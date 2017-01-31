<?php

if(!defined('_PS_VERSION_')) exit;
 
class ZEI extends Module {

    public function __construct() {
        $this->name = 'zei';
        $this->tab = 'zei_api';
        $this->version = '1.0';
        $this->author = 'Nazim from ZEI';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Zero ecoimpact');
        $this->description = $this->l('Link your company offers and rewards from Zero ecoimpact !');

        $this->confirmUninstall = $this->l('Are you sure to remove your link with ZEI ?');

        if (!Configuration::get('ZEI_NAME')) $this->warning = $this->l('No name provided');
    }

}
