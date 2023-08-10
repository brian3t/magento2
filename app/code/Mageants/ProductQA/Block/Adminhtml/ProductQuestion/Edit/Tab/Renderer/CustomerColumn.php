<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Block\Adminhtml\ProductQuestion\Edit\Tab\Renderer;

use \Magento\Framework\App\ObjectManager;

/**
 * @method string getValue()
 */
class CustomerColumn extends \Magento\Framework\Data\Form\Element\Text
{
	
    /**
     * Get customer url
     *
     * @return string
     */
    public function getAfterElementHtml()
    {
		 $objectManager = ObjectManager::getInstance();
		
		$registry = $objectManager->create('Magento\Framework\Registry');
		
		$productquestion = $registry->registry('mageants_productquestion');
		/* echo "test";
		echo parent::getAfterElementHtml();
		 print_r($productquestion);exit;
		 print_r(get_class($productquestion));exit; */
		 
        return parent::getAfterElementHtml()."test123";
    }
}
