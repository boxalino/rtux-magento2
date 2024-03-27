<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Util;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterInterface;

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
     * @var RequestTransformerInterface
     */
    protected $requestTransformer;

    /**
     * @var ParameterFactoryInterface
     */
    protected $parameterFactory;


    public function validateRequest(RequestInterface $request) : void {}

    /**
     * @return ParameterInterface
     */
    public function getCategoryFilter(RequestInterface $request) : ParameterInterface
    {
        return $this->getParameterFactory()->get(ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_TYPE_FILTER)
            ->add("category_id", $this->getContextNavigationId($request));
    }

    /**
     * @return RequestTransformerInterface
     */
    public function getRequestTransformer()  : RequestTransformerInterface
    {
        return $this->requestTransformer;
    }

    /**
     * @return ParameterFactoryInterface
     */
    public function getParameterFactory() : ParameterFactoryInterface
    {
        return $this->parameterFactory;
    }


}
