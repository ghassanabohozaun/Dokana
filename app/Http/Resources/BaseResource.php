<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request): array
    // {
    //     return parent::toArray($request);
    // }

    public function getImageUrl($path, $disk)
    {
        return $path ? Storage::disk($disk)->url($path) : null;
    }

    public function getImagesUrl($images, $disk)
    {
        return $images
            ? collect($images)
                ->map(function ($image) use ($disk) {
                    return $this->getImageUrl($image, $disk);
                })
                ->toArray()
            : null;
    }

    public function formatDateLocatiazied($data, $local, $format)
    {
        $carbon = Carbon::parse($data);
        if ($local == 'ar') {
            return $carbon->translatedFormat($format);
        }
        return $carbon->translatedFormat($format);
    }
}
