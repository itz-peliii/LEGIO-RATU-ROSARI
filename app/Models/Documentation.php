<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    use HasFactory;

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
}