<?php

/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class TiphioPSPrometheus extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tiphiopsprometheus';
        $this->tab = 'analytics_stats';
        $this->version = '1.0.0';
        $this->author = 'Martin \"tiphedor\" STEFFEN';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Prometheus Exporter');
        $this->description = $this->l('A Prometheus exporter for Prestashop');

        $this->confirmUninstall = $this->l('Confirm uninstallation ? Metrics will break');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function handleFormSubmit()
    {
        $newBasicAuthUsername = Tools::getValue('TIPHIOPSPROMETHEUS_BASICAUTH_USER');
        $newBasicAuthPassword = Tools::getValue('TIPHIOPSPROMETHEUS_BASICAUTH_PASSWORD');

        if (!isset($newBasicAuthPassword) || !isset($newBasicAuthPassword)) {
            return;
        }

        $encodedCredentials = base64_encode($newBasicAuthUsername . ':' . $newBasicAuthPassword);
        Configuration::updateValue("TIPHIOPSPROMETHEUS_BASICAUTH_ENCODED_CREDENTIALS", $encodedCredentials);

        $this->context->smarty->assign('form_submitted', 1);
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitTiphiopsprometheusModule')) == true) {
            $this->handleFormSubmit();
        }


        $hostnameWithPrefix = Tools::getHttpHost(true);
        $hostname = str_replace('https://', '', $hostnameWithPrefix);
        $scrapFullUrl = $this->context->link->getModuleLink("tiphiopsprometheus", "metrics");
        $metricsPath = str_replace($hostnameWithPrefix, '', $scrapFullUrl);

        $this->context->smarty->assign('module_dir', $this->_path);
        $this->context->smarty->assign('metrics_path', $metricsPath);
        $this->context->smarty->assign('hostname', $hostname);


        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitTiphiopsprometheusModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {

        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Update Basic Auth Credentials'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-user"></i>',
                        'desc' => $this->l('Username used for the basic auth to protect your metrics from outside access.'),
                        'name' => 'TIPHIOPSPROMETHEUS_BASICAUTH_USER',
                        'label' => $this->l('Basic Auth username'),
                    ),
                    array(
                        'col' => 6,
                        'type' => 'text',
                        'prefix' => '<i class="icon padlock"></i>',
                        'desc' => $this->l('Password used for the basic auth to protect your metrics from outside access.'),
                        'name' => 'TIPHIOPSPROMETHEUS_BASICAUTH_PASSWORD',
                        'label' => $this->l('Basic Auth password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }
}
