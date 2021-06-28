<?php
namespace Litvinenko\PageCounter\Model;

use Magento\Framework\Model\AbstractModel;
use Litvinenko\PageCounter\Api\Data\CountRecordInterface;

/**
 * CountRecord Model
 *
 */
class CountRecord extends AbstractModel implements CountRecordInterface
{

    protected function _construct()
    {
        $this->_init('Litvinenko\PageCounter\Model\ResourceModel\CountRecord');
    }

    /**
     * Get Id
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->getData(self::PAGE_COUNTER_ID);
    }

    /**
     * Get CMS Page Id
     *
     * @return int|null
     */
    public function getCmsPageId()
    {
        return $this->getData(self::CMS_PAGE_COUNTER_ID);
    }

    /**
     * Get Backend counter value
     *
     * @return int|null
     */
    public function getBackendCounter()
    {
        return $this->getData(self::BACKEND_COUNTER);
    }

    /**
     * Get Frontend counter value
     *
     * @return int|null
     */
    public function getFrontendCounter()
    {
        return $this->getData(self::FRONTEND_COUNTER);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setId($id)
    {
        return $this->setData(self::PAGE_COUNTER_ID, $id);
    }

    /**
     * Set CMS Page Id
     *
     * @param int $id
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setCmsPageId($id)
    {
        return $this->setData(self::CMS_PAGE_COUNTER_ID, $id);
    }

    /**
     * Set Backend counter value
     *
     * @param int $value
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setBackendCounter($value)
    {
        return $this->setData(self::BACKEND_COUNTER, $value);
    }

    /**
     * Set Frontend counter value
     *
     * @param int $value
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setFrontendCounter($value)
    {
        return $this->setData(self::FRONTEND_COUNTER, $value);
    }
}
