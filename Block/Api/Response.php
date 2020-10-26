<?php
namespace Boxalino\RealTimeUserExperience\Block\Api;

use Boxalino\RealTimeUserExperience\Api\ApiResponseBlockInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperience\Block\ApiResponseTrait;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;

/**
 * @package Boxalino\RealTimeUserExperience\Block\Api
 */
class Response extends \Magento\Framework\View\Element\Template
    implements ApiResponseBlockInterface
{
    use ApiBlockTrait;
    use ApiResponseTrait;

    /**
     * @return \ArrayIterator|null
     */
    public function getBlocks(): \ArrayIterator
    {
        if($this->apiResponsePage)
        {
            return $this->apiResponsePage->getBlocks();
        }

        return new \ArrayIterator();
    }

    /**
     * Dynamically get properties from the response page
     *
     * @param string $methodName
     * @param null $params
     * @return $this
     */
    public function __call($methodName, $params = null)
    {
        $methodPrefix = substr($methodName, 0, 3);
        $key = strtolower(substr($methodName, 3, 1)) . substr($methodName, 4);
        if($methodPrefix == 'get')
        {
            try{
                return $this->getApiResponsePage()->$methodName();
            } catch (\Exception $exception)
            {
                try{
                    return $this->$key;
                } catch (\Exception $exception)
                {
                    throw new UndefinedPropertyError("BoxalinoAPI: the function $methodName is not available in the response page block " . json_encode($this->getApiResponsePage()));
                }
            }
        }
    }

}
