<?php

namespace JawboneApi;

use JawboneApi\Interfaces\ItemInterface;
use JawboneApi\Items\AbstractItem;
use JawboneApi\SearchCondition;


class Collection extends \SplFixedArray
{
    const FORWARD = 1;

    const BACK = -1;

    const REFRESH = 0;

    private $autoPaging = true;

    private $scrollDirection = 0;

    /**
     * @var \JawboneApi\SearchCondition;
     */
    private $searchCondition;

    /**
     * @var string|null
     */
    private $previousPageUri;

    /**
     * @var string|null
     */
    private $currentPageUri;

    /**
     * @var string|null
     */
    private $nextPageUri;

    public function enableAutoPaging()
    {
        $this->autoPaging = true;
    }

    public function disableAutoPaging()
    {
        $this->autoPaging = false;
    }

    /**
     * @param \JawboneApi\SearchCondition $searchCondition
     */
    public function setSearchCondition(SearchCondition $searchCondition)
    {
        $this->searchCondition = $searchCondition;
    }

    /**
     * @return \JawboneApi\SearchCondition
     */
    public function getSearchCondition()
    {
        if (is_null($this->searchCondition)) {
            $this->resetSearchCondition();
        }
        return $this->searchCondition;
    }

    /**
     * @return \JawboneApi\Collection
     */
    public function resetSearchCondition()
    {
        $this->searchCondition = new SearchCondition();
        return $this;
    }

    /**
     * @param string|null $currentPageUri
     * @param int         $direction
     */
    public function setCurrentPageUri($currentPageUri, $direction = 0)
    {
        if ($this->needScrollPage($this->currentPageUri) && $direction == static::FORWARD) {
            $this->setPreviousPageUri($this->currentPageUri);
        } elseif ($this->needScrollPage($this->currentPageUri) && $direction == static::BACK) {
            $this->setNextPageUri($this->currentPageUri);
        }
        $this->currentPageUri = $currentPageUri;
    }

    /**
     * @return null|string
     */
    public function getCurrentPageUri()
    {
        return $this->currentPageUri;
    }

    /**
     * @param null|string $nextPageUri
     */
    public function setNextPageUri($nextPageUri)
    {
        if ($this->needScrollPage($this->nextPageUri)) {
            $this->setCurrentPageUri($this->nextPageUri, static::FORWARD);
        }
        $this->nextPageUri = $nextPageUri;
    }

    /**
     * @return null|string
     */
    public function getNextPageUri()
    {
        return $this->nextPageUri;
    }

    /**
     * @param null|string $previousPageUri
     */
    public function setPreviousPageUri($previousPageUri)
    {
        if ($this->needScrollPage($this->previousPageUri)) {
            $this->setCurrentPageUri($this->previousPageUri, static::BACK);
        }
        $this->previousPageUri = $previousPageUri;
    }

    /**
     * @return null|string
     */
    public function getPreviousPageUri()
    {
        return $this->previousPageUri;
    }

    /**
     * @param int $scrollDirection
     */
    public function setScrollDirection($scrollDirection)
    {
        $this->scrollDirection = $scrollDirection;
    }

    /**
     * Create collection of records
     *
     * @param \stdClass     $data
     * @param ItemInterface $item
     */
    public function update(\stdClass $data, ItemInterface $item)
    {
        if ($data->size > 0) {
            if ($this->getSize() !== $data->size) {
                $this->setSize($data->size);
            }
            $i=0;
            foreach ($data->items as $itemValues) {
                $newItem = clone $item;
                $newItem->populateFromJson($itemValues);
                $this->offsetSet($i++, $newItem);
            }
        }
        if (property_exists($data, 'links')) {
            $this->setNextPageUri($data->links->next);
        }
    }

    /**
     * @return null|string
     */
    public function getRequestPageUri()
    {
        if ($this->scrollDirection > static::REFRESH) {
            $pageUri = $this->getNextPageUri();
        } elseif ($this->scrollDirection < static::REFRESH) {
            $pageUri = $this->getPreviousPageUri();
        } else {
            $pageUri = $this->getCurrentPageUri();
        }
        return $pageUri;
    }

    protected function needScrollPage($pageUri)
    {
        return $this->autoPaging && !is_null($pageUri);
    }
}
