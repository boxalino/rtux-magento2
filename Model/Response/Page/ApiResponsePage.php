<?php declare(strict_types=1);
namespace Boxalino\RealTimeUserExperience\Model\Response\Page;

use Boxalino\RealTimeUserExperienceApi\Framework\Content\Page\ApiResponsePageInterface;

/**
 * @package Boxalino\RealTimeUserExperience\Model\Response\Page
 */
class ApiResponsePage implements ApiResponsePageInterface
{
    /**
     * @var \ArrayIterator
     */
    protected $blocks;

    /**
     * @var string
     */
    protected $requestId;

    /**
     * @var string
     */
    protected $groupBy;

    /**
     * @var bool
     */
    protected $fallback = false;

    /**
     * @var string
     */
    protected $variantUuid;

    /**
     * @var bool
     */
    protected $hasSearchSubPhrases = false;

    /**
     * @var string | null
     */
    protected $redirectUrl;

    /**
     * @var int
     */
    protected $totalHitCount = 0;

    /**
     * @var string
     */
    protected $searchTerm;

    /**
     * @return \ArrayIterator
     */
    public function getBlocks() : \ArrayIterator
    {
        return $this->blocks;
    }

    /**
     * @return string
     */
    public function getRequestId() : string
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getGroupBy() : string
    {
        return $this->groupBy;
    }

    /**
     * @param \ArrayIterator $blocks
     * @return $this
     */
    public function setBlocks(\ArrayIterator $blocks) : self
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @param string $groupBy
     * @return $this
     */
    public function setGroupBy(string $groupBy) : self
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @param string $requestId
     * @return $this
     */
    public function setRequestId(string $requestId) : self
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isFallback(): bool
    {
        return $this->fallback;
    }

    /**
     * @param bool $fallback
     * @return ApiResponsePage
     */
    public function setFallback(bool $fallback): ApiResponsePage
    {
        $this->fallback = $fallback;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariantUuid(): string
    {
        return $this->variantUuid;
    }

    /**
     * @param string $variantUuid
     * @return ApiResponsePage
     */
    public function setVariantUuid(string $variantUuid): ApiResponsePage
    {
        $this->variantUuid = $variantUuid;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSearchSubPhrases(): bool
    {
        return $this->hasSearchSubPhrases;
    }

    /**
     * @param bool $hasSearchSubPhrases
     * @return ApiResponsePage
     */
    public function setHasSearchSubPhrases(bool $hasSearchSubPhrases): ApiResponsePage
    {
        $this->hasSearchSubPhrases = $hasSearchSubPhrases;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    /**
     * @param string|null $redirectUrl
     * @return ApiResponsePage
     */
    public function setRedirectUrl(?string $redirectUrl): ApiResponsePage
    {
        $this->redirectUrl = $redirectUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalHitCount(): int
    {
        return $this->totalHitCount;
    }

    /**
     * @param int $totalHitCount
     * @return ApiResponsePage
     */
    public function setTotalHitCount(int $totalHitCount): ApiResponsePage
    {
        $this->totalHitCount = $totalHitCount;
        return $this;
    }

    /**
     * @param string $searchTerm
     * @return $this
     */
    public function setSearchTerm(string $searchTerm) : ApiResponsePage
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }

    /**
     * @return string
     */
    public function getSearchTerm() : string
    {
        return $this->searchTerm;
    }

}
