<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    use HasFactory;

    /**
     * Jumlah maksimal foto dokumentasi.
     */
    public const MAX = 10;

    protected $fillable = [
        'title',
        'image_path',
        'sort_order',
    ];

    /**
     * Type casting untuk kolom atribut.
     */
    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Urut sesuai pengaturan admin.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}