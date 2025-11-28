<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Request;

use Boxalino\RealTimeUserExperience\Service\Api\Util\RequestParametersTrait;
use Boxalino\RealTimeUserExperienceApi\Framework\Content\Listing\ApiSortingModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCookieSubscriber;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\RequestTransformerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\ConfigurationInterface;
use Psr\Log\LoggerInterface;
use Boxalino\RealTimeUserExperienceApi\Framework\Request\RequestTransformerAbstract as ApiRequestTransformer;
use Boxalino\RealTimeUserExperience\Helper\Configuration as StoreConfigurationHelper;
use Ramsey\Uuid\Uuid;

/**
 * Class RequestTransformer
 * Sets request variables dependent on the channel
 * (account, credentials, environment details -- language, dev, test, session, header parameters, etc)
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api\Request
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
    public function getSessionId(RequestInterface $request) : string
    {
        if($this->configuration->isCookieRestrictionModeEnabled())
        {
            if($this->configuration->isUserNotAllowSaveCookie()) {
                if($this->configuration->hasSessionAttribute(ApiCookieSubscriber::BOXALINO_API_INIT_SESSION))
                {
                    return $this->configuration->getSessionAttribute(ApiCookieSubscriber::BOXALINO_API_INIT_SESSION);
                }

                return $this->configuration->setSessionAttribute(ApiCookieSubscriber::BOXALINO_API_INIT_SESSION, Uuid::uuid4()->toString());
            }
        }

        return parent::getSessionId($request);
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    public function getProfileId(RequestInterface $request) : string
    {
        if($this->configuration->isCookieRestrictionModeEnabled())
        {
            if($this->configuration->isUserNotAllowSaveCookie()) {
                if($this->configuration->hasSessionAttribute(ApiCookieSubscriber::BOXALINO_API_INIT_VISITOR))
                {
                    return $this->configuration->getSessionAttribute(ApiCookieSubscriber::BOXALINO_API_INIT_VISITOR);
                }

                return $this->configuration->setSessionAttribute(ApiCookieSubscriber::BOXALINO_API_INIT_VISITOR, Uuid::uuid4()->toString());
            }
        }

        return parent::getProfileId($request);
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    public function getCustomerId(RequestInterface $request) : string
    {
        $sessionCustomerId = $this->configuration->getSessionCustomerId();
        if(is_null($sessionCustomerId))
        {
            return $this->getProfileId($request);
        }

        return (string) $sessionCustomerId;
    }


}
