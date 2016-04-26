<?php

namespace JawboneApi\Interfaces;


interface AddableInterface extends ItemInterface
{
    /**
     * Return action name part of request uri for add new record
     *
     * @return string
     */
    public function getAddActionName();

    /**
     * Array of parameters for new record
     *
     * @return array
     */
    public function getParametersForCreate();

    /**
     * Identifier of write permission
     *
     * All available identifiers specified in the JawboneApi\AccessScope class
     *
     * @return string
     */
    public function getWritePermissionName();
} 