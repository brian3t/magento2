<?php
/*------------------------------------------------------------------------
# SM Maxshop - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Maxshop\Model\Config\Source;

class Color implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray()
	{
		return [
			['value' => 'bue', 'label' => __('Blue')],
			['value' => 'cyan', 'label' => __('Cyan')],
			['value' => 'orange', 'label' => __('Orange')],
			['value' => 'green', 'label' => __('Green')],
			['value' => 'red', 'label' => __('Red')]
		];
	}
}