<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;

class ProcessImageJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected int    $userId,
        protected string $imageName,
        protected string $uploadedImagePath
    )
    {
    }

    public function backoff(): array
    {
        return [5, 10, 20];
    }

    public function handle(): void
    {
        $image = app(ImageManager::class)->read($this->uploadedImagePath)->orient();

        $sizes = [
            'small' => 150,
            'medium' => 300,
            'large' => 600,
        ];

        $timestamp = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($sizes as $sizeName => $dimension) {
            $thumbnail = clone $image;

            $thumbnail->scale($dimension, $dimension)->resizeCanvas($dimension, $dimension);

            $thumbnail->text($timestamp, $thumbnail->width() - 10, $thumbnail->height() - 10, function (FontFactory $font) {
                $font->file(storage_path('fonts/Roboto/Roboto-Regular.ttf'));
                $font->size(14);
                $font->align('right');
                $font->valign('bottom');
            });

            Storage::put("thumbnails/{$sizeName}/" . $this->imageName . ".webp", $thumbnail->toWebp());

            Storage::put("thumbnails/{$sizeName}/" . $this->imageName . ".avif", $thumbnail->toAvif());
        }
    }
}
