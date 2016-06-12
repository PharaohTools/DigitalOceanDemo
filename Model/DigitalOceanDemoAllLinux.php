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

    public function projectRoot() {
        $projectRoot = getcwd() ;
        return $projectRoot ;
    }

    public function getBastionPrivateKeyPath() {
        $bastion_key_location = getcwd().'/build/config/ptconfigure/ssh/keys/raw/bastion' ;
        return $bastion_key_location ;
    }

    public function getDevopsPrivateKeyPath() {
        $devops_key_location = getcwd().'/build/config/ptconfigure/ssh/keys/raw/devops' ;
        return $devops_key_location ;
    }

    public function getBastionPublicKeyPath() {
        $bastion_key_location = getcwd().'/build/config/ptconfigure/ssh/keys/raw/bastion.pub' ;
        return $bastion_key_location ;
    }

    public function getDevopsPublicKeyPath() {
        $devops_key_location = getcwd().'/build/config/ptconfigure/ssh/keys/raw/devops.pub' ;
        return $devops_key_location ;
    }

    public function noBastionSSHKeyExists() {
        $bastion_key_location = $this->getBastionPrivateKeyPath() ;
        if (file_exists($bastion_key_location)) { return false ; }
        return true ;
    }

    public function noDevopsSSHKeyExists() {
        $bastion_key_location = $this->getDevopsPrivateKeyPath() ;
        if (file_exists($bastion_key_location)) { return false ; }
        return true ;
    }

    public function findHost() {
        return "welovedigitalocean" ;
    }

    public function findEnvironmentLevel() {
        return "prod" ;
    }

    public function findSlug() {
        return "pharaohtools" ;
    }

    public function findDomain() {
        return $this->findSlug().".com" ;
    }

    public function findTarget($target_type, $target_scope = null) {
        $loggingFactory = new \Model\Logging() ;
        $logging = $loggingFactory->getModel($this->params) ;
        $logging->log("Trying to find target from {$target_type} ", $this->getModuleName()) ;
        $env_level = $this->findCompleteSlug()."-{$target_type}" ;
        $conf = \Model\AppConfig::getProjectVariable("environments") ;

        if ($target_scope == "public") { $target_scope_string = "target_public" ; }
        else if ($target_scope == "private") { $target_scope_string = "target_private" ; }
        else { $target_scope_string = "target" ; }


        $target = null ;
        foreach ($conf as $one_env) {
            if ($one_env["any-app"]["gen_env_name"] == $env_level) {
                $cou = count($one_env["servers"]) - 1 ;
                $target = $one_env["servers"][$cou][$target_scope_string]; } }
        return $target ;
    }

    public function findCompleteSlug() {
        $complete =
            $this->findHost().
            "-".
            $this->findSlug().
            "-".
            $this->findEnvironmentLevel() ;
        return $complete ;
    }

}

