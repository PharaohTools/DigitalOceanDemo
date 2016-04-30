<?php

Namespace Model;

class DigitalOceanDemoAllLinux extends BaseLinuxApp {

    // Compatibility
    public $os = array("any") ;
    public $linuxType = array("any") ;
    public $distros = array("any") ;
    public $versions = array("any") ;
    public $architectures = array("any") ;

    // Model Group
    public $modelGroup = array("Default") ;
    public $docroot ;

    public function __construct($params) {
        unset($params["vhe-url"]) ;
        parent::__construct($params);
    }

    public function setDocRoot() {
        $this->docroot = "" ;
        if (isset($this->params["docroot"])) {
            $this->docroot = $this->params["docroot"] ; }
    }

    public function setVirtufile() {
        $vflbo = $this->docroot."/VirtufileBase.php";
        $vflo = $this->docroot."/Virtufile";
        if (file_exists($vflbo) && file_exists($vflo)) {
            require_once($vflbo);
            require_once($vflo);
            $this->virtufile = new \Model\Virtufile ; }
    }

    public function getGuestPath() {
        $this->setDocRoot();
        return $this->params["docroot"] ;
    }

    public function getHostPath() {
        $this->setDocRoot();
        return $this->params["docroot"] ;
    }

    public function getVMName() {
        $this->setVirtufile();
        return $this->virtufile->config["vm"]["name"] ;
    }

    public function findHost() {
        return "welovedigitalocean" ;
    }

    public function findEnvironmentLevel() {
        return "prod" ;
    }

    public function findSlug() {
        return $this->findHost() ;
    }

    public function findDomain() {
        return $this->findSlug().".com" ;
    }

    public function findWebServerTarget() {
        $env_level = $this->findCompleteSlug()."-bastion" ;
        $conf = \Model\AppConfig::getProjectVariable("environments") ;
        $balancer_target = null ;
        foreach ($conf as $one_env) {
            // $logging->log("Looking for env {$env_level} in {$one_env["any-app"]["gen_env_name"]}", $this->getModuleName()) ;
            if ($one_env["any-app"]["gen_env_name"] == $env_level) {
                $cou = count($one_env["servers"]) - 1 ;
                $balancer_target = $one_env["servers"][$cou]["target"]; } }
        return $balancer_target ;
    }

    public function findCompleteSlug() {
        $complete =
            $this->findHost().
            "_".
            $this->findSlug().
            "_".
            $this->findEnvironmentLevel() ;
        return $complete ;
    }

    public function findProviderDefaultKey() {
        return "goldenballs" ;
    }

    public function getWebServerSSHData() {
        $sshData = <<<"SSHDATA"
sudo apt-get update -y
sudo apt-get install php5 git -y
cd /tmp
git clone http://github.com/PharaohTools/ptconfigure.git
sudo php ptconfigure/install-silent
sudo ptconfigure autopilot execute --autopilot-file="/tmp/cm-webserver.dsl.php"
rm /tmp/cm-webserver.dsl.php
SSHDATA;
        return $sshData ;
    }

}

