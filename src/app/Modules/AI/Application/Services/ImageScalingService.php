<?php

declare(strict_types=1);

namespace App\Modules\AI\Application\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageScalingService
{
    private const int MAX_WIDTH = 800;

    private const int JPEG_QUALITY = 80;

    public function scaleAndConvertToBase64(UploadedFile $image): string
    {
        $manager = new ImageManager(new Driver);

        $scaledImage = $manager->read($image)
            ->scale(width: self::MAX_WIDTH)
            ->toJpeg(quality: self::JPEG_QUALITY);

        return base64_encode($scaledImage->toString());
    }
}
