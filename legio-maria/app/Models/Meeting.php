<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ['meeting_date', 'topic', 'notes'];

    protected $casts = ['meeting_date' => 'date'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
