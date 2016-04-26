<?php

namespace JawboneApi\Items;


use JawboneApi\AccessScope;
use JawboneApi\Interfaces\AddableInterface;
use JawboneApi\Interfaces\CollectibleInterface;
use JawboneApi\Interfaces\RemovableInterface;
use JawboneApi\Interfaces\ViewableInterface;
use JawboneApi\Traits\AddableTrait;
use JawboneApi\Traits\AttachImageTrait;
use JawboneApi\Traits\LocationTrait;
use JawboneApi\Traits\TimestampTrait;

class Cardiac extends AbstractItem implements CollectibleInterface, ViewableInterface, AddableInterface, RemovableInterface
{
    use TimestampTrait, AddableTrait, LocationTrait, AttachImageTrait;

    protected $jsonToPropertyMap = [
        'xid' => 'xid',
        'title' => 'title',
        'place_lat' => 'placeLatitude',
        'place_lon' => 'placeLongitude',
        'place_acc' => 'placeAccuracy',
        'place_name' => 'placeName',
        'heart_rate' => 'heartRate',
        'systolic_pressure' => 'systolicPressure',
        'diastolic_pressure' => 'diastolicPressure',
        'image' => 'imageUri',
        'image_url' => 'imageUrl',
        'create_time' => 'timeCreated',
        'update_time' => 'timeUpdated',
        'date' => 'date',
        'tz' => 'timeZone',
        'details' => 'detailsTimeZone'
    ];

    protected $paramsDisableForCreate = [
        'details',
        'create_time',
        'image'
    ];

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var int|null
     */
    public $heartRate;

    /**
     * Blood pressure metric
     *
     * @var int|null
     */
    public $systolicPressure;

    /**
     * Blood pressure metric
     *
     * @var int|null
     */
    public $diastolicPressure;

    /**
     * Return endpoint part of request uri
     *
     * @return string
     */
    public function getEndPointName()
    {
        return 'cardiac_events';
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
        return AccessScope::CARDIAC_WRITE;
    }

    /**
     * Return action name part of request uri for view records' list
     *
     * @return string|null
     */
    public function getListActionName()
    {
        return 'cardiac_events';
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
}
