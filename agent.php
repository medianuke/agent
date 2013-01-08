<?php
#	Агентский скрипт Media-nuke.com

#	настройки
define('accountID', "1");  # ID аккаунта (кому идут деньги)
define('SYSTEM', "https://media-nuke.com/api/"); # системный адрес, не изменять
define('KEY', "testkey123");	# персональный ключ защиты

class mediaNukeAgent {

    public $agentSystem = SYSTEM;
    public $softwate = NULL;
    public $advertArea = NULL;

    protected function getFromSystem($cmd) {
        $url = $this->agentSystem . $cmd . "?key=" . KEY;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Media-nuke.com Money Agent 1.0 RUSSIAN KEY ' . KEY);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    public function initSoftware() {
        return $this->getFromSystem('initSoftware');
    }

    public function initAdvertArea($area) {
        return $this->getFromSystem('initAdvertArea/' . $area . '/');
    }

    public function getFromArea($systemCMD) {
        $url = $this->advertArea . $systemCMD;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, $this->softwate);
        $result = curl_exec($curl);
        curl_close($curl);
        die($result);
    }

}

$systemCMD = addslashes($_GET['systemCMD']);
$systemArea = intval($_GET['area']);
$systemKey = addslashes($_GET['key']);

if ($systemKey == md5(KEY)) {
    if ($systemCMD=="test") die('active');
    $agent = new mediaNukeAgent();
    $agent->softwate = $agent->initSoftware();
    $agent->advertArea = $agent->initAdvertArea($systemArea);
    $agent->getFromArea($systemCMD);
} else {
    die("access denied");
}
?>