<?php
/**
 * Created by PhpStorm.
 * User: JuanCarlos
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

Mage::register('isSecureArea', 1);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

// create website France

/* @var $category Mage_Catalog_Model_Category */
$category = Mage::getModel('catalog/category')->loadByAttribute('name','France');
if (!$category) {
    $category = Mage::getModel('catalog/category');
    $category->setPath('1');
    $category->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID);
    $category->setName('France');
    $category->setIsActive(true);
    $category->setIncludeInMenu(true);
    $category->save();
}

//addWebsite
/** @var $website Mage_Core_Model_Website */
$website = Mage::getModel('core/website');
$website->setCode('france')
    ->setName('France')
    ->setSortOrder(1)
    ->save();

//addStoreGroup
/** @var $storeGroup Mage_Core_Model_Store_Group */
$storeGroup = Mage::getModel('core/store_group');
$storeGroup->setWebsiteId($website->getId())
    ->setName('Store France')
    ->setRootCategoryId($category->getId())
    ->save();

//#addStore english
/** @var $store Mage_Core_Model_Store */
$storeView = Mage::getModel('core/store');
$storeView->setCode('french')
    ->setWebsiteId($storeGroup->getWebsiteId())
    ->setGroupId($storeGroup->getId())
    ->setName('French')
    ->setIsActive(1)
    ->save();

$installer->endSetup();