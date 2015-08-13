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

try {
    $_websites = Mage::getModel('core/website')->getCollection();
    $_websites->addFieldToFilter('website_id', array('nin'=>array(0,1)));

    /* @var $website Mage_Core_Model_Website */
    foreach ($_websites as $website) {
        if ($website->getCode() != 'base') {
            $rootCatId = $website->getDefaultStore()->getRootCategoryId();
            $rootCategory = Mage::getModel('catalog/category')->load($rootCatId);

            $categoryCollection = Mage::getModel('catalog/category')->getCollection();
            $categoryCollection->addAttributeToFilter('name', 'Printers');
            $categoryCollection->addAttributeToFilter('parent_id', $rootCategory->getId());
            $categoryPrinter = $categoryCollection->getFirstItem();

            if (!$categoryPrinter->getId()) {
                $categoryPrinter = Mage::getModel('catalog/category');
                $categoryPrinter->setName('Printers');
                $categoryPrinter->setUrlKey('printers');
                $categoryPrinter->setIsActive(1);
                $categoryPrinter->setDisplayMode('PRODUCTS');
                $categoryPrinter->setIsAnchor(0); //for active achor
                $categoryPrinter->setStoreId($rootCategory->getStoreId());
                $categoryPrinter->setPath($rootCategory->getPath());
                $categoryPrinter->save();
            }

            $categoryCollection->clear();

            // second category
            $categoryCollection = Mage::getModel('catalog/category')->getCollection();
            $categoryCollection->addAttributeToFilter('name', 'Ink Cartriges');
            $categoryCollection->addAttributeToFilter('parent_id', $rootCategory->getId());
            $categoryInkCartridge = $categoryCollection->getFirstItem();

            if (!$categoryInkCartridge->getId()) {
                $categoryInkCartridge = Mage::getModel('catalog/category');
                $categoryInkCartridge->setName('Ink Cartriges');
                $categoryInkCartridge->setUrlKey('ink-cartridge');
                $categoryInkCartridge->setIsActive(1);
                $categoryInkCartridge->setDisplayMode('PRODUCTS');
                $categoryInkCartridge->setIsAnchor(0); //for active achor
                $categoryInkCartridge->setStoreId($rootCategory->getStoreId());
                $categoryInkCartridge->setPath($rootCategory->getPath());
                $categoryInkCartridge->save();
            }

            // CREATE PRODUCTS

            $c = 2;
            for($i = 0; $i < $c; $i++) {
                // set madatory system attributes
                $rand = rand(1, 9999);

                /* @var $product Mage_Catalog_Model_Product */
                $product = Mage::getModel('catalog/product');

                $product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)// e.g. Mage_Catalog_Model_Product_Type::TYPE_SIMPLE
                ->setAttributeSetId(4)// default attribute set
                ->setSku('example_sku' . $rand)// generate some random SKU
                ->setWebsiteIds(array($website->getId()));

                $product->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) // visible in catalog and search
                ;

                // configure stock
                $product->setStockData(array(
                    'is_in_stock' => 1,
                    'qty' => 9999
                ));

                // optimize performance, tell Magento to not update indexes
                $product->setIsMassupdate(true)->setExcludeUrlRewrite(true);

                // finally set custom data
                $product
                    ->setName('Test Product #' . $rand)// add string attribute
                    ->setDescription("Globally morph end-to-end e-commerce without intuitive leadership. Compellingly customize exceptional e-services through resource-leveling testing procedures. Enthusiastically target accurate processes without premier mindshare. Enthusiastically simplify performance based materials via accurate customer service. Rapidiously reintermediate visionary sources without client-based strategic theme areas.
Phosfluorescently incentivize revolutionary infomediaries with fully tested deliverables. Globally provide access to focused quality vectors whereas interoperable data. Holisticly leverage other's go forward initiatives whereas high standards in solutions. Conveniently conceptualize top-line outsourcing and just in time technologies. Dramatically brand flexible models after diverse imperatives.
Collaboratively scale distinctive methodologies and.")

                    ->setShortDescription("Globally morph end-to-end e-commerce without intuitive leadership. Compellingly customize exceptional e-services through resource-leveling testing procedures. Enthusiastically target accurate processes without premier mindshare. Enthusiastically simplify performance based materials via accurate customer service. Rapidiously reintermediate visionary sources without client-based strategic theme areas.
Phosfluorescently incentivize revolutionary infomediaries with fully tested deliverables. Globally provide access to focused quality vectors whereas interoperable data. Holisticly leverage other's go forward initiatives whereas high standards in solutions. Conveniently conceptualize top-line outsourcing and just in time technologies. Dramatically brand flexible models after diverse imperatives.
Collaboratively scale distinctive methodologies and.")

                    ->setData('arvato_hs_code', $rand)
                    ->setData('arvato_country_of_origin', 83)
                    ->setData('arvato_main_category', 271)
                    ->setData('arvato_sub_category', 272)
                    // set up prices
                    ->setPrice(24.50)
//                ->setSpecialPrice(19.99)
                    ->setTaxClassId(2)// Taxable Goods by default
                    ->setWeight(87);

                switch($i) {
                    case 0: $product->setCategoryIds(array($categoryPrinter->getId()));
                        // add product images
                        $images = array(
                            'thumbnail'   => "photo_013.jpg",
                            'small_image' => "photo_011.jpg",
                            'image'       => "photo_012.jpg"
                        );
                        break;

                    case 1: $product->setCategoryIds(array($categoryInkCartridge->getId()));
                        // add product images
                        $images = array(
                            'thumbnail'   => "photo_011.jpg",
                            'small_image' => "photo_012.jpg",
                            'image'       => "photo_013.jpg"
                        );
                        break;
                }

                // Apply Tax
                switch($website->getCode()) {
                    case 'france':
                        $modelTaxClassFrance = Mage::getModel('tax/class')->load('Tax France', 'class_name');
                        if ($modelTaxClassFrance->getId()) {
                            $product->setTaxClassId($modelTaxClassFrance->getId());
                        }
                        break;
                    case 'uk':
                        $modelTaxClassUk = Mage::getModel('tax/class')->load('Tax UK', 'class_name');
                        if ($modelTaxClassUk->getId()) {
                            $product->setTaxClassId($modelTaxClassUk->getId());
                        }
                        break;
                }

                $dir = Mage::getModuleDir('data', 'Arvato_Website') . DS . 'media/';

                foreach ($images as $imageType => $imageFileName) {
                    $path = $dir . $imageFileName;
                    if (file_exists($path)) {
                        try {
                            $product->addImageToMediaGallery($path, $imageType, false, false);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    } else {
                        Mage::log("Can not find image by path: {$path}");
                    }
                }

                $product->save();

                $product->clearInstance();
            }
        }
    }
} catch (Exception $e) {
    Mage::log($e->getMessage());
}

$installer->endSetup();
