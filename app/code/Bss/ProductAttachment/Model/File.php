<?php 
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_ProductAttachment
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\ProductAttachment\Model;

class File extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     * 
     * @var string
     */
    const CACHE_TAG = 'bss_productattachment_file';

    /**
     * Cache tag
     * 
     * @var string
     */
    protected $_cacheTag = 'bss_productattachment_file';

    /**
     * Event prefix
     * 
     * @var string
     */
    protected $_eventPrefix = 'bss_productattachment_file';

    /**
     * Product Colection
     * 
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollection;

    /**
     * Request
     * 
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * Constructor
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        $data = []
    ) {
        $this->_request = $request;
        $this->_productCollection = $collectionFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Bss\ProductAttachment\Model\ResourceModel\File');
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get entity default values
     *
     * @return array
     */
    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    /**
     * Get Product by Attachment Id
     *
     * @param \Bss\ProductAttachment\Model\File $attachment
     * @return array
     */
    public function getProducts($attachment)
    {

        $storeView = $this->_request->getParam('store');
        $storeView = isset($storeView)? $storeView : 0;
        $productSelected = [];

        $productCollection = $this->_productCollection->create()->setStoreId($storeView);

        $collection = $productCollection->addAttributeToSelect('*')
        ->addAttributeToFilter(
            'bss_productattachment',
            ['finset' => [$attachment->getId()]]
        )
        ->load();

        foreach ($collection as $product) {
            array_push($productSelected, $product->getId());
        }
        return $productSelected;
    }
}
