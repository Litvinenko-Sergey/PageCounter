<?php
namespace Litvinenko\PageCounter\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Api\GetPageByIdentifierInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Cms\Helper\Page;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Litvinenko\PageCounter\Api\CountRecordRepositoryInterface;
use Litvinenko\PageCounter\Model\CountRecordFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Counter extends AbstractHelper {

    const IS_ENABLED_CONFIG_PATH = 'page_counter/general/enabled';

    protected $_isUrlChecked = false;

    protected $_getPageById;

    protected $_pageRepository;

    protected $_storeManager;

    protected $_scopeConfig;

    protected $_countRecordRepository;

    protected $_countRecordFactory;

    protected $_searchCriteriaBuilder;

    public function __construct(
            Context                        $context,
            PageRepositoryInterface        $pageRepository,
            GetPageByIdentifierInterface   $getPageById,
            StoreManagerInterface          $storeManager,
            ScopeConfigInterface           $scopeConfig,
            CountRecordRepositoryInterface $countRecordRepository,
            CountRecordFactory             $countRecordFactory,
            SearchCriteriaBuilder          $searchCriteriaBuilder
    ) {
        $this->_pageRepository        = $pageRepository;
        $this->_getPageById           = $getPageById;
        $this->_storeManager          = $storeManager;
        $this->_scopeConfig           = $scopeConfig;
        $this->_countRecordRepository = $countRecordRepository;
        $this->_countRecordFactory    = $countRecordFactory;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;

        parent::__construct($context);
    }

    /**
     * Main count function.
     *
     * @param int|string $pageId - can be CMS Page's URL or id
     * @param bool $isFrontend - from frontend flag
     * @return $this
     */
    public function count($pageId, $isFrontend = true)
    {
        $this->_isUrlChecked = true;

        $cmsPage = $this->_loadCmsPage($pageId);

        if ($cmsPage) {
            $cmsPageId = $cmsPage->getId();
            $this->_incrementCounter($cmsPageId, $isFrontend);
        }

        return $this;
    }

    /**
     * Try to load CountRecord by pageId or create new record
     * if record with searched pageId doesn't found
     * and increment view count.
     *
     * @param int $cmsPageId - only number of CMS page id
     * @param bool $isFrontend - frontend flag
     * @return $this
     */
    protected function _incrementCounter(int $cmsPageId, $isFrontend)
    {
        $countRecord = $this->_getCountRecordByCmsPageId($cmsPageId);

        if ($isFrontend) {
            $count = $countRecord->getFrontendCounter();
            $countRecord->setFrontendCounter(++$count);
        } else {
            $count = $countRecord->getBackendCounter();
            $countRecord->setBackendCounter(++$count);
        }

        $this->_countRecordRepository->save($countRecord);

        return $this;
    }

    /**
     * Load CMS page by Id.
     *
     * @param sting|int $pageId - can be CMS Page's URL or id
     * @return \Magento\Cms\Api\Data\PageInterface
     */
    protected function _loadCmsPage($pageId)
    {
        $pageId = trim($pageId, '/');

        try {
            if (is_numeric($pageId)) {
                $page = $this->_pageRepository->getById($pageId);
            } else {
                //for multistore cases too
                $storeId = $this->_storeManager->getStore()->getId();
                $page = $this->_getPageById->execute($pageId, $storeId);
            }
        } catch (NoSuchEntityException $e){
            $page = null;
        }

        return $page;
    }

    /**
     * Try to load CountRecord by pageId or create new record
     * if record with searched pageId doesn't found.
     *
     * @param int $cmsPageId
     * @return \Litvinenko\PageCounter\Api\Data\CountRecordInterface
     */
    protected function _getCountRecordByCmsPageId($cmsPageId)
    {
        $searchCriteria = $this->_searchCriteriaBuilder
                ->addFilter('cms_page_id', $cmsPageId)
                ->create()
                ;
        $cmsPages = $this->_countRecordRepository
                ->getList($searchCriteria)
                ->getItems()
                ;

        $countRecord = reset($cmsPages);
        //if count record doesn't find, create new record
        if (!$countRecord) {
            $countRecord = $this->_countRecordFactory->create();
            $countRecord->setCmsPageId($cmsPageId);
        }
        return $countRecord;
    }

    /**
     * Return CMS page id (url) by using request and response
     *
     * @param obj $request
     * @param obj $response
     * @return string
     */
    public function getRequestPath($request, $response)
    {
        //For plain CMS pages with plain URL.
        //And for cached pages and for no cached
        $requestPath = $request->getOriginalPathInfo();

        //cases for special CMS pages + no cached
        switch ($request->getFullActionName()) {
            case ('cms_index_index'):
                //case: home page + no cached
                $requestPath = $this->getHomePageIdFromConfig();
                break;
            case ('cms_noroute_index'):
                //case: no route + no cached
                $requestPath = $this->get404PageIdFromConfig();
                break;
            default:
                break;
        }

        //cases for special CMS pages + cached
        switch ($response->getHttpResponseCode()) {
            case 200:
                //home page with param URL + cached
                if ($requestPath == '/' || $requestPath === ""){
                    $requestPath = $this->getHomePageIdFromConfig();
                }
                break;
            case 404:
                //404 page + cached
                $requestPath = $this->get404PageIdFromConfig();
                break;
            default:
                break;
        }

        return $requestPath;
    }

    /**
     * Return home page id from config
     *
     * @return string
     */
    public function getHomePageIdFromConfig()
    {
        return $this->getConfigValue(Page::XML_PATH_HOME_PAGE);
    }

    /**
     * Return 404 page id from config
     *
     * @return string
     */
    public function get404PageIdFromConfig()
    {
        return $this->getConfigValue(Page::XML_PATH_NO_ROUTE_PAGE);
    }

    /**
     * Return is current module enabled
     *
     * @return int
     */
    public function isEnabled()
    {
        return $this->getConfigValue(self::IS_ENABLED_CONFIG_PATH);
    }

    /**
     * Return config value by path
     *
     * @param string $path - config path
     * @return string
     */
    public function getConfigValue($path)
    {
        return $this->_scopeConfig->
                getValue($path, ScopeInterface::SCOPE_STORE)
                ;
    }
}
