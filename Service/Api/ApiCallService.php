<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\ResponseDefinitionInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ApiCallService
 * Adding compression to the API request
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api
 */
class ApiCallService extends \Boxalino\RealTimeUserExperienceApi\Service\Api\ApiCallService
{

    /**
     * @param LoggerInterface $logger
     * @param ResponseDefinitionInterface $responseDefinition
     * @param bool $compress
     */
    public function __construct(
        LoggerInterface $logger,
        ResponseDefinitionInterface $responseDefinition,
        bool $compress = true
    ){
        parent::__construct($logger, $responseDefinition);
        $this->compress = $compress;
    }


}
