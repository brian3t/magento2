<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Block\Adminhtml\System\Config;

use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\Form\Element\AbstractElement;
use \Mageants\ProductQA\Helper\Data;
use \Magento\Framework\Module\Dir\Reader;
  
class Editor extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var  Registry
     */
    protected $_coreRegistry;
    /**
     * @var  \Mageants\ProductQA\Helper\Data
     */
    protected $_productQaHelper;
    /**
     * @var \Magento\Framework\Module\Dir\Reader
     */
    protected $_moduleDirReader;
  
    /**
     * @param Context       $context
     * @param WysiwygConfig $wysiwygConfig
     * @param array         $data
     */
    public function __construct(
        Context $context,
        WysiwygConfig $wysiwygConfig,
		Data $productQaHelper,
		Reader $moduleDirReader,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
		
        $this->_productQaHelper = $productQaHelper;
		
        $this->_moduleDirReader = $moduleDirReader;
		
        parent::__construct($context, $data);
    }
  
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        // set wysiwyg for element
        $element->setWysiwyg(true);
        // set configuration values 
		$config = $this->_wysiwygConfig->getConfig($element);
		$plugins_conf = $config->getData() ;
		
		$plugins_conf['add_variables'] = false; 
		$plugins_conf['add_widgets'] = false;
		$plugins_conf['add_images'] = true;
		
		/* foreach($plugins_conf['plugins'] as $key=>&$plg)
		{
			if($plg['name'] == "magentovariable")
			{
				$plg['options']['url'] =$this->_productQaHelper->getEditorVariableUrl();
				$plg['options']['onclick']['subject'] = "MagentovariablePlugin.loadChooser('".$this->_productQaHelper->getEditorVariableUrl()."', '{{html_id}}');";
			}
		} */
		
		$config->setData($plugins_conf);
		
        $element->setConfig($config);
		
        if(!$element->getValue())
		{
			$filePath = $this->_moduleDirReader->getModuleDir('', "Mageants_ProductQA")."/view/frontend/email/productqa_email_template.html";
			$value = file_get_contents($filePath);
			$element->setValue($value);
		}
		
        return parent::_getElementHtml($element);
    }
 }