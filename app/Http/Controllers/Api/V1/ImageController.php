<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Image\ImageUploadRequest;
use App\Jobs\ProcessImageJob;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageController extends Controller
{
    protected ImageManager $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function index()
    {
        return response()->json([
            'images' => auth()->user()->images()->get()
        ]);
    }

    public function store(ImageUploadRequest $request)
    {
        $uploadedImages = $request->all()['images'];

        foreach ($uploadedImages as $uploadedImage) {
            $image = app(ImageManager::class)->read($uploadedImage->getRealPath())->orient();

            $imagePath = md5(time() . rand(0, 99999)) . $uploadedImage->getClientOriginalName();

            $imageName = str_replace('.', '_', $imagePath);

            $originalUploadPath = 'originals/' . basename($imageName) . "." . $uploadedImage->getClientOriginalExtension();

            Image::query()->create([
                'user_id' => auth()->id(),
                'name' => $imageName,
                'path' => $originalUploadPath,
                'metadata' => $image->exif()->toArray(),
            ]);

            Storage::disk('minio')->put($originalUploadPath, $image->encode());

            $job = new ProcessImageJob(
                auth()->id(),
                $imageName,
                Storage::disk('minio')->path($originalUploadPath),
                [
                    'small' => 150,
                    'medium' => 300,
                    'large' => 600,
                ]
            );

            $job->onQueue('process_images');

            dispatch($job);
        }

        return response()->json([
            'msg' => 'processing images please wait',
        ]);
    }
}
