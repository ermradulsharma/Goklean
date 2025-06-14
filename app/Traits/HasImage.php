<?php

/**
 * File name: HasImage.php
 * Last modified: 18/05/21, 11:03 PM
 * Author: NearCraft - https://codecanyon.net/user/nearcraft
 * Copyright (c) 2021
 */

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    /**
     * Update the model's image.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateImage(UploadedFile $photo, $directory = null): void
    {
        $directoryName = $directory ? $directory : 'image-uploads';
        tap($this->image_path, function ($previous) use ($photo, $directoryName) {
            $path = $photo->store($directoryName, ['disk' => $this->imageDisk()]);
            $this->forceFill([
                'image_path' => $path,
            ])->save();
            if ($previous && $previous !== $path) {
                Storage::disk($this->imageDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteImage(): void
    {
        if ($this->image_path) {
            Storage::disk($this->imageDisk())->delete($this->image_path);
            $this->forceFill([
                'image_path' => null,
            ])->save();
        }
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->image_path ? Storage::disk($this->imageDisk())->url($this->image_path) : $this->defaultImageUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultImageUrl(): string
    {
        return asset('images/logo.png');
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function imageDisk()
    {
        // return config('filesystems.profile_photos_disk', config('filesystems.default', 'public'));
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('filesystems.default', 'public');
    }
}
