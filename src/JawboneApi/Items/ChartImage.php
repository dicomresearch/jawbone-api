<?php

namespace JawboneApi\Items;

/**
 * Class ChartImage
 *
 * Image object from binary content
 *
 * @package JawboneApi
 */
class ChartImage
{
    const FILE_EXTENSION = 'png';

    const CONTENT_TYPE_HEADER = 'Content-type: image/png';

    private $imageResource;

    /**
     * @param string $content Binary content of the image
     */
    public function __construct($content)
    {
        $this->imageResource = imagecreatefromstring($content);
    }

    /**
     * Sends the client a chart image
     */
    public function send()
    {
        header(static::CONTENT_TYPE_HEADER);
        imagepng($this->imageResource);
    }

    /**
     * @param string $targetDirectory
     * @param string $fileName
     */
    public function save($targetDirectory, $fileName)
    {
        imagepng($this->imageResource, $this->createFullImagePath($targetDirectory, $fileName));
    }

    protected function createFullImagePath($targetDirectory, $fileName)
    {
        return rtrim($targetDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName . '.' . static::FILE_EXTENSION;
    }
}