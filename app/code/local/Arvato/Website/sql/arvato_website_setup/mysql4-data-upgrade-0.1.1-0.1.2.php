<?php
/**
 * Created by PhpStorm.
 * User: juancarlosc
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

if (!Mage::registry('isSecureArea')) {
    Mage::register('isSecureArea', 1);
}
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

// create website United Kingdom

/* @var $category Mage_Catalog_Model_Category */
$category = Mage::getModel('catalog/category')->loadByAttribute('name','United Kingdom');
if (!$category) {
    $category = Mage::getModel('catalog/category');
    $category->setPath('1');
    $category->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID);
    $category->setName('United Kingdom');
    $category->setIsActive(true);
    $category->setIncludeInMenu(true);
    $category->save();
}

//addWebsite
/** @var $website Mage_Core_Model_Website */
$website = Mage::getModel('core/website');
$website->setCode('uk')
    ->setName('United Kingdom')
    ->setSortOrder(1)
    ->save();

//addStoreGroup
/** @var $storeGroup Mage_Core_Model_Store_Group */
$storeGroup = Mage::getModel('core/store_group');
$storeGroup->setWebsiteId($website->getId())
    ->setName('Store United Kingdom')
    ->setRootCategoryId($category->getId())
    ->save();

//#addStore english
/** @var $store Mage_Core_Model_Store */
$storeView = Mage::getModel('core/store');
$storeView->setCode('uk')
    ->setWebsiteId($storeGroup->getWebsiteId())
    ->setGroupId($storeGroup->getId())
    ->setName('United Kingdom')
    ->setIsActive(1)
    ->save();

$installer->endSetup();