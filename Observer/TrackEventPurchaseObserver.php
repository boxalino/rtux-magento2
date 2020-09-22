<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Boxalino\RealTimeUserExperience\Helper\Js\Configuration as RtuxApiHandler;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

/**
 * Class TrackEventPurchaseObserver
 * Sends the purchase event to track server
 * This event is tracked server-side as a sample on how the server-side tracker works
 *
 * @package Boxalino\RealTimeUserExperience\Observer
 */
class TrackEventPurchaseObserver implements ObserverInterface
{

    public const RTUX_API_TRACKER_EVENT = "purchase";

    /**
     * @var RtuxApiHandler
     */
    protected $rtuxApiHandler;

    public function __construct(RtuxApiHandler $configuration)
    {
        $this->rtuxApiHandler = $configuration;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer) : void
    {
        try {
            if($this->rtuxApiHandler->isTrackerActive())
            {
                $this->rtuxApiHandler->track(
                    self::RTUX_API_TRACKER_EVENT,
                    $this->getEventParameters($observer->getOrder())
                );
            }
        } catch (\Exception $e) {
            //do nothing
        }
    }

    /**
     * @param \Magento\Framework\Model\AbstractExtensibleModel|\Magento\Sales\Api\Data\OrderInterface|object|null $order
     * @return array
     */
    protected function getEventParameters(OrderInterface $order) : array
    {
        $productsCount = 0;
        $eventParameters = [
            't'  => round($order->getGrandTotal(), 2),
            'c'  => $order->getBaseCurrencyCode(),
            'orderId' => $order->getEntityId()
        ];
        /**
         * @var OrderItemInterface $item
         */
        foreach($order->getItems() as $item)
        {
            /** only set the products instead  */
            if($item->getPrice() > 0)
            {
                $eventParameters['id' . $productsCount] = $item->getProductId();
                $eventParameters['q' . $productsCount] = $item->getQtyOrdered();
                $eventParameters['p' . $productsCount] = $item->getPrice();
                $productsCount++;
            }
        }

        /** the count is added later because the vouchers are set as order line items as well */
        $eventParameters['n'] = $productsCount;

        return $eventParameters;
    }

}
