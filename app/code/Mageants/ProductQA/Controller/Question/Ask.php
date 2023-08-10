<?php
namespace Mageants\ProductQA\Controller\Question;
 
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\Controller\Result\JsonFactory;
use \Mageants\ProductQA\Model\ProductQuestionFactory;
use \Mageants\ProductQA\Model\Source\UserType;
use \Mageants\ProductQA\Model\Source\Status;
use \Magento\Store\Model\StoreManagerInterface;
use \Mageants\ProductQA\Helper\Data;
use \Magento\Customer\Model\Session as CustomerSession;
use \Magento\Captcha\Observer\CaptchaStringResolver;
use \Magento\Captcha\Helper\Data as CaptchaHelper;
use \Magento\Catalog\Model\Product;
use Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Catalog\Helper\ImageFactory;
use \Mageants\ProductQA\Model\ResourceModel\ProductQuestionAnswer\Collection;
 
class Ask extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Mageants\ProductQA\Model\ResourceModel\ProductQuestionFactory
     */
    protected $_productQuestion = false;
	/**
     * @var _storeManager
     */
	protected $_storeManager;
	/**
     * @var _storeManager
     */
	CONST CAPTCHA_FORM_ID = 'qa_captcha_form_1';
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Mageants\ProductQA\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Captcha\Observer\CaptchaStringResolver
     */
    protected $_captchaStringResolver;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;
	
    /**
     * @var \Magento\Captcha\Helper\Data
     */
    protected $_captchaHelper;

    protected $answerCollection;

    protected $_productQuestionColl;
	
    protected $_resultJsonFactory = false;
	
    public function __construct(
		Data $helper,
		Context $context, 
		JsonFactory $resultJsonFactory,
		CustomerSession $_customerSession,
		ProductQuestionFactory $productQuestion,
		CaptchaStringResolver $captchaStringResolver ,
		StoreManagerInterface $storeManager,
		CaptchaHelper $captchaHelper,
		Product $product,
		TransportBuilder $transportBuilder,
		ImageFactory $imageHelperFactory,
		Collection $answerCollection,
		\Mageants\ProductQA\Model\ResourceModel\ProductQuestion\Collection $productQuestionColl
		)
    {
		$this->_helper = $helper;
		
        $this->_productQuestion = $productQuestion;
		
		$this->_customerSession = $_customerSession;
		
		$this->_resultJsonFactory = $resultJsonFactory;
		
		$this->_storeManager = $storeManager;
		
		$this->_captchaStringResolver = $captchaStringResolver;
		
		$this->_captchaHelper = $captchaHelper;
		
		$this->_messageManager = $context->getMessageManager();

		$this->_product = $product;

		$this->_transportBuilder = $transportBuilder;

		$this->_imageHelperFactory = $imageHelperFactory;

		$this->answerCollection = $answerCollection;

		$this->_productQuestionColl = $productQuestionColl;
		
        parent::__construct($context);
    }
 
    public function execute()
    {
		$formId = Self::CAPTCHA_FORM_ID;
		
	 	$captchaModel = $this->_captchaHelper->getCaptcha($formId);

	  	if ($captchaModel->isCorrect($this->_captchaStringResolver->resolve($this->getRequest(), $formId)) || !$this->_helper->isCaptchaEnable()) 
		{
			$name = $this->getRequest()->getPost("name");
			 
			$email = $this->getRequest()->getPost("email");
			 
			$question = $this->getRequest()->getPost("question");
			 
			if($question && $email && $name)
			{
			 	if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			 		
			 		$message = __('Error! Invalid email format');
					$status = 0;
				} else {	
				 $question_lenght = $this->_helper->getMaxQuestionLength();
				 
				 if(strlen($question) <= $question_lenght )
				 {
					 $product_id = $this->getRequest()->getParam("product");
					 
					 $user_id = $this->_customerSession->getCustomer()->getId();
					 
					 $ask_by = $user_id;
					 
					 $user_type = UserType::CUSTOMER;
					 
					 $store_id =  $this->_storeManager->getStore()->getId();
					 
					 $status =  Status::STATUS_PENDING;
					 
					 $questionFaktory =  $this->_productQuestion->create();
					 
					 $questionFaktory->setProductId($product_id)
						->setUserId($user_id)
						->setAskBy($ask_by)
						->setUserType($user_type)
						->setStoreId($store_id)
						->setStatus($status)
						->setName($name)
						->setEmail($email)
						->setQuestion($question)
						->save();

						$this->sendCustomerEmailNotification($questionFaktory);

						$this->sendAdminEmailNotification($questionFaktory);

						$allQuestions = $this->_productQuestionColl->addFieldToFilter('store_id', $store_id);
						$allQuestionIds=array();
						foreach ($allQuestions as $que) {
							$allQuestionIds[]= $que['id'];
						}
						 $answerCollection = $this->answerCollection->addFieldToFilter('question_id', array('in'=>$allQuestionIds));
						 $answerCollectionData = $answerCollection->getData();
						if($questionFaktory->getId())
						{
							if($answerCollectionData){
								foreach ($answerCollectionData as $ansData) {							 	
							 		$this->sendAnsweredUserEmail($questionFaktory,$ansData['email']);
							 	}
							}
							
							$message = __('Your Question submited successfull. Please wait until admin approve your question.');
							$status = 1;
						}
						else
						{
							 $message = __('Error! while saving data. Please try latter.');
							$status = 0;
						}
				}
				else
				{
					 $message = __('Question is too long. max %1 allow"',$question_lenght );
					$status = 0;
				}
				}
					
			 } 
			 else
			 {
				 $message = __('Error! All fields are required.');
				 $status = 0;
			 }
	 	}
		else
		{
			 $message = __('Invalide security code!');
			 $status = 0;
		}
		 
		 $this->messageManager->getMessages();
		 
		 $result = $this->_resultJsonFactory->create();
		 
		 $resultData = [
				'status' => $status,
				'message' => $message ,
			];

			return $result->setData($resultData);
		 
    }

    /**
     * @param QuestionInterface $questionFaktory
     */
    public function sendCustomerEmailNotification($questionFaktory)
    {
    	if(!$questionFaktory->getId()) return;
    	$enable = $this->_helper->isNotifyCustomer();
        if ($enable) {
          		
          		$to = $questionFaktory->getEmail();

          		$productFactory =  $this->_product;

				$product = $productFactory->load($questionFaktory->getProductId());

				$product_image = $this->_imageHelperFactory->create()->init($product, 'product_thumbnail_image')->getUrl();

				$data = [
					'customer_name' => $questionFaktory->getName(),
					"question" => $questionFaktory->getQuestion(),
					"product_link" => $product->getProductUrl(),
					"product_name" => $product->getName(),
					"product_image" => $product_image
				];
				
				$store_id = $questionFaktory->getStoreId();

				$emailTo = $this->_helper->sendCustomerEmailSender();

				$store = $this->_storeManager->getStore($store_id);

				$store_email = $store->getConfig('trans_email/ident_' . $emailTo . '/email');
				
				$store_name = $store->getConfig('trans_email/ident_' . $emailTo . '/name');

				$templateId = $this->_helper->customerEmailTemplate();

				if(empty($templateId)){
					$templateId = "mageants_productqa_user_email_template";
				}
				if(!empty($to))
				{
					$transport = $this->_transportBuilder
						->setTemplateIdentifier($templateId)
						->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store_id])
						->setTemplateVars($data)
						->setFrom(['name' => $store_name,'email' => $store_email])
						->addTo($to)
						->getTransport();
						
					$transport->sendMessage();
				}
        }
    }

    /**
     * @param QuestionInterface $questionFaktory
     */
    public function sendAdminEmailNotification($questionFaktory)
    {
    	if(!$questionFaktory->getId()) return;
    	$enable = $this->_helper->isNotifyAdmin();
        if ($enable) {
          		
          		$productFactory =  $this->_product;

				$product = $productFactory->load($questionFaktory->getProductId());

				$product_image = $this->_imageHelperFactory->create()->init($product, 'product_thumbnail_image')->getUrl();

				$data = [
					'customer_name' => $questionFaktory->getName(),
					'customer_email' => $questionFaktory->getEmail(),
					"question" => $questionFaktory->getQuestion(),
					"product_link" => $product->getProductUrl(),
					"product_name" => $product->getName(),
					"product_image" => $product_image
				];
				
				$store_id = $questionFaktory->getStoreId();

				$emailTo = $this->_helper->sendAdminEmailSender();

				$store = $this->_storeManager->getStore($store_id);

				$store_email = $store->getConfig('trans_email/ident_' . $emailTo . '/email');
				
				$store_name = $store->getConfig('trans_email/ident_' . $emailTo . '/name');

				$templateId = $this->_helper->adminEmailTemplate();
				if(empty($templateId)){
					$templateId = "mageants_productqa_admin_email_template";
				}
				if(!empty($store_email))
				{
					$transport = $this->_transportBuilder
						->setTemplateIdentifier($templateId)
						->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store_id])
						->setTemplateVars($data)
						->setFrom(['name' => $store_name,'email' => $store_email])
						->addTo($store_email)
						->getTransport();
						
					$transport->sendMessage();
				}
        }
    }
    /**
     * @param QuestionInterface $questionFaktory
     */
    public function sendAnsweredUserEmail($questionFaktory, $toEmail)
    {
    	$enable = $this->_helper->isNotifyAnsweredUsers();
        if ($enable) {
          		
          		$to = $toEmail;

          		$productFactory =  $this->_product;

				$product = $productFactory->load($questionFaktory->getProductId());

				$product_image = $this->_imageHelperFactory->create()->init($product, 'product_thumbnail_image')->getUrl();

				$data = [
					'customer_name' => $questionFaktory->getName(),
					'customer_email' => $questionFaktory->getEmail(),
					"question" => $questionFaktory->getQuestion(),
					"product_link" => $product->getProductUrl(),
					"product_name" => $product->getName(),
					"product_image" => $product_image
				];
				
				$store_id = $questionFaktory->getStoreId();

				$emailTo = $this->_helper->sendAnsweredUserEmailSender();

				$store = $this->_storeManager->getStore($store_id);

				$store_email = $store->getConfig('trans_email/ident_' . $emailTo . '/email');
				
				$store_name = $store->getConfig('trans_email/ident_' . $emailTo . '/name');

				$templateId = $this->_helper->answeredUserEmailTemplate();
				if(empty($templateId)){
					$templateId = "mageants_productqa_email_to_answered_users_template";
				}
				if(!empty($to))
				{
					$transport = $this->_transportBuilder
						->setTemplateIdentifier($templateId)
						->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $store_id])
						->setTemplateVars($data)
						->setFrom(['name' => $store_name,'email' => $store_email])
						->addTo($to)
						->getTransport();
						
					$transport->sendMessage();
				}
        }
    }
}