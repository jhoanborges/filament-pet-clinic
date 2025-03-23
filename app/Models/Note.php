<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Note extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    /**
     * Get the parent notable model (pet or appointment).
     */
    public function notable(): MorphTo
    {
        return $this->morphTo();
    }
}
