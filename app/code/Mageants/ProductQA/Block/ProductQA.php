<?php
 /**
 * @category  Mageants Product360Image
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Block;

use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\Registry;
use \Mageants\ProductQA\Model\ResourceModel\ProductQuestion\CollectionFactory as QuestionCollection;
use \Mageants\ProductQA\Model\ResourceModel\ProductQuestionAnswer\CollectionFactory as AnswerCollection;
use \Mageants\ProductQA\Helper\Data;
use Mageants\ProductQA\Model\Source\Status;
use \Mageants\ProductQA\Model\Source\ActionType;
use Magento\Customer\Model\Session as CustomerSession;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Catalog\Model\ProductFactory;

class ProductQA extends \Magento\Framework\View\Element\Template
{
	/**
     * @var default store all store id
     */
	const ALL_STORE = 0;
	/**
     * @var default answer per page
     */
	 const ANSWER_PAGE_SIZE = 1;
	/**
     * @var _default_curr_page
     */
	protected $_default_curr_page = 1;
	/**
     * @var _total_question
     */
	protected $_total_question;
	/**
     * @var _storeManager
     */
	protected $_storeManager;
    /**
     * @var \Mageants\ProductQA\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
	  /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $_formKey;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory = false;
    /**
     * @var \Magento\Catalog\Model\Product\Interceptor
     */
    protected $_currentProduct = false;
	 /**
     * @var Array
     */
    protected $_questionCollection = [];
    /**
     * @var \Mageants\ProductQA\Model\ResourceModel\ProductQuestion\CollectionFactory
     */
    protected $_productQuestion = false; 
	/**
     * @var \Mageants\ProductQA\Model\ResourceModel\ProductQuestionAnswer\CollectionFactory
     */
    protected $_productQuestionAnswer = false;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
		Data $helper,
		QuestionCollection $productQuestion,
		AnswerCollection $productQuestionAnswer,
		Registry $coreRegistry,
		Context $context,
		CustomerSession $_customerSession,
		ProductFactory $productFactory,
		array $data = []
	) 
	{
        $this->_helper = $helper;
        
		$this->_customerSession = $_customerSession;
		
        $this->_formKey = $context->getFormKey();
		
		$this->_coreRegistry = $coreRegistry;
		
		$this->_productFactory = $productFactory;
		
		parent::__construct($context, $data);
		
		$this->initProduct();
		
		$this->_productQuestion = $productQuestion;
		
		$this->_productQuestionAnswer = $productQuestionAnswer;
		
        
		
    }
	
	/**
     * canShowLoadMore
     *
	 * return boolean
     */
	public function initProduct()
	{
		$this->_currentProduct = $this->_coreRegistry->registry('current_product');
		
		if(!$this->_currentProduct )
		{
			$product_id = $this->getRequest()->getParam("product");
			
			$productFactory = $this->_productFactory->create();
			
			$this->_currentProduct = $productFactory->load($product_id);
		}
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
     * getCurrPage
     *
	 * return boolean
     */
	public function getCurrPage()
	{
		$page = $this->getRequest()->getParam('page');
		
		if(!$page)
		{
			$page = $this->_default_curr_page;
		}
		
		return $page;
	}
	
	/**
     * hasProductQA
     * 
     * Product360 Enable / Disable for current product
	 *
	 * return boolean
     */
	public function hasProductQAEnable()
	{
		if($this->getProductQaHelper()->isExtentionEnable() && $this->_currentProduct)
		{
			return true;
		}
		return false;
	}
	
	/**
     * getProduct
     * 
     * get current product
	 *
	 * return object
     */
	public function getProduct()
	{
		return $this->_currentProduct ;		
	}
	
	/**
     * getLoginCustomer
     * 
     * get current login customer
	 *
	 * return object
     */
	public function getLoginCustomer()
	{
		return $this->_customerSession->getCustomer();		
	}
	
	/**
     * getEmail
     * 
	 * return string email
     */	
	public function getCustomerEmail()
	{
		return $this->getLoginCustomer()->getEmail();
	}
		
	/**
     * getName
     * 
	 * return string name
     */	
	public function getCustomerName()
	{
		return $this->getLoginCustomer()->getName();
	}
	/**
     * isLogin
     * 
	 * return boolean
     */	
	public function isLogin()
	{
		if($this->_customerSession->isLoggedIn()) 
		{
				return true;
		}
		return false;
	}	
	
	/**
     * getSortUrl
     * 
	 * return array
     */	
	public function getSortOrder()
	{
		$order = $this->getRequest()->getParam("order");
		
		 switch($order)
		 {
			 case "oldest": $order_field = "created_at"; $ascdesc ="ASC" ;break;
			 case "mostlike": $order_field = "likes"; $ascdesc ="DESC" ;break;
			 case "mostdislike": $order_field = "dislikes"; $ascdesc ="DESC" ;break;
			 default : $order_field = "created_at"; $ascdesc ="DESC" ; break;
		 }
			 
		return [$order_field , $ascdesc];
	}
	/**
     * getSortUrl
     * 
	 * return string url
     */	
	public function getSortUrl($sortkey)
	{
		return $this->getLoadMoreUrl()."order/".$sortkey;
	}
	/**
     * getLikeActionUrl
     * 
	 * return string url
     */	
	public function getLikeActionUrl($question_id)
	{
		return $this->getUrl("productqa/question/action/action/".ActionType::ACTION_LIKE."/question_id/".$question_id."/");
	}
	/**
     * getDislikeActionUrl
     * 
	 * return string url
     */	
	public function getDislikeActionUrl($question_id)
	{
		return $this->getUrl("productqa/question/action/action/".ActionType::ACTION_DISLIKE."/question_id/".$question_id."/");
	}
	/**
     * getLoadMoreUrl
     * 
	 * return string url
     */	
	public function getLoadMoreUrl()
	{
		return $this->getUrl("productqa/question/index/product/".$this->getProduct()->getId()."/");
	}
	/**
     * getFormUrl
     * 
	 * return string url
     */	
	public function getFormUrl()
	{
		return $this->getUrl("productqa/question/ask/product/".$this->getProduct()->getId()."/");
	}
	/**
     * getTotalQuestion
     * 
	 * return int 
     */	
	public function getTotalQuestion()
	{
		return $this->_total_question;
	}
	/**
     * getTotalQuestion
     * 
	 * return int 
     */	
	public function getTotalPage()
	{
		return floor($this->getTotalQuestion() / $this->getProductQaHelper()->getQuestionPageSize() );
	}
	/**
     * getFormKey
     * 
	 * return string form key
     */	
	public function getFormKey()
	{
		return $this->_formKey->getFormKey();
	}
	
	/**
     * getProductQuestions
     * 
     * ProductQuestions of ProductQA
	 *
	 * return collection
     */	
	public function getProductQuestions()
	{
		if($this->_currentProduct && $this->_currentProduct->getId())
		{
			$this->_questionCollection = $this->_productQuestion->create();
			
			list($order_field , $ascdesc) = $this->getSortOrder();
			
			$this->_questionCollection 
				->addFieldToFilter('product_id',$this->_currentProduct->getId())
				->addFieldToFilter('status',Status::STATUS_APPROVE)	
				->addFieldToFilter('store_id',['in'=>[$this->getCurrentStoreId(),Self::ALL_STORE]])
				->setPageSize($this->getProductQaHelper()->getQuestionPageSize())
				->setCurPage($this->getCurrPage())
				->setOrder($order_field,$ascdesc);
			
			$q = $this->getRequest()->getParam("q");
			if($q)
			{
					$this->_questionCollection->addFieldToFilter("question",array('like' => '%'.$q.'%'));
			}
		}
		//echo "<pre>";
		//print_r(get_class_methods($this->_questionCollection));
		
		$this->_total_question = $this->_questionCollection ->getSize();
		
		return $this->_questionCollection ;
	}
	
	/**
     * Retrieve Module Data Helper
     *
     * @return int
     */
	public function getQuestionMaxCharacter()
	{
		return $this->getProductQaHelper()->getMaxQuestionLength();
	}
	/**
     * Retrieve Module Data Helper
     *
     * @return _helper
     */
	public function getProductQaHelper()
	{
		return $this->_helper;
	}
	/**
     * getProductQuestions
     * 
     * ProductAnswer of ProductQA
	 *
	 * return collection
     */	
	public function getQuestionsAnswer($question_id)
	{
		return $this->getChildBlock('answer')->setQuestionId($question_id)->setPageSize(Self::ANSWER_PAGE_SIZE)->toHtml();
	}

	/**
     * Retrieve current Store Id 
     *
     * @return store_id
     */
	public function getCurrentStoreId(){
		return $this->_storeManager->getStore()->getId();
	}
}