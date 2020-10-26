<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Request;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterFactoryInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Request\ParameterInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ParameterFactory
 * Creates the parameter setter objects which are declared as public services
 *
 * @package Boxalino\RealTimeUserExperienceApi\Service\Api\Request
 */
class ParameterFactory implements ParameterFactoryInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \ArrayIterator
     */
    protected $definitionLinks;

    public function __construct(
        LoggerInterface $boxalinoLogger,
        array $definitionLinks = []
    ){
        $this->logger = $boxalinoLogger;
        $this->definitionLinks = new \ArrayIterator();
        foreach($definitionLinks as $type=>$definition)
        {
            $this->definitionLinks->offsetSet($type, $definition);
        }
    }

    /**
     * @param string $type
     * @return mixed
     */
    public function get(string $type) : ParameterInterface
    {
        $serviceId = strtolower($type);
        if($this->definitionLinks->offsetExists($serviceId))
        {
            $service =  $this->definitionLinks->offsetGet($serviceId)->create();
            if($service instanceof ParameterInterface)
            {
                return $service;
            }
            $this->logger->warning("BoxalinoApi: the requested service does not follow the required interface: $serviceId");
            return $this->definitionLinks->offsetGet($this->getDefaultParameterType())->create();
        }

        $this->logger->error("BoxalinoApi: the requested service does not exist: $serviceId; The default parameter service will be used.");
        return $this->definitionLinks->offsetGet($this->getDefaultParameterType())->create();
    }

    /**
     * @return string
     */
    public function getDefaultParameterType() : string
    {
        return ParameterFactoryInterface::BOXALINO_API_REQUEST_PARAMETER_DEFINITION;
    }

}
