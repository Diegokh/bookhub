<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'year',
        'rating',
        'description',
        'cover_url',
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

    public function getTierAttribute(): string
    {
        if ($this->rating >= 9.0) return 'S';
        if ($this->rating >= 7.0) return 'A';
        if ($this->rating >= 5.0) return 'B';
        return 'C';
    }

    public function getTierColorAttribute(): string
    {
        return match($this->tier) {
            'S' => 'bg-amber-400 text-gray-900',
            'A' => 'bg-green-500 text-white',
            'B' => 'bg-blue-500 text-white',
            default => 'bg-gray-500 text-white',
        };
    }
}
