<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class JSONStorageService
{
    protected string $directory = 'form_submissions';

    public function save(array $data, string $fileName): array
    {
        $data["created_at"] = now();
        Storage::makeDirectory($this->directory);

        $absoluteFilePath = sprintf("%s/%s.json", $this->directory, $fileName);

        if (Storage::exists($absoluteFilePath)) {

            $existing = Storage::get($absoluteFilePath);
            $contentAsArray = json_decode($existing, true) ?? [];
            $contentAsArray[] = $data;
            Storage::put($absoluteFilePath, json_encode($contentAsArray, JSON_PRETTY_PRINT));
            return $contentAsArray;
        } else {
            Storage::put($absoluteFilePath, json_encode([$data], JSON_PRETTY_PRINT));
            return [$data];
        }
    }

    public function read(string $fileName): array
    {
        $absoluteFilePath = sprintf("%s/%s.json", $this->directory, $fileName);

        if (!Storage::exists($absoluteFilePath)) {
            return [];
        }

        $content = Storage::get($absoluteFilePath);

        return json_decode($content, true) ?? [];
    }
}
