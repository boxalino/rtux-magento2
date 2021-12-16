<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Observer;

use Ramsey\Uuid\Uuid;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCookieSubscriber;
use \Magento\Framework\Stdlib\CookieManagerInterface;
use \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;

/**
 * Class ApiCookieSubscriber
 * Sets the API cookies for tracking and content personalization
 *
 * @package Boxalino\RealTimeUserExperience\Observer
 */
class SetApiCookieObserver implements ObserverInterface
{

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
     * Setting cookies for the tracker & API requests
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer) : void
    {
        if(!$this->cookieManager->getCookie(ApiCookieSubscriber::BOXALINO_API_COOKIE_SESSION))
        {
            $metadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setPath("/");

            $id =$this->_generateUuid();
            $this->cookieManager->setPublicCookie(
                ApiCookieSubscriber::BOXALINO_API_COOKIE_SESSION,
                $id,
                $metadata
            );

            // the cookie manager does not populate the $_COOKIE array, but getCookie only checks this one
            $_COOKIE[ApiCookieSubscriber::BOXALINO_API_COOKIE_SESSION] = $id;
        }

        if(!$this->cookieManager->getCookie(ApiCookieSubscriber::BOXALINO_API_COOKIE_VISITOR))
        {
            $metadata = $this->cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setPath("/")
                ->setDurationOneYear();

            $id =$this->_generateUuid();
            $this->cookieManager->setPublicCookie(
                ApiCookieSubscriber::BOXALINO_API_COOKIE_VISITOR,
                $id,
                $metadata
            );

            // the cookie manager does not populate the $_COOKIE array, but getCookie only checks this one
            $_COOKIE[ApiCookieSubscriber::BOXALINO_API_COOKIE_VISITOR] = $id;
        }
    }

    /**
     * @return string
     */
    protected function _generateUuid() : string
    {
        return Uuid::uuid4()->toString();
    }


}
