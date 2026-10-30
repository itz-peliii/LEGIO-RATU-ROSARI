<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingOverride extends Model
{
    protected $fillable = ['reading_id', 'week_start', 'page', 'note'];

    protected $casts = [
        'week_start' => 'date',
    ];

    public function reading()
    {
        return $this->belongsTo(Reading::class);
    }
}
