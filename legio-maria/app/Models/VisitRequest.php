<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitRequest extends Model
{
    use HasFactory;

    protected $fillable = ['requested_by', 'beneficiary_name', 'address', 'category', 'notes', 'status'];

    public function requester()
    {
        return $this->belongsTo(Member::class, 'requested_by');
    }

    public function schedule()
    {
        return $this->hasOne(VisitSchedule::class);
    }
}
