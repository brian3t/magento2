<?php 
 /**
 * @category  Mageants Product Question Answser
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Helper; 

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\App\Helper\Context;
use Magento\Email\Model\Template as EmailTemplate;
use \Magento\Store\Model\ScopeInterface;
		
class Data extends \Magento\Framework\App\Helper\AbstractHelper 
{
	/** 
	* @var \Magento\Framework\App\Config\ScopeConfigInterfac 
	*/ 
	protected $_scopeConfig;
	/** 
	* @var array
	*/ 
	protected $_productquestionsConfig; 
	
	/** 
	* @var \Magento\Backend\Helper\Data
	*/ 
	protected $_helperBackend; 
	/** 
	* @var \Magento\Backend\Model\UrlInterface
	*/ 
	protected $_url; 

	/*Extention Enable Disable Constant*/
	CONST ENABLE = 'mageants_productqa/general/enable'; 
	CONST CAPTCHA = 'mageants_productqa/general/captcha'; 
	CONST QPAGE_SIZE = 'mageants_productqa/general/qpaze_size'; 
	CONST ANSPAGE_SIZE = 'mageants_productqa/general/anspaze_size'; 
	CONST MAX_QUESTION_LENGHT = 'mageants_productqa/general/max_question_lenght'; 
	CONST CONF_SEND_EMAIL = 'mageants_productqa/general/sendemail'; 
	CONST CONF_SEND_EMAIL_TEMPLATE = 'mageants_productqa/general/email_template'; 
	CONST CONF_SEND_EMAIL_TEMPLATE_CSS = 'mageants_productqa/general/email_template_css'; 
	CONST CONF_SEND_EMAIL_SUBJECT = 'mageants_productqa/general/email_subject'; 

	CONST CUSTOMEREMAILENABLE = 'mageants_productqa/user_email/user_notify'; 
	CONST CUSTOMEREMAILSENDER = 'mageants_productqa/user_email/sender'; 
	CONST CUSTOMEREMAILTEMPLATE = 'mageants_productqa/user_email/template';

	CONST ADMINMAILENABLE = 'mageants_productqa/admin_email/enable_notify'; 
	CONST ADMINEMAILSENDER = 'mageants_productqa/admin_email/send_to'; 
	CONST ADMINEMAILTEMPLATE = 'mageants_productqa/admin_email/template';

	CONST ADMINMAILENABLE_ANSWER = 'mageants_productqa/admin_email/enable_notify_foranswer'; 
	CONST ADMINEMAILSENDER_ANSWER = 'mageants_productqa/admin_email/send_to_foranswer'; 
	CONST ADMINEMAILTEMPLATE_ANSWER = 'mageants_productqa/admin_email/template_foranswer';

	CONST USELIKEDISLIKESYMBOL = 'mageants_productqa/general/use_like_symbol';

	CONST ANSWERED_USERS_EMAIL_ENABLE = 'mageants_productqa/email_to_answered_users/enable_email_to_answered_users';
	CONST ANSWERED_USERS_EMAIL_SENDER = 'mageants_productqa/email_to_answered_users/sender'; 
	CONST ANSWERED_USERS_EMAIL_TEMPLATE = 'mageants_productqa/email_to_answered_users/template';
	
	 /**
	 *	construct
	 *
     * @param Context $context,
	 * @param ScopeConfigInterface $scopeConfig 
	 * @param Data $HelperBackend
     */
	public function __construct( 
		Context $context, 
		\Magento\Backend\Helper\Data $HelperBackend
	) 
	{
		
        $this->_url = $context->getUrlBuilder();

		$this->_scopeConfig = $context->getScopeConfig();
		
		$this->_helperBackend = $HelperBackend;		
    }
	
	/**
     * Retrieve editor variable url
     *
     * @return string
     */
    public function getEditorVariableUrl()
	{
		return $this->_url->getUrl('mageants_productqa/variable/template');
    }
	/**
     * Retrieve question per page
     *
     * @return boolean
     */
    public function getQuestionPageSize()
	{
		return $this->_scopeConfig->getValue(Self::QPAGE_SIZE,ScopeInterface::SCOPE_STORE);
    }
	/**
     * Retrieve answer per page
     *
     * @return boolean
     */
    public function getAnswerPageSize()
	{
		return $this->_scopeConfig->getValue( Self::ANSPAGE_SIZE,ScopeInterface::SCOPE_STORE);
    }
	
	/**
     * Retrieve max character allow in question 
     *
     * @return int
     */
    public function getMaxQuestionLength()
	{
		return $this->_scopeConfig->getValue( Self::MAX_QUESTION_LENGHT,ScopeInterface::SCOPE_STORE);
    }
	
	/**
     * Retrieve extention enable or disable
     *
     * @return boolean
     */
    public function isExtentionEnable()
	{
		return $this->_scopeConfig->getValue( Self::ENABLE,ScopeInterface::SCOPE_STORE);
    }
    
	/**
     * Retrieve captcha enable or disable
     *
     * @return boolean
     */
    public function isCaptchaEnable()
	{
		return $this->_scopeConfig->getValue( Self::CAPTCHA,ScopeInterface::SCOPE_STORE);
    }
	
	
	/**
     * Retrieve is email send enable or not
     *
     * @return boolean
     */
    public function isEmailEnable($store = null)
	{
		if($store)
		{
			$data =  $store->getConfig(Self::CONF_SEND_EMAIL);
		}
		else{
			 $data = $this->_scopeConfig->getValue( Self::CONF_SEND_EMAIL,ScopeInterface::SCOPE_STORE);
		}
		return $data;
    }
	/**
     * Retrieve email subject
     *
     * @return string
     */
    public function getEmailSubject($store = null)
	{
		if($store)
		{
			$data =  $store->getConfig( Self::CONF_SEND_EMAIL_SUBJECT);
		}
		else{
			 $data = $this->_scopeConfig->getValue( Self::CONF_SEND_EMAIL_SUBJECT,ScopeInterface::SCOPE_STORE);
		}
		return $data;
    }
	/**
     * Retrieve email html template
     *
     * @return string
     */
    public function getEmailTemplate($store = null)
	{
		if($store)
		{
			$data =  $store->getConfig( Self::CONF_SEND_EMAIL_TEMPLATE);
		}
		else{
			 $data = $this->_scopeConfig->getValue( Self::CONF_SEND_EMAIL_TEMPLATE,ScopeInterface::SCOPE_STORE);
		}
		return $data;
    }
	
	/**
     * Retrieve email template css
     *
     * @return string
     */
    public function getEmailTemplateCss($store = null)
	{
		$data='';
		if($store)
		{
			$data =  $store->getConfig( Self::CONF_SEND_EMAIL_TEMPLATE_CSS);
		}
		return $data;
    }
	
	/**
     * Retrieve serialize setting
     *
     * @return array
     */
    public function serializeSetting($data)
	{
		 return serialize($data);
    }
		
	/**
     * Retrieve unserialize setting
     *
     * @return array
     */
    public function unserializeSetting($string)
	{
		$data = [];
		
		if(!empty($string))
		{
			$data = unserialize($string);
		}
		
		return $data;
    }

    /**
     * Retrieve customer email enable or disable
     *
     * @return boolean
     */
    public function isNotifyCustomer()
	{
		return $this->_scopeConfig->getValue( Self::CUSTOMEREMAILENABLE,ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve customer email sender enable or disable
     *
     * @return boolean
     */
    public function sendCustomerEmailSender()
	{
		return $this->_scopeConfig->getValue( Self::CUSTOMEREMAILSENDER,ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve customer email template
     *
     * @return boolean
     */
    public function customerEmailTemplate()
	{
		return $this->_scopeConfig->getValue( Self::CUSTOMEREMAILTEMPLATE,ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve admin email enable or disable
     *
     * @return boolean
     */
    public function isNotifyAdmin()
	{
		return $this->_scopeConfig->getValue( Self::ADMINMAILENABLE,ScopeInterface::SCOPE_STORE);
    }
     /**
     * Retrieve admin email enable or disable
     *
     * @return boolean
     */
    public function isNotifyAnsweredUsers()
	{
		return $this->_scopeConfig->getValue( Self::ANSWERED_USERS_EMAIL_ENABLE,ScopeInterface::SCOPE_STORE);
    }
     /**
     * Retrieve customer email sender enable or disable
     *
     * @return boolean
     */
    public function sendAnsweredUserEmailSender()
	{
		return $this->_scopeConfig->getValue( Self::ANSWERED_USERS_EMAIL_SENDER,ScopeInterface::SCOPE_STORE);
    }
    public function answeredUserEmailTemplate()
	{
		return $this->_scopeConfig->getValue( Self::ANSWERED_USERS_EMAIL_TEMPLATE,ScopeInterface::SCOPE_STORE);
    }
    /**
     * Retrieve admin email enable or disable
     *
     * @return boolean
     */
    public function isNotifyAdminForAnswer()
	{
		return $this->_scopeConfig->getValue( Self::ADMINMAILENABLE_ANSWER,ScopeInterface::SCOPE_STORE);
    }

    /**
     * Retrieve customer email sender enable or disable
     *
     * @return boolean
     */
    public function sendAdminEmailSender()
	{
		return $this->_scopeConfig->getValue( Self::ADMINEMAILSENDER,ScopeInterface::SCOPE_STORE);
    }
    /**
     * Retrieve customer email sender enable or disable
     *
     * @return boolean
     */
    public function sendAdminEmailSenderForAnswer()
	{
		return $this->_scopeConfig->getValue( Self::ADMINEMAILSENDER_ANSWER,ScopeInterface::SCOPE_STORE);
    }
    /**
     * Retrieve admin email template
     *
     * @return boolean
     */
    public function adminEmailTemplate()
	{
		return $this->_scopeConfig->getValue( Self::ADMINEMAILTEMPLATE,ScopeInterface::SCOPE_STORE);
    }
    /**
     * Retrieve admin email template
     *
     * @return boolean
     */
    public function adminEmailTemplateForAnswer()
	{
		return $this->_scopeConfig->getValue( Self::ADMINEMAILTEMPLATE_ANSWER,ScopeInterface::SCOPE_STORE);
    }
    /**
     * Retrieve customer email template
     *
     * @return boolean
     */
    public function useLikeDislikeSymbol()
	{
		return $this->_scopeConfig->getValue( Self::USELIKEDISLIKESYMBOL,ScopeInterface::SCOPE_STORE);
    }


}