<?php

namespace App\Traits;

use App\Models\Storage;

trait HasStorage
{
    /**
     * Get all files associated with this model.
     */
    public function files()
    {
        return $this->morphMany(Storage::class, 'model');
    }

    /**
     * Get images associated with this model.
     */
    public function images()
    {
        return $this->morphMany(Storage::class, 'model')
            ->where('collection', 'images');
    }

    /**
     * Get documents associated with this model.
     */
    public function documents()
    {
        return $this->morphMany(Storage::class, 'model')
            ->where('collection', 'documents');
    }

    /**
     * Add a file to the model.
     */
    public function addFile($file, $collection = 'default', $uploadedBy = null)
    {
        $fileName = $file->hashName();
        $originalName = $file->getClientOriginalName();
        $path = $file->store($this->getStoragePath($collection));

        return $this->files()->create([
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'collection' => $collection,
            'uploaded_by' => $uploadedBy ?? auth()->id()
        ]);
    }

    /**
     * Get the storage path for this model.
     */
    protected function getStoragePath($collection)
    {
        return strtolower(class_basename($this)) . 's/' . $collection;
    }
}
