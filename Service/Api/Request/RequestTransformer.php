<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Request;

use Boxalino\RealTimeUserExperience\Service\Api\Util\RequestParametersTrait;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;
use Psr\Log\LoggerInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Request\RequestTransformerAbstract as ApiRequestTransformer;
use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;

/**
 * Class RequestTransformer
 * Sets request variables dependent on the channel
 * (account, credentials, environment details -- language, dev, test, session, header parameters, etc)
 *
 * @package Boxalino\RealTimeUserExperience\Framework\Request
 */
class RequestTransformer extends ApiRequestTransformer
    implements RequestTransformerInterface
{
    use RequestParametersTrait;

    public function __construct(
        ParameterFactoryInterface $parameterFactory,
        ConfigurationInterface $configuration,
        ApiSortingModelInterface $sortingModel,
        LoggerInterface $logger,
        StoreConfigurationHelper $storeConfigurationHelper
    ){
        parent::__construct($parameterFactory, $configuration, $sortingModel, $logger);
        $this->storeConfigurationHelper = $storeConfigurationHelper;
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    public function getCustomerId(RequestInterface $request) : string
    {
        $sessionCustomerId = $this->storeConfigurationHelper->getSessionCustomerId();
        if(is_null($sessionCustomerId))
        {
            return $this->getProfileId($request);
        }

        return (string) $sessionCustomerId;
    }

    /**
     * Store ID
     *
     * @return string
     */
    public function getContextId() : string
    {
        return (string) $this->storeConfigurationHelper->getMagentoStoreId();
    }

}
