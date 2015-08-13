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
$model->saveConfig('carriers/flatrate/active', 0, 'default', 0);
$model->saveConfig('carriers/matrixrate/active', 0, 'default', 0);

$installer->endSetup();
