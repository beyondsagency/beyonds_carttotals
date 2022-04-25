<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Beyonds_carttotals extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'beyonds_carttotals';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Beyonds';
        $this->need_instance = 1;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Override cart totals');
        $this->description = $this->l('Override cart totals');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall my module?');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }
    
    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionPresentCart');
    }
    
    public function hookActionPresentCart($params)
    {
        $currentCartHasNotCarrier = !$this->context->cart->id_carrier;
        $isNotFreeDelivery = $params['presentedCart']['subtotals']['shipping']['amount'] != 0;

        if($currentCartHasNotCarrier && $isNotFreeDelivery){
            $params['presentedCart']['subtotals']['shipping']['value'] = $this->trans('To be determined', [], 'Modules.Beyondscarttotals.Shop');
            $params['presentedCart']['totals']['total']['value'] = $params['presentedCart']['subtotals']['products']['value'];
            $params['presentedCart']['totals']['total']['amount'] = $params['presentedCart']['subtotals']['products']['amount'];
        }
    }

}
