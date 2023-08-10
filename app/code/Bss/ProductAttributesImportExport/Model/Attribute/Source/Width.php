<?php
namespace Bss\ProductAttributesImportExport\Model\Attribute\Source;

class Width extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        $optionArray = [];
        for ($i = 1; $i <= 2000; $i++) {
            $optionArray[] = ['label' => "$i", 'value' => $i];
        }
        if (!$this->_options) {
            $this->_options = $optionArray;
        }
        return $this->_options;
    }
}