<?php
namespace Tricosmic\Manager\Controller\Ajax;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class justforyou extends Action{

protected $request;
protected $resultPageFactory;
public function __construct(Context $context,\Magento\Framework\View\Result\PageFactory $resultPageFactory, array $data = []) {
	$this->resultPageFactory = $resultPageFactory;        
    parent::__construct($context);
}


public function execute()
{
   //Default message if validation failed.
    $block = "<div class=\"message info empty\"><div>We can't find products matching the selection.</div></div>";

    //Getting products list if validation passed

    $viewBlock = $this->resultPageFactory->create();
    $block = $viewBlock->getLayout()
        ->createBlock('Magento\Catalog\Block\Product\ListProduct')
        ->setTemplate('Magento_Theme::eezy/ajax_just_for_you.phtml')
        ->toHtml();


    //Setting response
    $response = $this->getResponse()->setBody($block);
    
    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
    $resultJson->setData($response);
    return $response;
}

}