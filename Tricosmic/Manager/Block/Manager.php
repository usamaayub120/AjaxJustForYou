<?php
namespace Tricomsic\Manager\Block;


class Manager extends \Magento\Framework\View\Element\Template
{
	public function __construct
    (
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        array $data = []
    ){
    	$this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data);
    }
    public function _prepareLayout()
    {
    	$this->pageConfig->getTitle()->set(__("Home Page"));
        return parent::_prepareLayout();
    }
}