<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Util;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestDefinitionInterface;
use Magento\Catalog\Model\Product\ProductList\Toolbar;
use Magento\Search\Model\QueryFactory;

/**
 * Trait RequestParametersTrait
 * Describes the local e-shop request parameters
 *
 * @package Boxalino\RealTimeUserExperience\Framework\Request
 */
trait RequestParametersTrait
{

    /**
     * @var \Boxalino\RealTimeUserExperience\Helper\Configuration
     */
    protected $storeConfigurationHelper;

    /**
     * @return string
     */
    public function getSortParameter() : string
    {
        return Toolbar::ORDER_PARAM_NAME;
    }

    /**
     * @return string
     */
    public function getDirectionParameter() : string
    {
        return Toolbar::DIRECTION_PARAM_NAME;
    }

    /**
     * @return string
     */
    public function getSearchParameter() : string
    {
        return QueryFactory::QUERY_VAR_NAME;
    }

    /**
     * @return string
     */
    public function getPageNumberParameter() : string
    {
        return Toolbar::PAGE_PARM_NAME;
    }

    /**
     * @return string
     */
    public function getPageLimitParameter() : string
    {
        return Toolbar::LIMIT_PARAM_NAME;
    }

    /**
     * Magento2 configuration parameter "grid_per_page"
     *
     * @return string
     */
    public function getDefaultLimitValue() : int
    {
        return $this->storeConfigurationHelper->getMagentoStoreConfigPageSize();
    }

    /**
     * @return string
     */
    public function getBlockViewModeParameter() : string
    {
        return Toolbar::MODE_PARAM_NAME;
    }

    /**
     * @return string
     */
    public function getSystemContextParameter() : string
    {
        return "id";
    }

    /**
     * @return array
     */
    public function getSystemParameters() : array
    {
        return [
            $this->getPageLimitParameter(),
            $this->getPageNumberParameter(),
            $this->getBlockViewModeParameter(),
            $this->getSearchParameter(),
            $this->getDirectionParameter(),
            $this->getSortParameter(),
            $this->getSystemContextParameter(),
            RequestDefinitionInterface::BOXALINO_API_REQUEST_INSPECT_FLAG,
            RequestDefinitionInterface::BOXALINO_API_WIDGET_INSPECT_FLAG
        ];
    }


}
