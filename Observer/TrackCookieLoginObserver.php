<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class TrackEventLoginObserver
 * Creates a cookie to mark a client login event
 * The cookie is deleted in JS when the event is sent
 * Additionaly, it can be used as a base to send to the event server-side as well
 *
 * @package Boxalino\RealTimeUserExperience\Observer
 */
class TrackCookieLoginObserver implements ObserverInterface
{

    public const RTUX_API_TRACKER_EVENT = "login";
    public const RTUX_API_TRACKER_EVENT_COOKIE = "cemv-login";

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory
    ){
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer) : void
    {
        try {
            if($observer->getCustomer()->getId())
            {
                if($this->cookieManager->getCookie(self::RTUX_API_TRACKER_EVENT_COOKIE))
                {
                   return;
                }

                $metadata = $this->cookieMetadataFactory
                    ->createPublicCookieMetadata()
                    ->setPath("/")
                    ->setDuration(120);

                $this->cookieManager->setPublicCookie(
                    self::RTUX_API_TRACKER_EVENT_COOKIE,
                    true,
                    $metadata
                );
            }
        } catch (\Exception $e) {
            //do nothing
        }
    }

}
