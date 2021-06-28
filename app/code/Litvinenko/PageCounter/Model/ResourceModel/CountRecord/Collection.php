<?php
namespace Litvinenko\PageCounter\Model\ResourceModel\CountRecord;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Litvinenko\PageCounter\Model\CountRecord','Litvinenko\PageCounter\Model\ResourceModel\CountRecord');
    }
}
