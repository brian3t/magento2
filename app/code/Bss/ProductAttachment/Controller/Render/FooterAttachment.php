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
namespace Bss\ProductAttachment\Controller\Render;
 
class FooterAttachment extends \Magento\Framework\App\Action\Action
{
    /**
     * File Factory
     *
     * @var \Bss\ProductAttachment\Model\FileFactory
     */
    protected $_fileFactory;

    /**
     * Helper
     *
     * @var \Bss\ProductAttachment\Helper\Data
     */
    protected $_helper;

    /**
     * Repository
     *
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepo;

    /**
     * Result Raw Factory
     *
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * Layout Factory
     *
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Bss\ProductAttachment\Model\FileFactory $fileFactory
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Bss\ProductAttachment\Helper\Data $helper
     * @internal param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Backend\App\Action\Context $context,
        \Bss\ProductAttachment\Model\FileFactory $fileFactory,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Bss\ProductAttachment\Helper\Data $helper

    ) {
        $this->resultRawFactory = $resultRawFactory; 
        $this->layoutFactory = $layoutFactory;
        $this->_fileFactory = $fileFactory;
        $this->_assetRepo = $assetRepo;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            $customerGroupId = $this->getRequest()->getPost('customer_groupId');
            $storeId = $this->getRequest()->getPost('store_id');

            /** Attachments Array */
            $attachments = [];

            $attachmentFactory = $this->_fileFactory->create();
            $collection = $attachmentFactory->getCollection();

            foreach ($collection as $item) {
                if ($item->getShowFooter()) {
                    $attachments[] = $item->getData();
                }
            }

            $attachments = $this->_helper->sortAttachment($attachments);

            /** @var \Magento\Framework\View\Layout $layout */
            $layout = $this->layoutFactory->create();

            $block = $layout->createBlock(\Bss\ProductAttachment\Block\Attachment\Ajax::class);
            $block->setAttachments($attachments);
            $block->setStoreId($storeId);
            $block->setCustomerGroupId($customerGroupId);

            $block->setTemplate('Bss_ProductAttachment::ajax/footer.phtml');

            /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            $resultRaw->setContents($block->toHtml());
            return $resultRaw;

        } else {
            $this->_redirect('no-route');
            return;
        }
    }

}
