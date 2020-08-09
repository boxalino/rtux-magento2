<?php
namespace Boxalino\RealTimeUserExperience\Block\Layout;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Api\ApiResponseBlockInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseRegistryInterface;
use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Boxalino\RealTimeUserExperience\Model\Request\ApiPageLoader;
use Magento\Framework\View\Element\Template;

/**
 * Class Top
 * The API request is not done in this block logic but it is part of the "main" page component
 * It will render the narrative Layout Blocks with the property "position":"top"
 *
 * @package Boxalino\RealTimeUserExperience\Block\Layout
 */
class Top extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface
{

    use ApiBlockTrait;

    /**
     * @var CurrentApiResponseRegistryInterface
     */
    protected $currentApiResponse;

    /**
     * @var CurrentApiResponseViewRegistryInterface
     */
    protected $currentApiResponseView;

    public function __construct(
        CurrentApiResponseRegistryInterface $currentApiResponse,
        CurrentApiResponseViewRegistryInterface $currentApiResponseView,
        ApiPageLoader $apiPageLoader,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->currentApiResponse = $currentApiResponse;
        $this->currentApiResponseView = $currentApiResponseView;
        $this->apiLoader = $apiPageLoader;
    }

    /**
     * In the assumption that the Boxalino Intelligence Layout Block has position:top,
     * it is expected for the blocks to be located under that response "parameter"
     *
     * @return \ArrayIterator
     */
    public function getBlocks(): \ArrayIterator
    {
        if ($this->currentApiResponse->get() && $this->currentApiResponseView->get()->isFallback()) {
            return new \ArrayIterator();
        }

        return $this->currentApiResponse->get()->getTop();
    }

    /**
     * Boxalino API: use the generic template to render blocks and children
     *
     * @return string
     */
    public function getTemplate()
    {
        return ApiResponseBlockInterface::BOXALINO_RTUX_API_BLOCK_TEMPLATE_DEFAULT;
    }


}
