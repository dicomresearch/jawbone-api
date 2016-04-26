<?php

namespace JawboneApi\Interfaces;


interface ViewableInterface extends ItemInterface
{
    /**
     * Return action name part of request uri for view single record
     *
     * @return string|null
     */
    public function getViewActionName();
} 