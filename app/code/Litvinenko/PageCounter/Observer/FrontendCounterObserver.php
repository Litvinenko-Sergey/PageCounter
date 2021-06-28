<?php
namespace Litvinenko\PageCounter\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Litvinenko\PageCounter\Helper\Counter;

/**
 *  Frontend counter observer.
 */
class FrontendCounterObserver implements ObserverInterface
{
    protected $_counter;

    public function __construct(Counter $counter)
    {
        $this->_counter = $counter;
    }

    /**
     * Frontend Counter
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->_counter->isEnabled()){
            return;
        }

        $request     = $observer->getEvent()->getRequest();
        $response    = $observer->getResponse();
        $requestPath = $this->_counter->getRequestPath($request, $response);

        $this->_counter->count($requestPath);
    }
}
