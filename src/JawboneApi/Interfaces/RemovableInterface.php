<?php

namespace JawboneApi\Interfaces;


interface RemovableInterface extends ItemInterface
{
    /**
     * Return action name part of request uri for remove exist record
     *
     * @return string
     */
    public function getRemoveActionName();

    /**
     * Identifier of write permission
     *
     * All available identifiers specified in the JawboneApi\AccessScope class
     *
     * @return string
     */
    public function getWritePermissionName();
} 