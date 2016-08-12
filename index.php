<?php
include 'Config.class.php';
$config = new Config();
if (!$config->getConfigFile() || !$config->getSaveConfigDir()) {
    header('Location: parameters.php');
    die();
}

?>
<html>
<head>
    <title>BotCU - Bot Configuration Utility</title>
    <meta charset = "UTF-8">
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <script>
        // json2.js 2016-05-01
        "object"!=typeof JSON&&(JSON={}),function(){"use strict";function f(t){return 10>t?"0"+t:t}function this_value(){return this.valueOf()}function quote(t){return rx_escapable.lastIndex=0,rx_escapable.test(t)?'"'+t.replace(rx_escapable,function(t){var e=meta[t];return"string"==typeof e?e:"\\u"+("0000"+t.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+t+'"'}function str(t,e){var r,n,o,u,f,a=gap,i=e[t];switch(i&&"object"==typeof i&&"function"==typeof i.toJSON&&(i=i.toJSON(t)),"function"==typeof rep&&(i=rep.call(e,t,i)),typeof i){case"string":return quote(i);case"number":return isFinite(i)?i+"":"null";case"boolean":case"null":return i+"";case"object":if(!i)return"null";if(gap+=indent,f=[],"[object Array]"===Object.prototype.toString.apply(i)){for(u=i.length,r=0;u>r;r+=1)f[r]=str(r,i)||"null";return o=0===f.length?"[]":gap?"[\n"+gap+f.join(",\n"+gap)+"\n"+a+"]":"["+f.join(",")+"]",gap=a,o}if(rep&&"object"==typeof rep)for(u=rep.length,r=0;u>r;r+=1)"string"==typeof rep[r]&&(n=rep[r],o=str(n,i),o&&f.push(quote(n)+(gap?": ":":")+o));else for(n in i)Object.prototype.hasOwnProperty.call(i,n)&&(o=str(n,i),o&&f.push(quote(n)+(gap?": ":":")+o));return o=0===f.length?"{}":gap?"{\n"+gap+f.join(",\n"+gap)+"\n"+a+"}":"{"+f.join(",")+"}",gap=a,o}}var rx_one=/^[\],:{}\s]*$/,rx_two=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,rx_three=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,rx_four=/(?:^|:|,)(?:\s*\[)+/g,rx_escapable=/[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,rx_dangerous=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;"function"!=typeof Date.prototype.toJSON&&(Date.prototype.toJSON=function(){return isFinite(this.valueOf())?this.getUTCFullYear()+"-"+f(this.getUTCMonth()+1)+"-"+f(this.getUTCDate())+"T"+f(this.getUTCHours())+":"+f(this.getUTCMinutes())+":"+f(this.getUTCSeconds())+"Z":null},Boolean.prototype.toJSON=this_value,Number.prototype.toJSON=this_value,String.prototype.toJSON=this_value);var gap,indent,meta,rep;"function"!=typeof JSON.stringify&&(meta={"\b":"\\b","	":"\\t","\n":"\\n","\f":"\\f","\r":"\\r",'"':'\\"',"\\":"\\\\"},JSON.stringify=function(t,e,r){var n;if(gap="",indent="","number"==typeof r)for(n=0;r>n;n+=1)indent+=" ";else"string"==typeof r&&(indent=r);if(rep=e,e&&"function"!=typeof e&&("object"!=typeof e||"number"!=typeof e.length))throw Error("JSON.stringify");return str("",{"":t})}),"function"!=typeof JSON.parse&&(JSON.parse=function(text,reviver){function walk(t,e){var r,n,o=t[e];if(o&&"object"==typeof o)for(r in o)Object.prototype.hasOwnProperty.call(o,r)&&(n=walk(o,r),void 0!==n?o[r]=n:delete o[r]);return reviver.call(t,e,o)}var j;if(text+="",rx_dangerous.lastIndex=0,rx_dangerous.test(text)&&(text=text.replace(rx_dangerous,function(t){return"\\u"+("0000"+t.charCodeAt(0).toString(16)).slice(-4)})),rx_one.test(text.replace(rx_two,"@").replace(rx_three,"]").replace(rx_four,"")))return j=eval("("+text+")"),"function"==typeof reviver?walk({"":j},""):j;throw new SyntaxError("JSON.parse")})}();
    </script>
    <style>
        body {
            font-family: "courier";
            color: #CFA554;
            background-color: #111111;
        }
        th {
            text-align: right;
            background-color: #222222;
        }
        tr {
            background-color: #222222;
        }
        table {
            border-width: 3px;
            border-style: double;
            border-color: #CFA554;
        }
        .botRunning {
            color: green;
        }
        .botStoped {
            color: red;
        }
    </style>
</head>
<body>
    <h1>BotCU - Bot Configuration Utility</h1>
    <button id="load">Load config</button>
    <button id="save">Save</button>
    <label>
        Config file:
        <select id="configName"></select>
    </label>
    <button id="newConfig">New</button>
    <a href="parameters.php">Application parameters</a>
    <br>
    <div id="botStatus">
    NecroBot status : ...
    </div>
    <div id="editor" style="border-width: 2px"></div>
    <script>
        var languages = ['ca','cs','da','de','es','et','fr','gr','hu','id','it','lt','nl','no','pl','pt-br','pt-pt','ro','ru_RU','sv','th','tr','uk_UA.','vi','zh_CN','zh_tw'];
        var config = {};
        var camelCaseToWords = function(str){
            return str.match(/^[a-z]+|[A-Z][a-z]*/g).map(function(x){
                return x[0].toUpperCase() + x.substr(1).toLowerCase();
            }).join(' ');
        };
        function gel(id) {
            return document.getElementById(id);
        }
        function loadJsonFile() {
            $.ajax({
                async: true,
                type: 'get',
                url:  'getConfig.php',
                data:  {
                    'configName': $('#configName').val()
                },
                beforeSend: function (request) {
                    //$('#ajax-loading').show();
                },
                complete: function (request, json) {
                    config = JSON.parse(request.responseText);
                    buildEditor();
                },
                error: function (request, json) {
                    console.log(request.responseText);
                }
            });
        }

        function generateConfig() {
            var directiveEditorList = document.querySelectorAll('#editor input, #editor textarea');
            config = {};
            for (var directiveIndex in directiveEditorList) {
                var directiveElem = directiveEditorList[directiveIndex];
                if(directiveElem.id) {
                    if (directiveElem.tagName == 'INPUT' && directiveElem.getAttribute("type") == "checkbox") {
                        config[directiveElem.id] = directiveElem.checked;
                    } else if (directiveElem.tagName == 'INPUT' && directiveElem.getAttribute("type") == "number") {
                        config[directiveElem.id] = Number.parseFloat(directiveElem.value);
                    } else if (directiveElem.tagName == 'INPUT' && directiveElem.getAttribute("type") == "text") {
                        config[directiveElem.id] = directiveElem.value;
                    } else if (directiveElem.tagName == 'SELECT') {
                        config[directiveElem.id] = $(directiveElem).val();
                    } else if (directiveElem.tagName == 'TEXTAREA') {
                        config[directiveElem.id] = JSON.parse(directiveElem.innerHTML);
                    }
                }
            }
            saveConfig();
        }

        function saveConfig() {
            $.ajax({
                async: true,
                type: 'post',
                url:  'getConfig.php',
                data: {
                    'config': JSON.stringify(config, null, 4),
                    'configName': $('#configName').val()
                },
                beforeSend: function (request) {
                    //$('#ajax-loading').show();
                },
                complete: function (request, json) {

                }
            });
        }

        function buildEditor() {
            var editor = gel('editor');
            var html = '<table>';
            for (var directive in config) {

                if (directive == ''/*'TranslationLanguageCode'*/) {

                } else if (typeof config[directive] == "boolean") {
                    html += '<tr><th><label for="'+directive+'">'+camelCaseToWords(directive)+'</label></th><td><input type="checkbox" id="'+directive+'" '+(config[directive]?'checked="checked"':'')+'></td></tr>';
                } else if (typeof config[directive] == "number") {
                    html += '<tr><th><label for="'+directive+'">'+camelCaseToWords(directive)+'</label></th><td><input type="number" id="'+directive+'" value="'+config[directive]+'"></td></tr>';
                } else if (typeof config[directive] == "string") {
                    html += '<tr><th><label for="'+directive+'">'+camelCaseToWords(directive)+'</label></th><td><input type="text" id="'+directive+'" value="'+config[directive]+'"></td></tr>';
                } else {
                    html += '<tr><th><label for="'+directive+'">'+camelCaseToWords(directive)+'</label></th><td><textarea id="'+directive+'">'+JSON.stringify(config[directive], null, 4)+'</textarea></td></tr>';
                }
            }
            html += '</table>';
            editor.innerHTML = html;
        }

        function loadConfigList() {
            var configNameSelect = gel('configName');
            for (var i = configNameSelect.options.length; i > 0; i--) {
                configNameSelect.options[i].remove();
            }
            $.ajax({
                async: true,
                type: 'post',
                url:  'getConfig.php',
                data: {'configNames': 1},
                beforeSend: function (request) {
                    //$('#ajax-loading').show();
                },
                complete: function (request, json) {
                    var configNames = JSON.parse(request.responseText);
                    for (var confName in configNames) {
                        var option = document.createElement('option');
                        option.setAttribute('value', confName);
                        option.appendChild(document.createTextNode(configNames[confName]));
                        configNameSelect.appendChild(option);
                    }
                }
            });
        }

        function refreshBotStatus() {
            $.ajax({
                async: true,
                type: 'get',
                url:  'getConfig.php',
                data: {'botStatus': "status"},
                beforeSend: function (request) {
                    //$('#ajax-loading').show();
                },
                complete: function (request, json) {
                    var botStatus = JSON.parse(request.responseText);
                    if (botStatus['status']) {
                        $('#botStatus').html('NecroBot status : <span class="botRunning">running</span>'
                            +' <button id="botStop" onclick="stopBot()">Stop</button>');
                    } else {
                        $('#botStatus').html('NecroBot status : <span class="botStoped">stoped</span>'
                            +' <button id="botStart" onclick="startBot()">Start</button>');
                    }
                }
            });
        }

        function startBot() {
            $.ajax({
                async: true,
                type: 'get',
                url:  'getConfig.php',
                data: {'botStatus': "start"},
                beforeSend: function (request) {
                    //$('#ajax-loading').show();
                },
                complete: function (request, json) {
                    var botStatus = JSON.parse(request.responseText);
                }
            });
        }

        function stopBot() {
            $.ajax({
                async: true,
                type: 'get',
                url:  'getConfig.php',
                data: {'botStatus': "stop"},
                beforeSend: function (request) {
                    //$('#ajax-loading').show();
                },
                complete: function (request, json) {
                    var botStatus = JSON.parse(request.responseText);
                }
            });
        }

        $(document).on('click', '#save', function (e){
            generateConfig();
            return false;
        });

        $(document).on('click', '#load', function (e){
            loadJsonFile();
            return false;
        });
        $(document).on('click', '#newConfig', function (e){
            var configNameSelect = gel('configName');
            var newConf = prompt('New config file name');
            if (newConf) {
                var option = document.createElement('option');
                option.setAttribute('value', newConf);
                option.setAttribute('selected', 'selected');
                option.appendChild(document.createTextNode(newConf));
                configNameSelect.appendChild(option);
            }
            return false;
        });
        loadJsonFile();
        loadConfigList();
        setInterval("refreshBotStatus()", 3000);
    </script>
</body>
</html>