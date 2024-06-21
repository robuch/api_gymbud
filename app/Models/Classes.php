<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'price',
        'location',
        'image',
        'instructor_id',
        'status',
        'type_id',
        'category_id',
    ];

    protected function image(): Attribute{
        return Attribute::make(
            get: fn ($image) => asset('storage/'.$image),
        );
    }

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn ($time) => Carbon::createFromDate($time)->format('H:i'),
        );
    }

    public function instructor():BelongsTo
    {
        return $this->belongsTo(instructor::class);
    }

    public function type():BelongsTo
    {
        return $this->belongsTo(type::class);
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(category::class);
    }

}
