<?php
namespace Boxalino\RealTimeUserExperience\Service\Api\Util;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Util\AccessorHandlerInterface;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\MissingDependencyException;

/**
 * Class ResponseAccessor
 *
 * Boxalino system accessors (base)
 * Creates objects and checks on accessor configurations (in Resources/config/services/api/accessor.xml)
 *
 * It is updated on further API extension & use-cases availability
 * Can be extended via custom API version provision
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api\Util
 */
class AccessorHandler extends \Boxalino\RealTimeUserExperienceApi\Service\Api\Util\AccessorHandler
    implements AccessorHandlerInterface
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * AccessorHandler constructor.
     *
     * @param array $accessorList
     * @param array $hitIdFieldNameList
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $accessorList = [],
        array $hitIdFieldNameList = []
    ){
        parent::__construct();
        $this->objectManager = $objectManager;
        foreach($accessorList as $type=>$setter)
        {
            foreach($setter as $key => $model)
            {
                $this->addAccessor($type, $key, $model);
            }
        }
        foreach($hitIdFieldNameList as $accessor=>$field)
        {
            $this->addHitIdFieldName($accessor, $field);
        }
    }


    /**
     * @param string $type
     * @param mixed|null $context
     * @return mixed
     */
    public function getModel(string $type, $context = null)
    {
        try {
            $service = $this->objectManager->create($type);
            if ($service instanceof AccessorModelInterface) {
                $service->addAccessorContext($context);
            }
            return $service;
        } catch (\Throwable $exception)
        {
            throw new MissingDependencyException(
                "BoxalinoApiAccessor: there was an issue accessing the service/model requested for $type. Original error: " . $exception->getMessage()
            );
        }
    }

}
