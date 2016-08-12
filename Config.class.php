<?php

class Config {
    const PARAMETERS_FILE_NAME = 'parameters.json';
    const BOT_EXE_NAME = 'NecroBot.exe';

    protected $configFile = '';
    protected $saveConfigDir = '';
    /**
     * null if not init
     * 0 if no process
     * int the processId
     * 
     * @var null|int
     */
    protected $botPid = null;

    /**
     * null if not init
     * 0 if no process
     * int the processId
     *
     * @return int|null
     */
    public function getBotPid()
    {
        if ($this->botPid === null) {
            $cmd = 'tasklist /FI "IMAGENAME eq '.self::BOT_EXE_NAME.'" /FO CSV /NH';
            $task = shell_exec($cmd);
            $taskInfo = explode(',', str_replace('"', '', trim($task)));
            if (count($taskInfo) < 5) {
                $this->botPid = 0;
            } else {
                $this->botPid = (int) $taskInfo[1];
            }
        }
        return $this->botPid;
    }

    /**
     * @param int $botPid
     * @return Config
     */
    public function setBotPid($botPid)
    {
        $this->botPid = $botPid;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }


    /**
     * @param string $configFile
     * @return $this
     * @throws Exception
     */
    public function setConfigFile($configFile)
    {
        if (!is_file($configFile)) {
            throw new Exception('file '.$configFile.' does not exist');
        }
        $this->configFile = $configFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getSaveConfigDir()
    {
        return $this->saveConfigDir;
    }

    /**
     * @param string $saveConfigDir
     * @return Config
     * @throws Exception
     */
    public function setSaveConfigDir($saveConfigDir)
    {
        if (!is_dir($this->saveConfigDir)) {
            throw new Exception('Directory '.$this->saveConfigDir.' does not exist');
        }
        $this->saveConfigDir = $saveConfigDir;
        return $this;
    }

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $json = file_get_contents(self::PARAMETERS_FILE_NAME);
        if ($json) {
            $cfg = json_decode($json, true);
            if ($cfg) {
                $this->configFile = $cfg['configFile'];
                $this->saveConfigDir = $cfg['saveConfigDir'];

            }
        }
    }

    /**
     *
     */
    public function saveParameters() {
        $parameters = [
            "configFile" => $this->configFile,
            "saveConfigDir" => $this->saveConfigDir
        ];
        file_put_contents(self::PARAMETERS_FILE_NAME, json_encode($parameters));
    }

    /**
     * @param $config
     * @param null $configName
     * @throws Exception
     */
    public function saveConfig($config, $configName = null) {
        if ($configName) {
            $configFile = $this->saveConfigDir.'/'.$configName.'.json';
        } else {
            $configFile = $this->configFile;
        }
        if (!json_decode($config, true)) {
            throw new Exception('Invalid json '.json_last_error_msg());
        }
        if (file_put_contents($configFile, $config) === false) {
            throw new Exception('cannot write config to file '.$configFile);
        }
    }

    /**
     * @param null $configName
     * @return string
     * @throws Exception
     */
    public function getConfig($configName = null) {
        if ($configName) {
            $configFile = $this->saveConfigDir.'/'.$configName.'.json';
        } else {
            $configFile = $this->configFile;
        }
        $json = file_get_contents($configFile);
        if (!json_decode($json, true)) {
            throw new Exception('Invalid json '.json_last_error_msg());
        }
        if ($json === false) {
            throw new Exception('cannot read config from file '.$configFile);
        }
        return $json;
    }

    public function getConfigNames() {
        $iterator = new GlobIterator($this->saveConfigDir.'/'.'*.json', FilesystemIterator::KEY_AS_FILENAME);
        $configNames = array("" => "<Running Config>");
        foreach ($iterator as $item) {
            $configNames[$item->getBasename('.json')] = $item->getBasename('.json');
        }
        return $configNames;
    }

    public function startBot() {
        if ($this->getBotPid()) {
            throw new Exception('Bot already running');
        }
        $dir = dirname($this->getConfigFile()); // "Config" folder
        $dir = dirname($dir); // NecroBot folder
        //$cmd = 'start /D "'.$dir.'" "'.self::BOT_EXE_NAME.'"';
        $cmd = 'start /D "'.$dir.'" /B "'.self::BOT_EXE_NAME.'"';
        var_dump($cmd);
        //shell_exec($cmd);
    }

    public function stopBot() {
        if (!$this->getBotPid()) {
            throw new Exception('Bot not running');
        }
        $cmd = 'taskkill /IM "'.self::BOT_EXE_NAME.'"';
        //$cmd = 'taskkill /PID "'.$this->getBotPid().'"';
        shell_exec($cmd);
    }

    public function getBotStatus() {
        return $this->getBotPid() != 0;
    }
}
