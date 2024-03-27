<?php
namespace Boxalino\RealTimeUserExperience\Block\Api;

use Boxalino\RealTimeUserExperience\Api\CurrentApiResponseViewRegistryInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Correlation
 * Sample on how to access correlations for a certain source
 * The block sample can be used as "block" property on the Layout Block definition
 * 
 * @package Boxalino\RealTimeUserExperience\Block\Api
 */
class Correlation extends Block
{

    /**
     * @var \ArrayIterator
     */
    protected $correlations;

    /**
     * @param CurrentApiResponseViewRegistryInterface $currentApiResponseView
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        CurrentApiResponseViewRegistryInterface $currentApiResponseView,
        Context $context,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->currentApiResponseView = $currentApiResponseView;
    }

    /**
     * @return \ArrayIterator
     */
    public function getCorrelations() : \ArrayIterator
    {
        if(is_null($this->correlations))
        {
            try{
                return $this->currentApiResponseView->get()->getCorrelations();
            } catch (\Throwable $exception)
            {
                $this->_logger->info("Boxalino Correlations Access Error: " . $exception->getMessage());
                return new \ArrayIterator();
            }
        }

        return $this->correlations;
    }


}
