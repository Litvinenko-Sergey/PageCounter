<?php
namespace Litvinenko\PageCounter\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Litvinenko_PageCounter::additional_page_counter';

    const MENU_ID        = 'Litvinenko_PageCounter::additional_page_counter';

    protected $_pageFactory;

    public function __construct(Context $context, PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;

        return parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $resultPage->setActiveMenu(static::MENU_ID);
        $resultPage->getConfig()->getTitle()->prepend(__('CMS Page Counter'));

        return $resultPage;
    }
}
