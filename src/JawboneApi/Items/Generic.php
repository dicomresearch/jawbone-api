<?php

namespace JawboneApi\Items;


use JawboneApi\AccessScope;
use JawboneApi\Interfaces\AddableInterface;
use JawboneApi\Interfaces\CollectibleInterface;
use JawboneApi\Interfaces\EditableInterface;
use JawboneApi\Interfaces\RemovableInterface;
use JawboneApi\Interfaces\ViewableInterface;
use JawboneApi\Traits\AddableTrait;
use JawboneApi\Traits\AttachImageTrait;
use JawboneApi\Traits\EditableTrait;
use JawboneApi\Traits\LocationTrait;
use JawboneApi\Traits\TimestampTrait;

class Generic extends AbstractItem
    implements CollectibleInterface, AddableInterface, EditableInterface, RemovableInterface
{
    use TimestampTrait, AddableTrait, EditableTrait, LocationTrait, AttachImageTrait;

    protected $jsonToPropertyMap = [
        'xid' => 'xid',
        'title' => 'title',
        'verb' => 'verb',
        'attributes' => 'attributes',
        'note' => 'note',
        'place_lat' => 'placeLatitude',
        'place_lon' => 'placeLongitude',
        'place_acc' => 'placeAccuracy',
        'place_name' => 'placeName',
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
        'image',
        'time_updated'
    ];

    protected $paramsDisableForUpdate = [
        'details',
        'image',
        'time_created'
    ];

    /**
     * @var string|null
     */
    public $title;

    /**
     * @var string|null
     */
    public $verb;

    /**
     * @var string|null
     */
    public $note;

    /**
     * Array of custom attributes
     *
     * @var array|null
     */
    public $attributes;

    /**
     * Return endpoint part of request uri
     *
     * @return string
     */
    public function getEndPointName()
    {
        return 'generic_events';
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
        return AccessScope::GENERIC_WRITE;
    }

    /**
     * Return action name part of request uri for view records' list
     *
     * @return string|null
     */
    public function getListActionName()
    {
        return 'generic_events';
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

    public function setAttributesFromJson(\stdClass $attributes)
    {
        $this->attributes = (array)$attributes;
    }

    /**
     * @return string
     */
    public function getAttributesForJson()
    {
        return json_encode($this->attributes);
    }
}
