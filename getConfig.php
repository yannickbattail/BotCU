<?php
include 'Config.class.php';
$config = new Config();

if (isset($_POST['config'])) {
    $config->saveConfig($_POST['config'], isset($_POST['configName'])?$_POST['configName']:null);
} else if (isset($_POST['configNames'])) {
    echo json_encode($config->getConfigNames());
} else if (isset($_POST['parameters'])) {
    echo json_encode(getCfgParameters());
} else if (isset($_GET['botStatus'])) {
    if ($_GET['botStatus'] == 'start') {
        echo json_encode(['status' => "starting".$config->startBot()]);
    } else if ($_GET['botStatus'] == 'stop') {
        echo json_encode(['status' => 'stopping'.$config->stopBot()]);
    } else {
        echo json_encode(['status' => $config->getBotStatus()]);
    }
} else if (isset($_GET['configName'])) {
    echo $config->getConfig($_GET['configName']);
}
