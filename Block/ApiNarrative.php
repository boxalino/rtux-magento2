<?php
namespace Boxalino\RealTimeUserExperience\Block;

use Boxalino\RealTimeUserExperience\Block\ApiNarrative\CmsContext;
use Boxalino\RealTimeUserExperience\Model\Request\ApiPageLoader;
use Magento\Framework\View\Element\Template;

/**
 * Class Test
 * testing requests to Boxalino API
 *
 * @package Boxalino\RealTimeUserExperience\Block
 */
class ApiNarrative extends \Magento\Framework\View\Element\Template
{

    protected $apiLoader;

    public function __construct(
        ApiPageLoader $apiPageLoader,
        CmsContext $cmsContext,
        Template\Context $context,
        array $data = []
    ){
        parent::__construct($context, $data);
    }

    public function _getData($key)
    {
        $this->_logger->info("in _getData");
        return parent::_getData($key);
    }

    public function _beforeToHtml()
    {
        $this->_logger->info("in _beforeToHtml");
        return parent::_beforeToHtml();
    }

    public function _afterToHtml($html)
    {
        $this->_logger->info("in _afterToHtml");
        return parent::_afterToHtml($html);
    }

    /**
     * @return string
     */
    public function getTemplate() : string
    {
        return "Boxalino_RealTimeUserExperience::api/narrative.phtml";
    }
}
