<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Controller\Adminhtml\ProductQuestionAnswer;

use \Magento\Ui\Component\MassAction\Filter;
use \Mageants\ProductQA\Model\ResourceModel\ProductQuestionAnswer\CollectionFactory;
use \Magento\Backend\App\Action\Context;
use Mageants\ProductQA\Model\Source\Status;
use \Mageants\ProductQA\Helper\Email as QaEmailHelper;

class MassReject extends \Magento\Backend\App\Action
{
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Mageants_ProductQA::productanswer_massreject';
    /**
     * Mass Action Filter
     * 
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;

    /**
     * Collection Factory
     * 
     * @var \Mageants\ProductQA\Model\ResourceModel\ProductQuestionAnswer\CollectionFactory
     */
    protected $_collectionFactory;
    /**
     * @var \Mageants\ProductQA\Helper\Email
     */
    protected $_qaEmailHelper;

    /**
     * constructor
     * 
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
        CollectionFactory $collectionFactory,
        QaEmailHelper $qaEmailHelper,
        Context $context
    )
    {
        $this->_filter            = $filter;
		
        $this->_collectionFactory = $collectionFactory;

        $this->_qaEmailHelper = $qaEmailHelper;
		
        parent::__construct($context);
    }


	/*
	 * Check permission via ACL resource
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed(Self::RESOURCE_ID);
	}
	
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());

        $reject = 0;
		
        foreach ($collection as $productquestionanswer) 
		{
            /** @var \Mageants\ProductQA\Model\ProductQuestionAnswer $productquestionanswer */
            $productquestionanswer->setStatus(Status::STATUS_REJECT)->save();
			$this->_qaEmailHelper->sendEmail($productquestionanswer);
            $reject++;
        }
		
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been rejected.', $reject));
		
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		
        return $resultRedirect->setPath('*/*/');
    }
}
