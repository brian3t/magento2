<?php
/*------------------------------------------------------------------------
# SM Maxshop - Version 1.0.0
# Copyright (c) 2016 YouTech Company. All Rights Reserved.
# @license - Copyrighted Commercial Software
# Author: YouTech Company
# Websites: http://www.magentech.com
-------------------------------------------------------------------------*/

namespace Sm\Maxshop\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

	public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }
	
	public function getGeneral($name)
	{
        return $this->scopeConfig->getValue(
            'maxshop/general/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
	public function getThemeLayout($name)
	{
        return $this->scopeConfig->getValue(
            'maxshop/theme_layout/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
	public function getProductListing($name)
	{
        return $this->scopeConfig->getValue(
            'maxshop/product_listing/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}	
	
	public function getProductDetail($name)
	{
        return $this->scopeConfig->getValue(
            'maxshop/product_detail/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}

	public function getSocial($name)
	{
        return $this->scopeConfig->getValue(
            'maxshop/socials/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
	public function getAdvanced($name)
	{
        return $this->scopeConfig->getValue(
            'maxshop/advanced/'.$name,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );		
	}
	
}