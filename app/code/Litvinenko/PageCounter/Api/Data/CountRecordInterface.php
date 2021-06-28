<?php
namespace Litvinenko\PageCounter\Api\Data;

interface CountRecordInterface 
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const PAGE_COUNTER_ID       = 'id';
    const CMS_PAGE_COUNTER_ID   = 'cms_page_id';
    const BACKEND_COUNTER       = 'backend_counter';
    const FRONTEND_COUNTER      = 'frontend_counter';
    
    /**
     * Get Id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get CMS Page Id
     *
     * @return int|null
     */
    public function getCmsPageId();

    /**
     * Get Backend counter value
     *
     * @return int|null
     */
    public function getBackendCounter();

    /**
     * Get Frontend counter value
     *
     * @return int|null
     */
    public function getFrontendCounter();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setId($id);

    /**
     * Set CMS Page Id
     *
     * @param int $id
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setCmsPageId($id);

    /**
     * Set Backend counter value
     *
     * @param int $value
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setBackendCounter($value);

    /**
     * Set Frontend counter value
     *
     * @param int $value
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    public function setFrontendCounter($value);
}
