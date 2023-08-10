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
class ActionType implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Status values
     */
    const ACTION_LIKE = 1;
    const ACTION_DISLIKE = 2;

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
            ['value' => self::ACTION_LIKE,  'label' => __('Like')],
            ['value' => self::ACTION_DISLIKE,  'label' => __('Dislike')],
        ];
    }
}
