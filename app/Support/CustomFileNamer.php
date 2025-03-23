<?php

namespace App\Support;

use Illuminate\Support\Str;
use Spatie\MediaLibrary\Support\FileNamer\DefaultFileNamer;
use Spatie\MediaLibrary\Conversions\Conversion;

class CustomFileNamer extends DefaultFileNamer
{
    /**
     * Generate a slug from the original filename with no spaces and add a UUID.
     *
     * @param string $fileName
     * @return string
     */
    public function originalFileName(string $fileName): string
    {
        // Get the base filename without extension, convert to slug, remove spaces, and add UUID
        $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $slug = str_replace(' ', '', Str::slug($strippedFileName));
        return $slug . '-' . Str::uuid();
    }

    /**
     * Generate a slug from the filename for conversions with no spaces and add a UUID.
     *
     * @param string $fileName
     * @param \Spatie\MediaLibrary\Conversions\Conversion $conversion
     * @return string
     */
    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);
        // Convert to slug, remove spaces, add conversion name and UUID
        $slug = str_replace(' ', '', Str::slug($strippedFileName));
        return $slug . "-{$conversion->getName()}" . '-' . Str::uuid();
    }

    /**
     * Generate a slug from the filename for responsive images with no spaces and add a UUID.
     *
     * @param string $fileName
     * @return string
     */
    public function responsiveFileName(string $fileName): string
    {
        $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);
        // Convert to slug, remove spaces, and add UUID
        $slug = str_replace(' ', '', Str::slug($strippedFileName));
        return $slug . '-' . Str::uuid();
    }
}
