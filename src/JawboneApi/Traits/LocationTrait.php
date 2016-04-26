<?php

namespace JawboneApi\Traits;

trait LocationTrait
{
    /**
     * Latitude of location where cardiac event was logged
     *
     * @var float|null
     */
    public $placeLatitude;

    /**
     * Longitude of location where cardiac event was logged
     *
     * @var float|null
     */
    public $placeLongitude;

    /**
     * Accuracy of location where cardiac event was logged, in meters
     *
     * @var int|null
     */
    public $placeAccuracy;

    /**
     * Name of location where cardiac event was logged
     *
     * @var string|null
     */
    public $placeName;
} 