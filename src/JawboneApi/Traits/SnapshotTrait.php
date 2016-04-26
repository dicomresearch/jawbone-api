<?php

namespace JawboneApi\Traits;

use JawboneApi\DataProvider;

trait SnapshotTrait
{
    protected $loadSnapshotInformationAction = "snapshot";

    /**
     * @var array
     */
    protected $snapshotInformation;

    /**
     * Return array of [timestamp, parameter value] during the day
     *
     * @return array
     */
    public function getSnapshotInformation()
    {
        if (is_null($this->snapshotInformation)) {
            $response = $this->getDataProvider()->sendRequest(
                DataProvider::REQUEST_GET,
                $this->getUser(),
                $this,
                $this->loadSnapshotInformationAction
            );
            $this->snapshotInformation = $this->getDataProvider()->getItemDataFromResponse($response);
        }
        return $this->snapshotInformation;
    }
} 