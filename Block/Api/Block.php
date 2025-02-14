<?php
namespace Boxalino\RealTimeUserExperience\Block\Api;

use Boxalino\RealTimeUserExperience\Api\ApiRendererInterface;
use Boxalino\RealTimeUserExperience\Block\ApiBlockTrait;
use Boxalino\RealTimeUserExperienceApi\Service\ErrorHandler\UndefinedPropertyError;

/**
 * Class Block
 * Generic API block to access simple
 * @package Boxalino\RealTimeUserExperience\Block\Api
 */
class Block extends \Magento\Framework\View\Element\Template
    implements ApiRendererInterface
{

    use ApiBlockTrait;

    /**
     * Dynamically get properties from the response block
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
                return $this->getBlock()->get($key);
            } catch (\Exception $exception)
            {
                try {
                    parent::__call();
                } catch (\Throwable $exception) {
                    throw new UndefinedPropertyError("BoxalinoAPI: the property $key is not available in the layout block " . json_encode($this->getBlock()));
                }
            }
        }
    }

}
