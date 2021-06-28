<?php
namespace Litvinenko\PageCounter\Model\ResourceModel;

/**
 * CountRecord Resource Model
 *
 */
class CountRecord extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('customy_cms_page_counter','id');
    }
}
