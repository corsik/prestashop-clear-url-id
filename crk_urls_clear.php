<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class Crk_urls_clear extends Module
{

    public function __construct()
    {
        $this->name = 'crk_urls_clear';
        $this->author = 'corsik';
        $this->version = '0.0.2';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Clear urls');
        $this->description = $this->l('The module makes it possible to delete id values from URLs');

        $this->ps_versions_compliancy = array('min' => '1.7.2', 'max' => _PS_VERSION_);

    }

    public function install()
    {
        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
}
