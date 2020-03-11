<?php
namespace Tricosmic\Manager\Controller\Page;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\View\Result\Page;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\Exception\LocalizedException;

class View extends Action{
        /** @var  \Magento\Framework\View\Result\Page */
        protected $resultPageFactory;
        /**      * @param \Magento\Framework\App\Action\Context $context      */
        public function __construct(
            Context $context,
        PageFactory $resultPageFactory)
        {
            $this->resultPageFactory = $resultPageFactory;
            parent::__construct($context);
        }
        /**
         * Blog Index, shows a list of recent blog posts.
         *
         * @return \Magento\Framework\View\Result\PageFactory
         */
        public function execute()
        {
            $resultPage = $this->resultPageFactory->create();
            return $resultPage;
        }
}
?>