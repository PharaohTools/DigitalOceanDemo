<?php

Namespace Info;

class DigitalOceanDemoInfo extends Base {

  public $hidden = false;

  public $name = "Digital Ocean Demo Module";

  public function __construct() {
    parent::__construct();
  }

  public function routesAvailable() {
    return array( "DigitalOceanDemo" =>  array_merge(parent::routesAvailable(), array("install") ) );
  }

  public function routeAliases() {
    return array("DigitalOceanDemo"=>"DigitalOceanDemo", "dodemo"=>"DigitalOceanDemo");
  }

  public function helpDefinition() {
    $help = <<<"HELPDATA"
  This is a dummy Linux module that doesn't execute any commands.

  DigitalOceanDemo, DigitalOceanDemo

        - install
        Installs nothing
        example: ptconfigure DigitalOceanDemo install

HELPDATA;
    return $help ;
  }

}