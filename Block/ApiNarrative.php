<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Block\ApiNarrative\CmsContext;
use Boxalino\RealTimeUserExperience\Model\Request\ApiPageLoader;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Definition\ListingRequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class Test
 * testing requests to Boxalino API
 *
 * @package Boxalino\RealTimeUserExperience\Block
 */
class ApiNarrative extends \Magento\Framework\View\Element\Template
{

    /**
     * @var ApiPageLoader
     */
    protected $apiLoader;

    /**
     * @var CmsContext
     */
    protected $apiContext;

    /**
     * @var RequestInterface
     */
    protected $requestWrapper;

    public function __construct(
        ApiPageLoader $apiPageLoader,
        CmsContext $apiContext,
        RequestInterface $requestWrapper,
        Template\Context $context,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->requestWrapper = $requestWrapper;
        $this->apiLoader = $apiPageLoader;
        $this->apiContext = $apiContext;
    }

    protected function _prepareLayout()
    {
        $this->_logger->info("in _prepareLayout");

        parent::_prepareLayout();

        $this->apiContext->setWidget($this->getData("widget"))
            ->setHitCount($this->getData("hitCount"))
            ->setGroupBy($this->getData("groupBy"));

        $this->apiLoader->setRequest($this->requestWrapper->setRequest($this->_request))
            ->setApiContext($this->apiContext);
        try{
            $this->apiLoader->load();
        } catch (\Throwable $exception)
        {
            $this->_logger->warning($exception->getMessage());
            $this->_logger->warning("BoxalinoAPIError: " . $exception->getTraceAsString());
        }
    }

    public function _getData($key)
    {
        $this->_logger->info("in _getData " . $key);
        return parent::_getData($key);
    }

    public function _beforeToHtml()
    {
        $this->_logger->info("in _beforeToHtml");
        return parent::_beforeToHtml();
    }

    public function _afterToHtml($html)
    {
        $this->_logger->info("in _afterToHtml");
        return parent::_afterToHtml($html);
    }

    /**
     * @return string
     */
    public function getTemplate() : string
    {
        return "Boxalino_RealTimeUserExperience::api/narrative.phtml";
    }

    public function getBlocks()
    {
        $this->_logger->info("IN GET BLOCKS");
        return $this->apiLoader->getApiResponsePage()->getBlocks();
    }
}
