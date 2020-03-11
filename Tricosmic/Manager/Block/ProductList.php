<?php

namespace Tricosmic\Manager\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;
use Magento\Reports\Block\Product\Viewed as ReportProductViewed;
use Magento\Catalog\Model\CategoryFactory as collectionCategoryFactory;
use RLTSquare\CategoryPage\Helper\Collection as CollectionHelper;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as CollectionFactory;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
class ProductList extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $collection;
    protected $visibility;
    protected $recentViewed;
    protected $categoryFactory;
    protected $collectionHelper;
    protected $collectionFactory;
    protected $storeManager;
    //protected $_session;
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Catalog\Model\Product\Visibility $visibility,
        ReportProductViewed $recentViewed,
        collectionCategoryFactory $categoryFactory,
        CollectionHelper $collectionHelper,
        CollectionFactory $collectionFactory,
        StoreManager $storeManager,
        \Magento\Customer\Model\Session $session,
        Data $urlHelper,
        array $data = []
    )
    {
        $this->collection = $collection;
        $this->visibility = $visibility;
        $this->recentViewed = $recentViewed;
        $this->categoryFactory = $categoryFactory;
        $this->collectionHelper = $collectionHelper;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->_session = $session;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }
    public function recentViewedCollection(){
        return $this->recentViewed->getItemsCollection()->setPageSize(5);
    }
    protected function setSessionValue($value){
        $this->_session->setTestValue($value);
    }
    protected function getSessionValue(){
        return $this->_session->getTestValue();
    }
    public function unsSessionValue(){
        return $this->_session->unsTestValue();
    }
    public function recentJustForYouCollection(){
    	$collection = $this->recentViewed->getItemsCollection()->setPageSize(5);
    	$this->allCatIds = array();
    	$this->allProductIds = array();
    	$allCatIdCount = 0;
    	$highestProductPrice = 0;
        $counter2 = 0;
        $productsSessionCount = 0;
        $this->allSPIds = array();
    	foreach($collection as $product){
    		$catIds = $product->getCategoryIds(); 
    		$productPrice = $product->getPrice();
    		$idCount = count($catIds);
    		$this->allCatIds[$allCatIdCount] = $catIds[$idCount-1];
    		if($productPrice > $highestProductPrice){
    			$highestProductPrice = $productPrice;
    		}
    		$allCatIdCount++;
    	}
    	for ($i=0 ; $i < count($this->allCatIds);$i++) {
            $this->category = $this->categoryFactory->create()->load($this->allCatIds[$i]);
            $_productCollection = $this->category->getProductCollection()->addAttributeToSelect('*')->setPageSize(10);            
            $_productCollection = $this->collectionHelper->getAvailableProducts($_productCollection);
            foreach ($_productCollection as $_product){
                $this->allProductIds[$counter2] = $_product->getId();
                $counter2++;
            }
            
        }
        echo "get:".$valueCheck = $this->getSessionValue();
        echo "set".$valueCheck = $valueCheck +10;
        $this->setSessionValue($valueCheck);
        shuffle($this->allProductIds);
    	return $this->allProductIds;
    }
    public function getCollectionJfy(){
        //	session_start();
        if($this->getSessionValue() != null){
            $this->unsSessionValue();
        }
        $this->productIds = array();
    	$pCounter = 0;
    	$this->storeId = $this->storeManager->getStore()->getId();
    	$this->collectionJFY = $this->collectionFactory->create()->addMinimalPrice()->addFinalPrice()->addTaxPercents()->addAttributeToSelect('*')->addStoreFilter($this->storeId)->setPageSize(10);
    	//if(is_null($this->getSessionValue())){
    	$data = array();
    	if( $this->getSessionValue() != NULL ){
    	//$this->setSessionValue(array());
    	$data = $this->getSessionValue();
    	//$this->unsSessionValue();
    	echo "already set";
    	print_r($data);
    	}
        
        //$datas = $_SESSION['test_value'];
    	foreach($this->collectionJFY as $product){
    		$this->productIds[$pCounter] = $product->getId();
    		array_push($data, $product->getId());
    		$pCounter++;
    	}
    	//print_r($datas);
    	//$this->setSessionValue($sessionValue);
    	//$this->setSessionValue($data);
    	$valueCheck1 = array();
        //$valueCheck1 = $this->get
        //SessionValue();
       	//$valueCheck1 = $this->getSessionValue();
        //print_r($valueCheck1);
    	shuffle($this->productIds);
    	return $this->productIds;

    }

    public function firstGetCollectionJfy(){

    	//unset($_SESSION['test_value']);
    	$firstProductIds = array();
    	$firstProductIds = $this->getCollectionJfy();
        print_r($this->getSessionValue());
    	return $firstProductIds;
    }
    
    protected function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $limit = 30;
            //Getting configuration
            $this->collection->addAttributeToSelect('*');
            //Enable filter
            $this->collection->addAttributeToFilter(
                'status', array('eq' => \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            );

            //Stock filter
            $this->collection->joinField(
                'qty', 'cataloginventory_stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left'
            );

            $this->collection->setVisibility($this->visibility->getVisibleInSiteIds());

            //Setting up pagination
            $this->collection->setPageSize($limit)
                ->setCurPage(1);

            //This will sort in random order
            $this->collection->getSelect()->orderRand();
        }

        $this->_productCollection = $this->collection;

        return $this->_productCollection;
    }
}