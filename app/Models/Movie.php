<?php

namespace App\Models;

use App\Models\Review;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'director',
        'year',
        'score',
        'synopsis',
        'poster_url',
        'published',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function getTierAttribute()
    {
        if ($this->score >= 9.0) {
            return 'S';
        }

        if ($this->score >= 7.0) {
            return 'A';
        }

        if ($this->score >= 5.0) {
            return 'B';
        }

        return 'C';
    }
}