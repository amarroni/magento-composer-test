<?php
/**
 * Created by PhpStorm.
 * User: juancarlosc
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$model = new Mage_Core_Model_Config();
// disable guest checkout
$model->saveConfig('checkout/options/guest_checkout', 0, 'default', 0);
$model->saveConfig('checkout/options/customer_must_be_logged', 1, 'default', 0);

// disable payment method ccsave
$model->saveConfig('payment/ccsave/active', 0, 'default', 0);
$model->saveConfig('payment/checkmo/active', 0, 'default', 0);

// set Arvato Payment Gateway
/** @var $store Mage_Core_Model_Store */
$storeViewFrench = Mage::getModel('core/store')->load('french','code');
$model->saveConfig('payment/paymentgateway_creditcard/available_for_customer_groups', '0,1', 'websites',  $storeViewFrench->getId());
$model->saveConfig('payment/paymentgateway_paypal/available_for_customer_groups', '0,1', 'websites',  $storeViewFrench->getId());

/** @var $store Mage_Core_Model_Store */
$storeViewUk = Mage::getModel('core/store')->load('uk','code');
$model->saveConfig('payment/paymentgateway_creditcard/available_for_customer_groups', '0,1', 'websites',  $storeViewUk->getId());
$model->saveConfig('payment/paymentgateway_paypal/available_for_customer_groups', '0,1', 'websites',  $storeViewUk->getId());

$installer->endSetup();