<?php
 /**
 * @category  Mageants ProductQA
 * @package   Mageants_ProductQA
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <info@mageants.com>
 */
namespace Mageants\ProductQA\Controller\Adminhtml;

use \Mageants\ProductQA\Model\ProductQuestionAnswerFactory;
use \Magento\Framework\Registry;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Backend\App\Action\Context;
		
abstract class ProductQuestionAnswer extends \Magento\Backend\App\Action
{
    /**
     * Post Factory
     * 
     * @var \Mageants\ProductQA\Model\ProductQuestionFactory
     */
    protected $_productQuestionAnswerFactory;

    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * constructor
     * 
     * @param ProductQuestionFactory $productquestionFactory
     * @param Registry $coreRegistry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        ProductQuestionAnswerFactory $productQuestionAnswerFactory,
        Registry $coreRegistry,
        Context $context
    )
    {
        $this->_productQuestionAnswerFactory           = $productQuestionAnswerFactory;
		
        $this->_coreRegistry          = $coreRegistry;
		
        $this->_resultRedirectFactory = $context->getResultRedirectFactory();
		
        parent::__construct($context);
    }

    /**
     * Init Post
     *
     * @return \Mageants\ProductQA\Model\ProductQuestion
     */
    protected function _initProductQuestionAnswer()
    {
        $answerid  = (int) $this->getRequest()->getParam('id');
		
        /** @var \Mageants\ProductQA\Model\ProductQuestion $productquestionanswer*/
        $productquestionanswer   = $this->_productQuestionAnswerFactory->create();
		
        if ($answerid) 
		{
            $productquestionanswer->load($answerid);
        }
		
        $this->_coreRegistry->register('mageants_productquestionanswer', $productquestionanswer);
		
        return $productquestionanswer;
    }
}
