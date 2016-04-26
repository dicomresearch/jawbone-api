<?php

namespace JawboneApi\Traits;

use JawboneApi\Items\ChartImage;
use JawboneApi\DataProvider;

trait ChartImageTrait
{
    protected $loadChartImageAction = 'image';

    protected $chartImage;

    public function getChartImage()
    {
        if (is_null($this->chartImage)) {
            $response = $this->getDataProvider()->sendRequest(
                DataProvider::REQUEST_GET,
                $this->getUser(),
                $this,
                $this->loadChartImageAction
            );
            $this->chartImage = new ChartImage($response->body);
        }
        return $this->chartImage;
    }
} 