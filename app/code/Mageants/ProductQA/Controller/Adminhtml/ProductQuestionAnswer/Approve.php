<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Controller\Adminhtml\ProductQuestionAnswer;

use \Magento\Ui\Component\MassAction\Filter;
use \Mageants\ProductQA\Model\ProductQuestionAnswerFactory;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Magento\Backend\App\Action\Context;
use Mageants\ProductQA\Model\Source\Status;
use Magento\Framework\UrlInterface;
use \Mageants\ProductQA\Helper\Email as QaEmailHelper;

class Approve extends \Magento\Backend\App\Action
{
	const MAIL_SEND_SUCCESS = 1;
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product = false;
    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $_imageHelperFactory = false;
    /**
     * @var \Mageants\ProductQA\Model\ResourceModel\ProductQuestionFactory
     */
    protected $_productQuestion = false;
    /**
     * @var \Mageants\ProductQA\Helper\Email
     */
    protected $_qaEmailHelper;	
    /**
     * @var \Mageants\ProductQA\Helper\Data
     */
    protected $_qaHelper;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_timezone;
	/**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
	protected $_storeManager;
    /**
     * @var UrlInterface
     */
    private $_urlBuilder;
	/**
     * Access Resource ID
     * 
     */
	const RESOURCE_ID = 'Mageants_ProductQA::productqnswer_approve';
	/**
	* Dis approve url
	*/
	const URL_ANSWER_REJECT = 'mageants_productqa/productquestionanswer/reject';
    /**
     * Mass Action Filter
     * 
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_filter;
    /**
     * Question Answer Factory
     * 
     * @var \Mageants\ProductQA\Model\QuestionAnswerAnswerFactory
     */
    protected $_productQuestionAnswerFactory;
    /**
     * constructor
     * 
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Context $context
     */
    public function __construct(
        Filter $filter,
        ProductQuestionAnswerFactory $productQuestionAnswerFactory,
		JsonFactory $jsonFactory,
		QaEmailHelper $qaEmailHelper,
        Context $context
    )
    {
        $this->_filter = $filter;
		
        $this->_productQuestionAnswerFactory = $productQuestionAnswerFactory;
		
	    $this->_jsonFactory = $jsonFactory;
		
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
        $answerFaktory = $this->_productQuestionAnswerFactory->create();
		
		$id = $this->getRequest()->getParam("id");
		
		$jsonResultFactory = $this->_jsonFactory->create();
		
		$status = 0;
		
		$message = "Error! while  changeing status.";
		
		if($id)
		{
			$answerFaktory->load($id);
			
			$answerFaktory->setStatus(Status::STATUS_APPROVE);
			
			$answerFaktory->save();
			
			$this->_qaEmailHelper->sendEmail($answerFaktory);
			
			$status = 1;
			
			$message = "Answer Approved successfully.";
		}
		
		$jsonResultFactory = $this->_jsonFactory->create();
		
		 $resultData = [
				'status' => $status,
				'status_text' => __("Reject Now"),				
				'prev_status_text' => __("Approved"),
				'message' => $message ,
				'url'=> $this->getUrl(static::URL_ANSWER_REJECT, ['id' => $id ] )
		];

		return $jsonResultFactory->setData($resultData);
    }
}
