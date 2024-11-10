<?php

namespace App\Services\Image;

use Intervention\Image\ImageManager;

class ImageService
{
    public function __construct(protected ImageManager $imageManager)
    {

    }
}
