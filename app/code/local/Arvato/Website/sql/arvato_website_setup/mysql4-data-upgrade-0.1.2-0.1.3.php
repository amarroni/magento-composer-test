<?php
/**
 * Created by PhpStorm.
 * User: juancarlosc
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/** @var $store Mage_Core_Model_Store */
$storeView = Mage::getModel('core/store')->load('uk','code');

$model = new Mage_Core_Model_Config();
// unsecure
$model->saveConfig('web/unsecure/base_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'en-GB/', 'websites', $storeView->getId());
$model->saveConfig('web/unsecure/base_skin_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'skin/', 'websites', $storeView->getId());
$model->saveConfig('web/unsecure/base_media_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/', 'websites', $storeView->getId());
$model->saveConfig('web/unsecure/base_js_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'js/', 'websites', $storeView->getId());
// secure
$model->saveConfig('web/secure/base_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'en-GB/', 'websites', $storeView->getId());
$model->saveConfig('web/secure/base_skin_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'skin/', 'websites', $storeView->getId());
$model->saveConfig('web/secure/base_media_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'media/', 'websites', $storeView->getId());
$model->saveConfig('web/secure/base_js_url', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . 'js/', 'websites', $storeView->getId());
// set the currency
$model->saveConfig('currency/options/default', 'GBP', 'websites',  $storeView->getId());
$model->saveConfig('currency/options/allow', 'GBP', 'websites',  $storeView->getId());
// set locale
$model->saveConfig('general/country/default', 'GB', 'websites',  $storeView->getId());
$model->saveConfig('general/locale/timezone', 'Europe/London', 'websites',  $storeView->getId());
$model->saveConfig('general/locale/code', 'en_GB', 'websites',  $storeView->getId());
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