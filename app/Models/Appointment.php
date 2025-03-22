<?php

namespace App\Models;

use App\Enums\AppointmentStatus;
use Spatie\EloquentSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model implements Sortable
{
    use HasFactory;
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'order_column',
        'sort_when_creating' => true,
    ];
    
    protected $casts = [
        'status' => AppointmentStatus::class,
        'date' => 'datetime',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id')
            ->whereHas('role', function ($query) {
                $query->where('name', 'doctor');
            });
    }

    public function scopeNew(Builder $query): void
    {
        $query->whereStatus(AppointmentStatus::Created);
    }

    /**
     * Get all of the appointments' notes.
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    
}
