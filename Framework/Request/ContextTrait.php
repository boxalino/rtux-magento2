<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactory;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trait ContextTrait
 * sets all the functions required for the Boxalino\RealTimeUserExperienceApi\Framework\Request\ContextAbstract
 * to be used for all other implicit contexts
 * (generic filters)
 *
 * @package Boxalino\RealTimeUserExperience\Framework\Request
 */
trait ContextTrait
{

    /**
     * @var string
     */
    protected $groupBy = "products_group_id";

    /**
     * @param Request $request
     */
    public function validateRequest(Request $request) : void
    {
        return;
    }

    /**
     * @return ParameterInterface
     */
    public function getVisibilityFilter(Request $request) : ParameterInterface
    {
        return $this->getParameterFactory()->get(ParameterFactory::BOXALINO_API_REQUEST_PARAMETER_TYPE_FILTER)
            ->add("products_visibility", $this->getContextVisibility());
    }

    /**
     * @return ParameterInterface
     */
    public function getCategoryFilter(Request $request) : ParameterInterface
    {
        return $this->getParameterFactory()->get(ParameterFactory::BOXALINO_API_REQUEST_PARAMETER_TYPE_FILTER)
            ->add("category_id", $this->getContextNavigationId($request));
    }

    /**
     * @return ParameterInterface
     */
    public function getActiveFilter(Request $request) : ParameterInterface
    {
        return $this->getParameterFactory()->get(ParameterFactory::BOXALINO_API_REQUEST_PARAMETER_TYPE_FILTER)
            ->add("products_active", [1]);
    }

    /**
     * @return RequestTransformerInterface
     */
    public function getRequestTransformer()  : RequestTransformerInterface
    {
        return $this->requestTransformer;
    }

    /**
     * @return ParameterFactory
     */
    public function getParameterFactory() : ParameterFactory
    {
        return $this->parameterFactory;
    }

    /**
     * Set the range properties following the presented structure
     *
     * @return array
     */
    public function getRangeProperties() : array
    {
        return [
            "discountedPrice" => ['from' => 'min-price', 'to' => 'max-price']
        ];
    }

}
