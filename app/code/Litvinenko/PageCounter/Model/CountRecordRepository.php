<?php
namespace Litvinenko\PageCounter\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Litvinenko\PageCounter\Model\CountRecordFactory;
use Litvinenko\PageCounter\Model\ResourceModel\CountRecord\CollectionFactory as CountRecordCollectionFactory;
use Litvinenko\PageCounter\Model\ResourceModel\CountRecord as ResourceCountRecord;
use Litvinenko\PageCounter\Api\CountRecordRepositoryInterface;
use Litvinenko\PageCounter\Api\Data\CountRecordInterface;
use Litvinenko\PageCounter\Api\Data\CountRecordSearchResultsInterfaceFactory as SearchResultsFactory;

class CountRecordRepository implements CountRecordRepositoryInterface
{
    protected $_resourceModel;

    protected $_countRecordFactory;

    protected $_countRecordCollectionFactory;

    protected $_searchResultsFactory;

    protected $_collectionProcessor;

    public function __construct(
            ResourceCountRecord          $resourceModel,
            CountRecordFactory           $countRecordFactory,
            CountRecordCollectionFactory $countRecordCollectionFactory,
            SearchResultsFactory         $searchResultsFactory,
            CollectionProcessorInterface $collectionProcessor
    ) {
        $this->_resourceModel                = $resourceModel;
        $this->_countRecordFactory           = $countRecordFactory;
        $this->_countRecordCollectionFactory = $countRecordCollectionFactory;
        $this->_searchResultsFactory         = $searchResultsFactory;
        $this->_collectionProcessor          = $collectionProcessor;
    }

    /**
     * Retrieve page counter record.
     *
     * @param int $id
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException If page counter record with the specified id does not exist.
     */
    public function getById($id)
    {
        $countRecord = $this->_countRecordFactory->create();
        $countRecord->load($id);

        if (!$countRecord->getId()) {
            throw new NoSuchEntityException(__('The Count Record with the "%1" ID doesn\'t exist.', $id));
        }

        return $countRecord;
    }

    /**
     * Retrieve pages matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_countRecordCollectionFactory->create();

        $this->_collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->_searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Save page counter record.
     *
     * @param \Litvinenko\PageCounter\Api\Data\CountRecordInterface $countRecord
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(CountRecordInterface $countRecord)
    {
        try {
            $this->_resourceModel->save($countRecord);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Count Record: %1', $exception->getMessage()),
                $exception
            );
        }
        return $countRecord;        
    }
}
