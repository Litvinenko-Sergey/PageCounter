<?php
namespace Litvinenko\PageCounter\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Count Record search results.
 */
interface CountRecordSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get count record list.
     *
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface[]
     */
    public function getItems();

    /**
     * Set count record list.
     *
     * @param \Litvinenko\PageCounter\Api\Data\CountRecordInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
