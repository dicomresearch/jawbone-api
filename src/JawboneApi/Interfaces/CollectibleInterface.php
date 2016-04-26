<?php

namespace JawboneApi\Interfaces;


interface CollectibleInterface extends ItemInterface
{
    /**
     * Return action name part of request uri for view records' list
     *
     * @return string
     */
    public function getListActionName();
} 