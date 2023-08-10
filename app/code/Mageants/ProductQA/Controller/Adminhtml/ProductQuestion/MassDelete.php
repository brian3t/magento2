<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Controller\Adminhtml\ProductQuestion;

use \Magento\Ui\Component\MassAction\Filter;
use \Mageants\ProductQA\Model\ResourceModel\ProductQuestion\CollectionFactory;
use \Magento\Backend\App\Action\Context;

class MassDelete extends \Magento\Backend\App\Action
{
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Mageants_ProductQA::productquestion_massdel';
    /**
     * Mass Action Filter
     * 
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;

    /**
     * Collection Factory
     * 
     * @var \Mageants\ProductQA\Model\ResourceModel\ProductQuestion\CollectionFactory
     */
    protected $_collectionFactory;

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
        Context $context
    )
    {
        $this->_filter            = $filter;
		
        $this->_collectionFactory = $collectionFactory;
		
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

        $delete = 0;
		
        foreach ($collection as $productquestion) 
		{
            /** @var \Mageants\ProductQA\Model\ProductQuestion $productquestion */
            
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productquestionanswer = $objectManager->get('Mageants\ProductQA\Model\ProductQuestionAnswerFactory')->create();
            $questionanswer = $productquestionanswer->getCollection()->addFieldToFilter('question_id', $productquestion->getId());
            foreach($questionanswer as $answer){
                $answer->delete();
            }
            $productquestion->delete();
                
            $delete++;
        }
		
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $delete));
		
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
		
        return $resultRedirect->setPath('*/*/');
    }
}
