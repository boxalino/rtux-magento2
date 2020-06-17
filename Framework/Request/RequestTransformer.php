<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Framework\Request;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactory;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;
use Doctrine\DBAL\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Boxalino\RealTimeUserExperienceApi\Framework\Request\RequestTransformerAbstract as ApiRequestTransformer;

use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;

/**
 * Class RequestTransformer
 * Sets request variables dependent on the channel
 * (account, credentials, environment details -- language, dev, test, session, header parameters, etc)
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api
 */
class RequestTransformer extends ApiRequestTransformer
    implements RequestTransformerInterface
{
    use RequestParametersTrait;

    public function __construct(
        Connection $connection,
        ParameterFactory $parameterFactory,
        ConfigurationInterface $configuration,
        ApiSortingModelInterface $sortingModel,
        LoggerInterface $logger,
        StoreConfigurationHelper $storeConfigurationHelper
    ){
        parent::__construct($connection, $parameterFactory, $configuration, $sortingModel, $logger);
        $this->storeConfigurationHelper = $storeConfigurationHelper;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getCustomerId(Request $request) : string
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
