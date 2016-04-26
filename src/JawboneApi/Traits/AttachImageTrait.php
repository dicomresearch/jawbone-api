<?php

namespace JawboneApi\Traits;

use JawboneApi\DataProvider;

trait AttachImageTrait
{
    /**
     * Uri of the attached image of this event
     *
     * @var string|null
     */
    public $imageUri;

    /**
     * Url of the image of this event for attaching
     *
     * @var string|null
     */
    public $imageUrl;

    public function getFullAttachedImageUrl()
    {
        if (!is_null($this->imageUri)) {
            $url = DataProvider::HTTP . $this->getDataProvider()->getDomainName() . '/' . ltrim($this->imageUri, '/');
        } else {
            $url = null;
        }
        return $url;
    }
} 