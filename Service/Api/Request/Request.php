<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Request;

use \Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use \Magento\Framework\App\RequestInterface as PlatformRequest;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Class Request
 * Adapts the framework request to a generic request interface (wrapper/processor) used within the plugin
 * Receives a request
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api\Request
 */
class Request implements RequestInterface
{

    /**
     * @var PlatformRequest
     */
    protected $request;

    /**
     * @var \Magento\Framework\HTTP\Header
     */
    protected $httpHeader;

    /**
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $locale;

    /**
     * @var CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $cookieMetadataFactory;

    /**
     * Request constructor.
     * @param \Magento\Framework\HTTP\Header $httpHeader
     * @param \Magento\Framework\Locale\ResolverInterface $store
     */
    public function __construct(
        \Magento\Framework\HTTP\Header $httpHeader,
        \Magento\Framework\Locale\ResolverInterface $locale,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory
    ){
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->locale = $locale;
        $this->httpHeader = $httpHeader;
    }

    /**
     * @param mixed $request
     * @return self
     */
    public function setRequest($request) : RequestInterface
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Magento2 request is a preference for \Magento\Framework\App\RequestInterface
     *
     * @return PlatformRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasCookie(string $key) : bool
    {
        return (bool) $this->cookieManager->getCookie($key);
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function setCookie(string $key, $value) : RequestInterface
    {
        $metadata = $this->cookieMetadataFactory
            ->createPublicCookieMetadata()
            ->setPath("/");

        $this->cookieManager->setPublicCookie($key, $value, $metadata);
        return $this;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getCookie(string $key, $default = null): string
    {
        return $this->cookieManager->getCookie($key, $default);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale->getLocale();
    }

    /**
     * @return string
     */
    public function getClientIp(): string
    {
        return $this->request->getClientIp();
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->httpHeader->getHttpUserAgent();
    }

    /**
     * @return string
     */
    public function getUserReferer(): string
    {
        return $this->httpHeader->getHttpReferer();
    }

    /**
     * @return string
     */
    public function getUserUrl(): string
    {
        return $this->httpHeader->getHttpHost().$this->httpHeader->getRequestUri();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->request->getParams();
    }

    /**
     * @param string $key
     * @param null $default
     */
    public function getParam(string $key, $default = null)
    {
        return $this->request->getParam($key, $default);
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod(string $method) : bool
    {
        if($method === self::METHOD_POST)
        {
            return $this->request->isPost();
        }

        return $this->request->isGet();
    }

}
