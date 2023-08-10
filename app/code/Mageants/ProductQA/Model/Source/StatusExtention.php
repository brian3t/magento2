<?php
 /**
 * @category  Mageants Product Question Answser
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */

namespace Mageants\ProductQA\Model\Source;

/**
 * Class Status
 * @package Mageants\ProductQA\Model\Source
 */
class StatusExtention implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Status values
     */
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    /**
     * @return array
     */
    public function getOptionArray()
    {
        $optionArray = ['' => ' '];
        
		foreach ($this->toOptionArray() as $option) 
		{
            $optionArray[$option['value']] = $option['label'];
        }
		
        return $optionArray;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::STATUS_ENABLE,  'label' => __('Enable')],
            ['value' => self::STATUS_DISABLE,  'label' => __('Disable')],
        ];
    }
}
