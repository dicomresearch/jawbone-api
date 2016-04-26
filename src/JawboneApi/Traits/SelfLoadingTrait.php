<?php

namespace JawboneApi\Traits;

use JawboneApi\DataProvider;
use JawboneApi\JawboneApiException;


trait SelfLoadingTrait
{

    /**
     * @var DataProvider|null
     */
    private $dataProvider;

    /**
     * @var \JawboneApi\User|null
     */
    private $user;

    /**
     * @param DataProvider $dataProvider
     */
    public function setDataProvider(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return DataProvider
     * @throws \JawboneApi\JawboneApiException
     */
    public function getDataProvider()
    {
        if (is_null($this->dataProvider)) {
            throw new JawboneApiException('Data provider is not specified.');
        }
        return $this->dataProvider;
    }

    /**
     * @param \JawboneApi\User|null $user
     */
    public function setUser(\JawboneApi\User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \JawboneApi\User
     * @throws \JawboneApi\JawboneApiException
     */
    public function getUser()
    {
        if (is_null($this->user)) {
            throw new JawboneApiException('User is not specified.');
        }
        return $this->user;
    }

    /**
     * Self refresh data
     */
    public function selfRefresh()
    {
        $this->getDataProvider()->loadItem($this->getUser(), $this, true);
    }
} 