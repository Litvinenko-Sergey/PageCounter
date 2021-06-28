<?php
namespace Litvinenko\PageCounter\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Litvinenko\PageCounter\Api\Data\CountRecordInterface;

interface CountRecordRepositoryInterface
{
    /**
     * Retrieve page counter record.
     *
     * @param int $id
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If page count record with the specified id does not exist.
     */
    public function getById($id);

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Save page counter record.
     *
     * @param \Litvinenko\PageCounter\Api\Data\CountRecordInterface $countRecord
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(CountRecordInterface $countRecord);
}
