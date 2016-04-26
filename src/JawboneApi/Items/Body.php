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

class Body extends AbstractItem implements CollectibleInterface, ViewableInterface, AddableInterface, RemovableInterface
{
    use TimestampTrait, AddableTrait, LocationTrait, AttachImageTrait;

    protected $jsonToPropertyMap = [
        'xid' => 'xid',
        'title' => 'title',
        'place_lat' => 'placeLatitude',
        'place_lon' => 'placeLongitude',
        'place_acc' => 'placeAccuracy',
        'place_name' => 'placeName',
        'type' => 'type',
        'note' => 'note',
        'lean_mass' => 'leanMass',
        'weight' => 'weight',
        'body_fat' => 'bodyFat',
        'bmi' => 'bmi',
        'image' => 'imageUri',
        'image_url' => 'imageUrl',
        'time_created' => 'timeCreated',
        'time_updated' => 'timeUpdated',
        'date' => 'date',
        'tz' => 'timeZone',
        'details' => 'detailsTimeZone'
    ];

    protected $paramsDisableForCreate = [
        'details',
        'time_updated',
        'image'
    ];

    protected $paramsDisableForUpdate = [
        'details',
        'time_created',
        'image'
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
     * @var string|null
     */
    public $note;

    /**
     * @var integer|null
     */
    public $leanMass;

    /**
     * @var integer|null
     */
    public $weight;


    /**
     * @var integer|null
     */
    public $bodyFat;


    /**
     * @var integer|null
     */
    public $bmi;

    /**
     * Return endpoint part of request uri
     *
     * @return string
     */
    public function getEndPointName()
    {
        return 'body_events';
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
        return AccessScope::WEIGHT_WRITE;
    }

    /**
     * Return action name part of request uri for view records' list
     *
     * @return string|null
     */
    public function getListActionName()
    {
        return 'body_events';
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
