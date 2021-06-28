<?php
namespace Litvinenko\PageCounter\Plugin;

use Magento\Cms\Controller\Adminhtml\Page\Edit;
use Litvinenko\PageCounter\Helper\Counter;

/**
 * Count opened CMS pages from admin panel
 *
 */
class BackendCounter
{
    protected $_counter;

    public function __construct(Counter $counter)
    {
        $this->_counter = $counter;
    }

    public function afterExecute(Edit $subject, $resultPage) {
        if ($this->_counter->isEnabled()){

            $pageId = $subject->getRequest()->getParam('page_id');

            $this->_counter->count($pageId, false);
        }

        return $resultPage;
    }
}
