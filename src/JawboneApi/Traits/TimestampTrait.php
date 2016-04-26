<?php

namespace JawboneApi\Traits;


trait TimestampTrait
{
    /**
     * @var \DateTime|null
     */
    public $timeCreated;

    /**
     * @var \DateTime|null
     */
    public $timeUpdated;

    /**
     * @var \DateTimeZone
     */
    public $timeZone;

    /**
     * Updated date, formatted as YYYYMMDD
     *
     * @var string
     */
    public $date;

    public function setTimeCreatedFromJson($timestamp)
    {
        $this->setTimeCreated(
            $this->createDateTime($timestamp)
        );
    }

    public function getTimeCreatedForJson()
    {
        return $this->timestampFromDateTime($this->timeCreated);
    }

    public function setTimeCreated(\DateTime $dateTime)
    {
        $this->timeCreated = $dateTime;
    }

    public function getTimeCreated()
    {
        return $this->assignDefaultTimeZone($this->timeCreated);
    }

    public function setTimeUpdatedFromJson($timestamp)
    {
        $this->setTimeUpdated(
            $this->createDateTime($timestamp)
        );
    }

    public function getTimeUpdatedForJson()
    {
        return $this->timestampFromDateTime($this->timeUpdated);
    }

    public function setTimeUpdated(\DateTime $dateTime)
    {
        $this->timeUpdated = $dateTime;
    }

    public function getTimeUpdated()
    {
        return $this->assignDefaultTimeZone($this->timeUpdated);
    }

    public function setTimeZoneFromJson($timeZoneName)
    {
        if (!in_array($timeZoneName, \DateTimeZone::listIdentifiers())) {
            $timeZoneName = date_default_timezone_get();
        }
        $this->timeZone = new \DateTimeZone($timeZoneName);
    }

    public function getTimeZoneForJson()
    {
        if ($this->timeZone instanceof \DateTimeZone) {
            $zoneName = $this->timeZone->getName();
        } else {
            $zoneName = date_default_timezone_get();
        }
        return $zoneName;
    }

    public function createDateTime($timestamp)
    {
        return new \DateTime('@' . $timestamp);
    }

    public function timestampFromDateTime($dateTime)
    {
        if (!$dateTime instanceof \DateTime) {
            return null;
        }
        return $dateTime->setTimezone($this->timeZone)->getTimestamp();
    }

    public function assignDefaultTimeZone($dateTime)
    {
        if (!$dateTime instanceof \DateTime) {
            return null;
        }
        return $dateTime->setTimezone(new \DateTimeZone(date_default_timezone_get()));
    }

    /**
     * Function for correct time zone parsing
     *
     * @param \stdClass $object
     */
    public function setDetailsTimeZoneFromJson(\stdClass $object)
    {
        $this->setTimeZoneFromJson($object->tz);
    }

    /**
     * Function for correct time zone export
     *
     * @return array
     */
    public function getDetailsTimeZoneForJson()
    {
        return ['tz' => $this->getTimeZoneForJson()];
    }
}