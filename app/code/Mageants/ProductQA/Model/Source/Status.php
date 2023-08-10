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
class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Status values
     */
    const STATUS_APPROVE = 1;
    const STATUS_PENDING = 0;
    const STATUS_REJECT = 2;

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
            ['value' => self::STATUS_APPROVE,  'label' => __('Approved')],
            ['value' => self::STATUS_REJECT,  'label' => __('Rejected')],
            ['value' => self::STATUS_PENDING,  'label' => __('Pending')],
        ];
    }
}
