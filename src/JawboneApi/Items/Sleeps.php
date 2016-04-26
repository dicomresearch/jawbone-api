<?php

namespace JawboneApi\Items;


use JawboneApi\AccessScope;
use JawboneApi\Interfaces\AddableInterface;
use JawboneApi\Interfaces\CollectibleInterface;
use JawboneApi\Interfaces\RemovableInterface;
use JawboneApi\Interfaces\SelfLoadingInterface;
use JawboneApi\Interfaces\ViewableInterface;
use JawboneApi\Traits\AddableTrait;
use JawboneApi\Traits\AttachImageTrait;
use JawboneApi\Traits\ChartImageTrait;
use JawboneApi\Traits\SelfLoadingTrait;
use JawboneApi\Traits\SnapshotTrait;
use JawboneApi\Traits\TimestampTrait;

class Sleeps extends AbstractItem implements CollectibleInterface, ViewableInterface, AddableInterface, RemovableInterface, SelfLoadingInterface
{
    use TimestampTrait, AddableTrait, AttachImageTrait, SelfLoadingTrait, SnapshotTrait, ChartImageTrait;

    protected $jsonToPropertyMap = [
        'xid' => 'xid',
        'title' => 'title',
        'type' => 'type',
        'sub_type' => 'subType',
        'snapshot_image' => 'imageUri',
        'time_created' => 'timeCreated',
        'time_updated' => 'timeUpdated',
        'time_completed' => 'timeCompleted',
        'date' => 'date',
        'tz' => 'timeZone',
        'place_lat' => 'placeLatitude',
        'place_lon' => 'placeLongitude',
        'place_acc' => 'placeAccuracy',
        'place_name' => 'placeName',
        'details' => 'detailsProcessing',
        'smart_alarm_fire' => 'smartAlarmTime',
        'awake_time' => 'awakeTime',
        'asleep_time' => 'asleepTime',
        'awakenings' => 'awakeningCount',
        'light' => 'lightSleepDuration',
        'deep' => 'deepSleepDuration',
        'awake' => 'awakeSleepDuration',
        'duration' => 'sleepTotalDuration',
        'quality' => 'sleepQuality'
    ];

    protected $paramsDisableForCreate = [
        'xid' => 'xid',
        'title' => 'title',
        'type' => 'type',
        'sub_type' => 'subType',
        'snapshot_image' => 'imageUri',
        'date' => 'date',
        'place_lat' => 'placeLatitude',
        'place_lon' => 'placeLongitude',
        'place_acc' => 'placeAccuracy',
        'place_name' => 'placeName',
        'details' => 'detailsProcessing',
        'smart_alarm_fire' => 'smartAlarmTime',
        'awake_time' => 'awakeTime',
        'asleep_time' => 'asleepTime',
        'awakenings' => 'awakeningCount',
        'light' => 'lightSleepDuration',
        'deep' => 'deepSleepDuration',
        'awake' => 'awakeSleepDuration',
        'duration' => 'sleepTotalDuration',
        'quality' => 'sleepQuality'
    ];

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var string|null
     */
    public $type;

    /**
     * Type of sleep. 0=normal, 1=power_nap, 2=nap
     *
     * @var int|null
     */
    public $subType;

    /**
     * Number of times the user awoke during sleep period
     *
     * @var int|null
     */
    public $awakeningCount;

    /**
     * Total light sleep time, in seconds
     *
     * @var int|null
     */
    public $lightSleepDuration;

    /**
     * Total deep sleep time, in seconds
     *
     * @var int|null
     */
    public $deepSleepDuration;

    /**
     * Total time spent awake, in seconds
     *
     * @var int|null
     */
    public $awakeSleepDuration;

    /**
     * Total time for this sleep event, in seconds
     *
     * @var int|null
     */
    public $sleepTotalDuration;

    /**
     * Sleep quality for the night
     *
     * Based on a proprietary formula of light and deep sleep vs wake time.
     * Note this is a different value than the percentage shown in the UP app
     * (which is the percentage of sleep goal completed)
     *
     * @var int|null
     */
    public $sleepQuality;

    /**
     * Time when smart alarm was fired
     *
     * @var \DateTime|null
     */
    public $smartAlarmTime;

    /**
     * Time when the user awoke
     *
     * @var \DateTime|null
     */
    public $awakeTime;

    /**
     * Time when the user fell asleep
     *
     * @var \DateTime|null
     */
    public $asleepTime;

    /**
     * Time when this sleep was completed
     *
     * @var \DateTime|null
     */
    public $timeCompleted;

    /**
     * Return endpoint part of request uri
     *
     * @return string
     */
    public function getEndPointName()
    {
        return 'sleeps';
    }

    /**
     * Identifier of write permission
     *
     * All available identifiers specified in the JawboneApi\AccessScope class
     *
     * @return string
     */
    public function getWritePermissionName()
    {
        return AccessScope::SLEEP_WRITE;
    }

    /**
     * Return action name part of request uri for view records' list
     *
     * @return string|null
     */
    public function getListActionName()
    {
        return 'sleeps';
    }

    /**
     * Return action name part of request uri for remove exist record
     *
     * @return string
     */
    public function getRemoveActionName()
    {
        return null;
    }

    /**
     * Return action name part of request uri for view single record
     *
     * @return string|null
     */
    public function getViewActionName()
    {
        return null;
    }

    /**
     * Set properties from details section to model properties
     *
     * @param \stdClass $details
     */
    public function setDetailsProcessingFromJson(\stdClass $details)
    {
        $parameters = (array) $details;
        foreach ($this->jsonToPropertyMap as $jsonKey => $propertyName) {
            if (array_key_exists($jsonKey, $parameters)) {
                $methodName = $this->createSetterMethodName($propertyName, true);
                $this->$methodName($parameters[$jsonKey]);
            }
        }
    }

    public function getDetailsProcessing()
    {
        return [];
    }

    public function setSmartAlarmTimeFromJson($timestamp)
    {
        $this->setSmartAlarmTime(
            $this->createDateTime($timestamp)
        );
    }

    public function setSmartAlarmTime(\DateTime $dateTime)
    {
        $this->smartAlarmTime = $dateTime;
    }

    public function getSmartAlarmTime()
    {
        return $this->assignDefaultTimeZone($this->smartAlarmTime);
    }

    public function setAwakeTimeFromJson($timestamp)
    {
        $this->setAwakeTime(
            $this->createDateTime($timestamp)
        );
    }

    public function setAwakeTime(\DateTime $dateTime)
    {
        $this->awakeTime = $dateTime;
    }

    public function getAwakeTime()
    {
        return $this->assignDefaultTimeZone($this->awakeTime);
    }

    public function setAsleepTimeFromJson($timestamp)
    {
        $this->setAsleepTime(
            $this->createDateTime($timestamp)
        );
    }

    public function setAsleepTime(\DateTime $dateTime)
    {
        $this->asleepTime = $dateTime;
    }

    public function getAsleepTime()
    {
        return $this->assignDefaultTimeZone($this->asleepTime);
    }

    /**
     * @param $timestamp
     */
    public function setTimeCompletedFromJson($timestamp)
    {
        $this->setTimeCompleted(
            $this->createDateTime($timestamp)
        );
    }

    public function getTimeCompletedForJson()
    {
        return $this->timestampFromDateTime($this->timeCompleted);
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setTimeCompleted(\DateTime $dateTime)
    {
        $this->timeCompleted = $dateTime;
    }

    /**
     * @return \DateTime|null
     */
    public function getTimeCompleted()
    {
        return $this->assignDefaultTimeZone($this->timeCompleted);
    }

	public function getDuration()
    {		
        return round($this->sleepTotalDuration / 3600, 1);
    }
}



