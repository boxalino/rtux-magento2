<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Framework\Request;

use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;
use Boxalino\RealTimeUserExperienceApi\Framework\Request\ContextAbstract;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\Definition\ListingRequestDefinitionInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Magento\Catalog\Model\Product\Visibility;

/**
 * Class CmsContext
 * Holds the request properties: widget, hitcount, returnfields, groupby, offset, etc
 *
 * @package Boxalino\RealTimeUserExperience\Block\ApiNarrative
 */
class CmsContext extends ContextAbstract
    implements ContextInterface
{
    use ContextTrait;
    use RequestParametersTrait;

    public function __construct(
        RequestTransformerInterface $requestTransformer,
        ParameterFactoryInterface $parameterFactory,
        ListingRequestDefinitionInterface $requestDefinition,
        StoreConfigurationHelper $storeConfigurationHelper
    ) {
        parent::__construct($requestTransformer, $parameterFactory);
        $this->setRequestDefinition($requestDefinition);
        $this->storeConfigurationHelper = $storeConfigurationHelper;
    }

    /**
     * Product visibility on a search context
     * @return array
     */
    public function getContextVisibility() : array
    {
        return [Visibility::VISIBILITY_BOTH, Visibility::VISIBILITY_IN_SEARCH, Visibility::VISIBILITY_IN_CATALOG];
    }

    /**
     * For the search context - generally the root category ID is the navigation filter (if needed)
     *
     * @param RequestInterface $request
     * @return string
     */
    public function getContextNavigationId(RequestInterface $request): array
    {
        return [$this->storeConfigurationHelper->getMagentoRootCategoryId()];
    }

    /**
     * Other fields can be: products_seo_url, products_image, discountedPrice, etc
     * @return array
     */
    public function getReturnFields() : array
    {
        return ["id", "products_group_id", "title"];
    }


}
