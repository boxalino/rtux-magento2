<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Service\Api\Response\Accessor;

use Boxalino\RealTimeUserExperience\Api\ApiBlockAccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\Block as ApiBlock;

/**
 * Class Block
 * Extending the default API data-contract in order to access Magento2-required properties for layout block generation
 *
 * @package Boxalino\RealTimeUserExperience\Service\Api\Accessor
 */
class Block extends ApiBlock
    implements ApiBlockAccessorInterface
{

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * Block type (as required in order to create Magento2 layout blocks)
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Block name (as required in order to create Magento2 layout blocks)
     *
     * @return string|null
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param array $value
     * @return $this|ApiBlockAccessorInterface
     */
    public function setType(array $value) : ApiBlockAccessorInterface
    {
        $this->type = $value[0];
        return $this;
    }

    /**
     * @param array $value
     * @return $this|ApiBlockAccessorInterface
     */
    public function setName(array $value) : ApiBlockAccessorInterface
    {
        $this->name = $value[0];
        return $this;
    }

    /**
     * As configured in the di.xml - product is a match for bx-hit accessor
     *
     * @return AccessorInterface|null
     */
    public function getProduct()
    {
        if(isset($this->product))
        {
            return $this->get("product");
        }

        return null;
    }

}
