<?php 
namespace Mageants\ProductQA\Controller\Adminhtml\ProductQuestionAnswer;
 
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\LayoutFactory;
use \Mageants\ProductQA\Model\ProductQuestionAnswerFactory;;
 
class Answer extends \Magento\Backend\App\Action
{
    /**
     * @var \Mageants\ProductQA\Model\ResourceModel\ProductQuestionFactory
     */
    protected $_productQAnswer = false;
	/**
     * @var _storeManager
     */
	protected $_storeManager;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
	
    protected $_resultJsonFactory = false;
	
    public function __construct(
		Context $context, 
		LayoutFactory $resultLayoutFactory,
		ProductQuestionAnswerFactory $productQAnswer
		)
    {
        $this->_productQAnswer = $productQAnswer;
		
		$this->_resultLayoutFactory = $resultLayoutFactory;
		 
        parent::__construct($context);
    }
 
    public function execute()
    {
		return $this->_resultLayoutFactory->create();
    }
}