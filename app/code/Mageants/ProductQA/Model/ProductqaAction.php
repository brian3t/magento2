<?php
 /**
 * @category  Mageants Product Question Answser
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
 
namespace Mageants\ProductQA\Model;

class ProductqaAction extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
		
		$this->_init('Mageants\ProductQA\Model\ResourceModel\ProductqaAction');
    }
	
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
