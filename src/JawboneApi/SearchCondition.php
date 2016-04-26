<?php

namespace JawboneApi;


class SearchCondition
{
    const DATE_FORMAT = 'Ymd';

    /**
     * @var \DateTime|null
     */
    private $date;

    /**
     * @var \DateTime|null
     */
    private $pageToken;

    /**
     * @var \DateTime|null
     */
    private $startTime;

    /**
     * @var \DateTime|null
     */
    private $endTime;

    /**
     * @var \DateTime|null
     */
    private $updateAfter;

    /**
     * @var integer|null
     */
    private $limit;

    /**
     * @param \DateTime|null $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime|null $updateAfter
     */
    public function setUpdateAfter(\DateTime $updateAfter)
    {
        $this->updateAfter = $updateAfter;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdateAfter()
    {
        return $this->updateAfter;
    }

    /**
     * @param \DateTime|null $startTime
     */
    public function setStartTime(\DateTime $startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime|null $pageToken
     */
    public function setPageToken(\DateTime $pageToken)
    {
        $this->pageToken = $pageToken;
    }

    /**
     * @return \DateTime|null
     */
    public function getPageToken()
    {
        return $this->pageToken;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param \DateTime|null $endTime
     */
    public function setEndTime(\DateTime $endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    public function getSearchParamArray()
    {
        $searchParams = [];

        if ($this->getDate() instanceof \DateTime) {
            $searchParams['date'] = $this->getDate()->format(static::DATE_FORMAT);
        }

        if ($this->getLimit()) {
            $searchParams['limit'] = $this->getLimit();
        }

        $timestampParameters = [
            'start_time' => 'getStartTime',
            'end_time' => 'getEndTime',
            'updated_after' => 'getUpdateAfter',
            'page_token' => 'getPageToken'
        ];

        foreach ($timestampParameters as $keyName => $getterName) {
            if ($this->$getterName() instanceof \DateTime) {
                $searchParams[$keyName] = $this->$getterName()->getTimestamp();
            }
        }

        return $searchParams;
    }
}
