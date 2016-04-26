<?php

namespace JawboneApi\Interfaces;


interface EditableInterface extends ItemInterface
{
    /**
     * Return action name part of request uri for edit exist record
     *
     * @return string
     */
    public function getEditActionName();

    /**
     * Array of parameters for update record
     *
     * @return array
     */
    public function getParametersForUpdate();

    /**
     * Identifier of write permission
     *
     * All available identifiers specified in the JawboneApi\AccessScope class
     *
     * @return string
     */
    public function getWritePermissionName();
} 