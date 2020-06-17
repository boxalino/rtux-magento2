<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Framework\Request;

use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;
use Boxalino\RealTimeUserExperienceApi\Framework\Request\ContextAbstract;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ContextInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactory;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Magento\Catalog\Model\Product\Visibility;
use Symfony\Component\HttpFoundation\Request;

/**
 * Boxalino Search Request handler
 * Allows to set the nr of subphrases and products returned on each subphrase hit
 *
 * @package Boxalino\RealTimeUserExperienceSample\Framework\Request
 */
class CmsContext extends ContextAbstract
    implements ContextInterface
{
    use ContextTrait;
    use RequestParametersTrait;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Search\Model\QueryFactory
     */
    protected $queryFactory;

    public function __construct(
        RequestTransformerInterface $requestTransformer,
        ParameterFactory $parameterFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Search\Model\QueryFactory $queryFactory,
        StoreConfigurationHelper $storeConfigurationHelper
    ) {
        parent::__construct($requestTransformer, $parameterFactory);
        $this->request = $request;
        $this->queryFactory = $queryFactory;
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
     * @param Request $request
     * @return string
     */
    public function getContextNavigationId(Request $request): array
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
