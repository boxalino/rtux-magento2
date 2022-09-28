<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Observer;

use Boxalino\RealTimeUserExperience\Helper\Js\Configuration as RtuxApiHandler;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class TrackEventLoginObserver
 * Tracks the login event
 *
 * @package Boxalino\RealTimeUserExperience\Observer
 */
class TrackEventLoginObserver implements ObserverInterface
{

    public const RTUX_API_TRACKER_EVENT = "login";

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
            if($observer->getCustomer()->getId() && $this->rtuxApiHandler->isTrackerActive())
            {
                $this->rtuxApiHandler->track("login", ["id" => $observer->getCustomer()->getId()]);
            }
        } catch (\Throwable $e) {
            //do nothing
        }
    }

}
