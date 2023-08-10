<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Block\Adminhtml\ProductQuestion\Edit\Tab;

use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\Registry;
use \Mageants\ProductQA\Model\ResourceModel\ProductQuestionAnswer\CollectionFactory as AnswerCollection;
use \Mageants\ProductQA\Helper\Data;
use Magento\Customer\Model\Session as CustomerSession;
use Mageants\ProductQA\Model\Source\Status;
use Magento\Store\Model\StoreManagerInterface;

class QAnswer extends \Mageants\ProductQA\Block\ProductQAnswer
{
	/**
     * @var int
     */
   protected $_status ;
	
	/**
     * @var $_storeManager
     */
	protected $_storeManager;	
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
		Data $helper,
		AnswerCollection $productQuestionAnswer,
		Registry $coreRegistry,
		Context $context,
		CustomerSession $_customerSession,
		Status $status,
		array $data = []
	) 
	{	
		$this->setTemplate('productqa/answers.phtml');
		
        parent::__construct($helper	,$productQuestionAnswer,$coreRegistry,$context,$_customerSession,$data);
		
		//$this->_page_size = 5;
		
		$this->_storeManager = $context->getStoreManager();
				
		$this->_status = $status->getOptionArray();
		
    }	
	/**
     * canShowLoadMore
     *
	 * return boolean
     */
	public function canShowLoadMore()
	{
		return $this->getTotalQuestion() > $this->getProductQaHelper()->getQuestionPageSize() ? true : false;		
	}
	/**
     * isApprove
     * 
	 * return boolean
     */	
	public function isApprove($status_id = 0)
	{
		return $status_id == Status::STATUS_APPROVE ? true : false;
	}
	/**
     * getStatusText
     * 
	 * return string url
     */	
	public function getStatusText($status_id = 0)
	{
		return __($this->_status[$status_id ]);
	}
	/**
     * getApproveUrl
     * 
	 * return string url
     */	
	public function getApproveUrl($question_id)
	{
		return $this->getUrl("mageants_productqa/productquestionanswer/approve/id/".$question_id);
	}
	/**
     * getLoadMoreUrl
     * 
	 * return string url
     */	
	public function getRejectUrl($question_id)
	{
		return $this->getUrl("mageants_productqa/productquestionanswer/reject/id/".$question_id);
	}
	/**
     * getLoadMoreUrl
     * 
	 * return string url
     */	
	public function getLoadMoreUrl()
	{
		return $this->getUrl("mageants_productqa/productanswer/answer/question_id/".$this->getQuestionId());
	}
	/**
     * getFormUrl
     * 
	 * return string url
     */	
	public function getFormUrl()
	{
        return $this->_storeManager->getStore($this->getStoreId())->getBaseUrl()."productqa/question/answer/question_id/".$this->getQuestionId();
	}
	
	/**
     * getFormUrl
     * 
	 * return string url
     */	
	public function getApproveStatus()
	{
        return Status::STATUS_APPROVE;
	}
	
	/**
     * getProductQuestions
     * 
     * ProductAnswer of ProductQA
	 *
	 * return collection
     */	
	public function getQuestionsAnswer()
	{
		$answers  = [];
		
		$question_id = $this->getQuestionId();
		
		if($question_id)
		{
		 	$this->_answersCollection = $this->_productQuestionAnswer->create();
			
			$this->_answersCollection->addFieldToFilter('question_id',$question_id)
				->setPageSize($this->_page_size)
				->setCurPage($this->getCurrPage())
				->setOrder('created_at','DESC');
				
			$answers = $this->_answersCollection;
		}
		
		$this->_total_answer = $this->_answersCollection ->getSize();
		
		return $answers ;
	}
	
}