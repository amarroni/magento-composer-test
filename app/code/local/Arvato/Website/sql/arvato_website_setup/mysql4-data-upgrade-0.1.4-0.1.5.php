<?php
/**
 * Created by PhpStorm.
 * User: juancarlosc
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

// tax class to United Kingdom
/* @var $modelTaxClass Mage_Tax_Model_Class */
$modelTaxClass = Mage::getModel('tax/class')->load('Tax UK', 'class_name');
if (!$modelTaxClass->getId()) {
    $modelTaxClass = Mage::getModel('tax/class');
    $modelTaxClass->setClassName('Tax UK');
    $modelTaxClass->setClassType(Mage_Tax_Model_Class::TAX_CLASS_TYPE_PRODUCT);
    $modelTaxClass->save();
}

$rateModel = Mage::getModel('tax/calculation_rate')->load('GB-*-*-Rate 1','code');
if (!$rateModel->getId()) {
    /* @var $rateModel Mage_Tax_Model_Calculation_Rate */
    $rateModel = Mage::getModel('tax/calculation_rate');
    $rateModel->setCode('GB-*-*-Rate 1');
    $rateModel->setTaxCountryId('GB');
    $rateModel->setTaxRegionId(0);
    $rateModel->setZipIsRange(0);
    $rateModel->setTaxPostcode('*');
    $rateModel->setRate(20);
    $rateModel->save();
}

/* @var $ruleModel Mage_Tax_Model_Calculation_Rule */
$ruleModel = Mage::getModel('tax/calculation_rule')->load('Retail Customer-Taxable Goods-Rate UK 1','code');
if (!$ruleModel->getId()) {
    /* @var $taxClass Mage_Tax_Model_Class */
    $taxClass = Mage::getModel('tax/class');

    /* @var $taxClassCollection Mage_Tax_Model_Resource_Class_Collection */
    $taxClassCollection = $taxClass->getCollection();
    $taxClassCollection
        ->addFieldToFilter('class_type', Mage_Tax_Model_Class::TAX_CLASS_TYPE_CUSTOMER)
        ->addFieldToFilter('class_name', 'Retail Customer');
    $taxClass = $taxClassCollection->getFirstItem();

    if (!$taxClass->getId()) {
        $taxClass->setClassName('Retail Customer');
        $taxClass->setClassType(Mage_Tax_Model_Class::TAX_CLASS_TYPE_CUSTOMER);
        $taxClass->save();
    }

    $ruleModel->setCode('Retail Customer-Taxable Goods-Rate UK 1');
    $ruleModel->setTaxCustomerClass(array($taxClass->getId()));
    $ruleModel->setTaxProductClass(array($modelTaxClass->getId()));
    $ruleModel->setTaxRate(array($rateModel->getId()));
    $ruleModel->setPriority(0);
    $ruleModel->setCalculateSubtotal(0);
    $ruleModel->save();
}

$installer->endSetup();