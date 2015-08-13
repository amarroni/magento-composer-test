<?php
/**
 * Created by PhpStorm.
 * User: JuanCarlos
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/** @var $store Mage_Core_Model_Store */
$storeView = Mage::getModel('core/store')->load('french','code');

$model = new Mage_Core_Model_Config();
// set base link url. This config is applied only once.
$model->saveConfig('web/unsecure/base_link_url', '{{unsecure_base_url}}', 'default', 0);
$model->saveConfig('web/secure/base_link_url', '{{secure_base_url}}', 'default', 0);
// SEO magento default. This config is applied only once.
$model->saveConfig('web/seo/use_rewrites', 1, 'default', 0);
$model->saveConfig('web/session/use_frontend_sid', 0, 'default', 0);
// unsecure
$model->saveConfig('web/unsecure/base_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'fr-FR/', 'websites', $storeView->getId());
$model->saveConfig('web/unsecure/base_skin_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'skin/', 'websites', $storeView->getId());
$model->saveConfig('web/unsecure/base_media_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/', 'websites', $storeView->getId());
$model->saveConfig('web/unsecure/base_js_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'js/', 'websites', $storeView->getId());
// secure
$model->saveConfig('web/secure/base_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'fr-FR/', 'websites', $storeView->getId());
$model->saveConfig('web/secure/base_skin_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'skin/', 'websites', $storeView->getId());
$model->saveConfig('web/secure/base_media_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'media/', 'websites', $storeView->getId());
$model->saveConfig('web/secure/base_js_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'js/', 'websites', $storeView->getId());
// set the currency
$model->saveConfig('currency/options/default', 'EUR', 'websites',  $storeView->getId());
$model->saveConfig('currency/options/allow', 'EUR', 'websites',  $storeView->getId());
// set locale
$model->saveConfig('general/country/default', 'FR', 'websites',  $storeView->getId());
$model->saveConfig('general/locale/timezone', 'Europe/Paris', 'websites',  $storeView->getId());
$model->saveConfig('general/locale/code', 'fr_FR', 'websites',  $storeView->getId());
// set the user and password to enable Arvato Credit Card
$userAndPass = Mage::helper('core')->encrypt('magento');
$model->saveConfig('paymentgateway_admin/settings/username', $userAndPass, 'websites',  $storeView->getId());
$model->saveConfig('paymentgateway_admin/settings/password', $userAndPass, 'websites',  $storeView->getId());
// set Arvato Payment Gateway
$model->saveConfig('payment/paymentgateway_creditcard/available_for_customer_groups', '0,1', 'websites',  $storeView->getId());
// REINDEX
/* @var $indexCollection Mage_Index_Model_Resource_Process_Collection */
$indexCollection = Mage::getModel('index/process')->getCollection();
foreach ($indexCollection as $index) {
    /* @var $index Mage_Index_Model_Process */
    $index->reindexAll();
}

$installer->endSetup();