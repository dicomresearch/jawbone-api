<?php

namespace JawboneApi\Interfaces;

use JawboneApi\DataProvider;


interface SelfLoadingInterface
{
    /**
     * @param DataProvider $dataProvider
     */
    public function setDataProvider(DataProvider $dataProvider);

    /**
     * @return DataProvider|null
     */
    public function getDataProvider();

    /**
     * @param \JawboneApi\User|null $user
     */
    public function setUser(\JawboneApi\User $user);

    /**
     * @return \JawboneApi\User
     * @throws \JawboneApi\JawboneApiException
     */
    public function getUser();

    /**
     * Self refresh data
     */
    public function selfRefresh();
} 