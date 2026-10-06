<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['visit_request_id', 'assigned_member_id', 'visit_date', 'reminder_sent_at', 'status'];

    protected $casts = ['visit_date' => 'date'];

    public function visitRequest()
    {
        return $this->belongsTo(VisitRequest::class);
    }

    public function assignedMember()
    {
        return $this->belongsTo(Member::class, 'assigned_member_id');
    }
}
