<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageManagerUtils
{
    /**
     * Handle single image upload without resizing.
     *
     * @param mixed $image
     * @param string $path
     * @param string $disk
     * @param string|null $file_name
     * @return string
     */
    public function uploadSingleImage($path, $image, $disk, $file_name = null)
    {
        if (!$file_name) {
            $file_name = $this->generateImageName($image);
        }

        $this->storeInLocal($image, $path, $file_name, $disk);
        return $file_name;
    }

    /**
     * Handle single file upload (PDF, Docs, etc.).
     *
     * @param mixed $file
     * @param string $path
     * @param string $disk
     * @param string|null $file_name
     * @return string
     */
    public function uploadFile($path, $file, $disk, $file_name = null)
    {
        if (!$file_name) {
            $file_name = $this->generateImageName($file); // Using same logic for name generation
        }

        $this->storeInLocal($file, $path, $file_name, $disk);
        return $file_name;
    }

    /**
     * Upload and resize image.
     *
     * @param mixed $image
     * @param string $path
     * @param string $disk
     * @param int $width
     * @param int $height
     * @param string|null $file_name
     * @return string
     */
    public function uploadAndResizeImage($path, $image, $disk, $width, $height, $file_name = null)
    {
        if (!$file_name) {
            $file_name = $this->generateImageName($image);
        }

        $manager = new ImageManager(new Driver());
        $img = $manager->read($image->getRealPath());
        $img->resize($width, $height);

        $encodedImage = $img->encode();

        // Ensure path ends with slash
        $fullPath = $path ? rtrim($path, '/') . '/' . $file_name : $file_name;
        
        Storage::disk($disk)->put($fullPath, $encodedImage);

        return $file_name;
    }

    /**
     * Bulk upload images for a model.
     *
     * @param array $images
     * @param mixed $model
     * @param string $disk
     * @return void
     */
    public function uploadImages($images, $model, $disk)
    {
        foreach ($images as $image) {
            $file_name = $this->generateImageName($image);
            $this->storeInLocal($image, '/', $file_name, $disk);
            $model->images()->create([
                'file_name' => $file_name,
            ]);
        }
    }

    /**
     * Remove file from storage.
     *
     * @param string $file_name
     * @param string $disk
     * @return void
     */
    public function removeImageFromLocal($file_name, $disk)
    {
        if ($file_name && Storage::disk($disk)->exists($file_name)) {
            Storage::disk($disk)->delete($file_name);
        }
    }

    /**
     * Generate a unique image name.
     *
     * @param mixed $image
     * @return string
     */
    public function generateImageName($image)
    {
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = preg_replace('/[^\p{L}\p{N}\-_]/u', '', str_replace(' ', '_', $originalName));
        if (empty($safeName)) {
            $safeName = 'file';
        }
        return $safeName . '_' . time() . '.' . $image->getClientOriginalExtension();
    }

    /**
     * Generate a formatted file name.
     */
    public function generateFileName($file, $employeeName, $month, $year)
    {
        $extension = $file->getClientOriginalExtension();
        $cleanName = Str::slug($employeeName, '_');
        return $cleanName . '_' . $month . '_' . $year . '_' . time() . '.' . $extension;
    }

    /**
     * Internal helper to store file.
     */
    private function storeInLocal($file, $path, $file_name, $disk)
    {
        $file->storeAs($path, $file_name, ['disk' => $disk]);
    }

    // Deprecated method name mapping for backward compatibility if needed
    public function saveResizeImage($image, $disk, $width, $height)
    {
        return $this->uploadAndResizeImage('', $image, $disk, $width, $height);
    }
}
