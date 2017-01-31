<?php

if(!defined('_PS_VERSION_')) exit;
 
class ZEI extends Module {

    public function __construct() {
        $this->name = 'zei';
        $this->tab = 'zei_api';
        $this->version = '1.0';
        $this->author = 'Nazim from ZEI';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Zero ecoimpact');
        $this->description = $this->l('Link your company offers and rewards from Zero ecoimpact !');

        $this->confirmUninstall = $this->l('Are you sure to remove your link with ZEI ?');

        if (!Configuration::get('ZEI')) $this->warning = $this->l('No name provided');
    }

    public function install() {
        if(!parent::install()) return false;
        return true;
    }

    public function uninstall() {
        if(!parent::uninstall()) return false;
        return true;
    }

    public function getContent() {
        $output = null;
        if(Tools::isSubmit('submit'.$this->name)) {
            $error = false;

            $key = strval(Tools::getValue('zei_api_key'));
            $secret = strval(Tools::getValue('zei_api_secret'));
            $https = strval(Tools::getValue('zei_api_https'));

            if(!$key || empty($key) || 32 !== strlen($key) || !Validate::isGenericName($key)) {
                $output .= $this->displayError($this->l('Invalid API key'));
                $error = true;
            }

            if(!$error && (!$secret || empty($secret) || 45 !== strlen($secret) || !Validate::isGenericName($secret))) {
                $output .= $this->displayError($this->l('Invalid API secret'));
                $error = true;
            }

            if(!$error && ($https != 0 || $https != 1) && !Validate::isGenericName($https)) {
                $output .= $this->displayError($this->l('Invalid HTTPS option'));
                $error = true;
            }

            if(!$error) {
                $output .= $this->displayConfirmation($this->l('Settings updated'));
                Configuration::updateValue('zei_api_key', $key);
                Configuration::updateValue('zei_api_secret', $secret);
                Configuration::updateValue('zei_api_https', $https);
            }
        }
        return $output.$this->displayForm();
    }

    public function displayForm() {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;

        $form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('API Key'),
                    'desc' => 'Enter your ZEI API Key from your company tools.',
                    'name' => 'zei_api_key',
                    'size' => 32,
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('API Secret'),
                    'desc' => 'Enter your ZEI API Secret from your company tools.',
                    'name' => 'zei_api_secret',
                    'size' => 45,
                    'required' => true
                ),
                array(
                    'type' => 'radio',
                    'label' => $this->l('Use HTTPS'),
                    'desc' => 'Use or not secure API requests.',
                    'name' => 'zei_api_https',
                    'required'  => true,
                    'is_bool'   => true,
                    'values'    => array(
                        array(
                            'id'    => 'zei_api_https_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id'    => 'zei_api_https_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Global offer'),
                    'desc' => $this->l('Use a ZEI offer for the whole store.'),
                    'name' => 'zei_global_offer',
                    'required' => false,
                    'options' => array(
                        'query' => ['', 'Example'],
                        'id' => 'id_option',
                        'name' => 'name'
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper->fields_value['zei_api_key'] = Configuration::get('zei_api_key');
        $helper->fields_value['zei_api_secret'] = Configuration::get('zei_api_secret');

        $https = Configuration::get('zei_api_https');
        $helper->fields_value['zei_api_https'] = ($https == 0 || $https == 1) ? $https : 1;

        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        return $helper->generateForm($form);
    }

}
