<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Renungan extends Model
{
    protected $table = 'renungans';

    protected $fillable = ['user_id', 'name', 'written_on', 'content'];

    protected function casts(): array
    {
        return [
            'written_on' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
