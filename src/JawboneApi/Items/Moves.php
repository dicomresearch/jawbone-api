<?php

namespace JawboneApi\Items;


use JawboneApi\AccessScope;
use JawboneApi\Interfaces\CollectibleInterface;
use JawboneApi\Interfaces\SelfLoadingInterface;
use JawboneApi\Interfaces\ViewableInterface;
use JawboneApi\Traits\AttachImageTrait;
use JawboneApi\Traits\ChartImageTrait;
use JawboneApi\Traits\SelfLoadingTrait;
use JawboneApi\Traits\SnapshotTrait;
use JawboneApi\Traits\TimestampTrait;

class Moves extends AbstractItem implements CollectibleInterface, ViewableInterface, SelfLoadingInterface
{
    use TimestampTrait, AttachImageTrait, SelfLoadingTrait, SnapshotTrait, ChartImageTrait;

    protected $jsonToPropertyMap = [
        'xid' => 'xid',
        'title' => 'title',
        'type' => 'type',
        'snapshot_image' => 'imageUri',
        'time_created' => 'timeCreated',
        'time_updated' => 'timeUpdated',
        'time_completed' => 'timeCompleted',
        'date' => 'date',
        'tz' => 'timeZone',
        'details' => 'detailsProcessing',
        'distance' => 'travelledDistance',
        'steps' => 'stepCounter',
        'active_time' => 'activeTime',
        'longest_active' => 'longestActivePeriod',
        'inactive_time' => 'inactiveTime',
        'longest_idle' => 'longestInactivePeriod',
        'calories' => 'burnedCalories',
        'bmr_day' => 'bmrForDay',
        'bmr' => 'bmr',
        'bg_calories' => 'burnedCaloriesFromUp',
        'wo_calories' => 'burnedCaloriesFromWorkouts',
        'wo_time' => 'workoutSpentTime',
        'wo_active_time' => 'steppingSpentTime',
        'wo_count' => 'workoutEventCount',
        'wo_longest' => 'longestWorkoutPeriod',
        'hourly_totals' => 'hourlyTotalArray'
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
     * Distance travelled, in meters
     *
     * @var int|null
     */
    public $travelledDistance;

    /**
     * Number of steps taken
     *
     * @var int|null
     */
    public $stepCounter;

    /**
     * Total active time for move, in seconds
     *
     * @var int|null
     */
    public $activeTime;

    /**
     * Longest consecutive active period, in seconds
     *
     * @var int|null
     */
    public $longestActivePeriod;

    /**
     * Total inactive time for move, in seconds
     *
     * @var int|null
     */
    public $inactiveTime;

    /**
     * Longest consecutive inactive period, in seconds
     *
     * @var int|null
     */
    public $longestInactivePeriod;

    /**
     * Total calories burned
     *
     * This is computed by this formula: wo_calories+bg_calories+bmr_day / 86400 * active_time
     *
     * @var int|null
    */
    public $burnedCalories;

    /**
     * Estimated basal metabolic rate for entire day, in calories
     *
     * @var int|null
     */
    public $bmrForDay;

    /**
     * Estimated basal metabolic rate at time of last sync
     *
     * For previous days should approximately equal bmr_day
     *
     * @var int|null
     */
    public $bmr;

    /**
     * Calories directly from UP band activity outside the context of a workout
     *
     * @var int|null
     */
    public $burnedCaloriesFromUp;

    /**
     * Calories burned from workouts
     *
     * @var int|null
     */
    public $burnedCaloriesFromWorkouts;

    /**
     * Total time spent in workouts, in seconds
     *
     * @var int|null
     */
    public $workoutSpentTime;

    /**
     * Actual active time during workout (where user was stepping) in seconds
     *
     * @var int|null
     */
    public $steppingSpentTime;

    /**
     * Number of workouts logged during this move
     *
     * @var int|null
     */
    public $workoutEventCount;

    /**
     * Longest workout period, in seconds
     *
     * @var int|null
     */
    public $longestWorkoutPeriod;

    /**
     * Data broken out by hour (values as above)
     *
     * @var array
     */
    public $hourlyTotalArray = [];

    /**
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
        return 'moves';
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
        return AccessScope::MOVE_WRITE;
    }

    /**
     * Return action name part of request uri for view records' list
     *
     * @return string|null
     */
    public function getListActionName()
    {
        return 'moves';
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

    /**
     * @param $timestamp
     */
    public function setTimeCompletedFromJson($timestamp)
    {
        $this->setTimeCompleted(
            $this->createDateTime($timestamp)
        );
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

    /**
     * Transformation hourly total values to array
     *
     * @param \stdClass $attributes
     */
    public function setHourlyTotalArrayFromJson(\stdClass $attributes)
    {
        $episodeArray =  (array) $attributes;
        foreach ($episodeArray as $timestamp => $details) {
            $this->hourlyTotalArray[$timestamp] = (array) $details;
        }
    }

	public function getSteps()
    {
        return $this->stepCounter;
    }
}
