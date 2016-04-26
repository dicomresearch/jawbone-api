<?php

namespace JawboneApi\Interfaces;


interface ItemInterface
{
    /**
     * Return endpoint part of request uri
     *
     * @return string
     */
    public function getEndPointName();

    /**
     * Populate record from array
     *
     * @param array $json
     */
    public function populateFromJson($json);

    /**
     * @return bool
     */
    public function isLoaded();

    /**
     * @param string $xid
     */
    public function setXid($xid);

    /**
     * @return string
     */
    public function getXid();
} 