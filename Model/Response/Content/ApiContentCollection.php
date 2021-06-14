<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Content;

use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorInterface;
use Boxalino\RealTimeUserExperienceApi\Service\Api\Response\Accessor\AccessorModelInterface;


/**
 * Class ApiContentCollection
 *
 * Item refers to any data model/logic that is desired to be rendered/displayed
 * The integrator can decide to either use all data as provided by the Narrative API,
 * or to design custom data layers to represent the fetched content
 *
 * @package Boxalino\RealTimeUserExperience\Model\Response
 */
class ApiContentCollection implements AccessorModelInterface
{

    /**
     * @var \ArrayIterator
     */
    protected $collection;

    public function __construct(){
        $this->collection = new \ArrayIterator();
    }

    /**
     * @return \ArrayIterator
     */
    public function getCollection() : \ArrayIterator
    {
        return $this->collection;
    }

    /**
     * Creates the collection which has only the return fields requested
     *
     * @param \ArrayIterator $blocks
     * @param string $hitAccessor
     */
    public function setCollection(\ArrayIterator $blocks, string $hitAccessor) : void
    {
        $items = array_map(function(AccessorInterface $block) use ($hitAccessor) {
            if(property_exists($block, $hitAccessor))
            {
                return $block->get($hitAccessor);
            }
        }, $blocks->getArrayCopy());

        $this->collection = $items;
    }

    /**
     * @param null | AccessorInterface $context
     * @return AccessorModelInterface
     */
    public function addAccessorContext(?AccessorInterface $context = null): AccessorModelInterface
    {
        $this->setCollection($context->getBlocks(), $context->getAccessorHandler()->getAccessorSetter("bx-hit"));
        return $this;
    }


}
