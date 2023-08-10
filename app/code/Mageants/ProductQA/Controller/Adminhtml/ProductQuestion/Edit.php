<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Controller\Adminhtml\ProductQuestion;

use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Mageants\ProductQA\Model\ProductQuestionFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Backend\App\Action\Context;

class Edit extends \Mageants\ProductQA\Controller\Adminhtml\ProductQuestion
{
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Mageants_ProductQA::productquestion_new_edit';
    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Page factory
     * 
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Result JSON factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * constructor
     * 
     * @param Session $backendSession
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param ProductQuestionFactory $productquestionFactory
     * @param Registry $registry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        ProductQuestionFactory $productquestionFactory,
        Registry $registry,
        Context $context
    )
    {
        $this->_backendSession = $context->getSession();
		
        $this->_resultPageFactory = $resultPageFactory;
		
        parent::__construct($productquestionFactory, $registry, $context);
    }

    /*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed(Self::RESOURCE_ID);
	}

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
		
        /** @var \Mageants\ProductQA\Model\ProductQuestion $productquestion */
        $productquestion = $this->_initProductQuestion();
		
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
		
        $resultPage->setActiveMenu('Mageants_ProductQA::productquestion');
		
        $resultPage->getConfig()->getTitle()->set(__(' Product Question'));
		
        if ($id) 
		{
            $productquestion->load($id);
			
            if (!$productquestion->getId()) 
			{
                $this->messageManager->addError(__('This ProductQuestion no longer exists.'));
				
                $resultRedirect = $this->_resultRedirectFactory->create();
				
                $resultRedirect->setPath(
                    'mageants_productqa/*/edit',
                    [
                        'id' => $productquestion->getId(),
                        '_current' => true
                    ]
                );
				
                return $resultRedirect;
            }
        }
		
        $title = $productquestion->getId() ? $productquestion->getName() : __('New Product Question');
		
        $resultPage->getConfig()->getTitle()->prepend($title);
		
        $data = $this->_backendSession->getData('mageants_productqa_productquestion_data', true);
		
        if (!empty($data)) 
		{
            $productquestion->setData($data);
        }
		
        return $resultPage;
    }
}
