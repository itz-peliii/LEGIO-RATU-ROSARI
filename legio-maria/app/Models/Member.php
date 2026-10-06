<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'gender', 'join_date', 'status'];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function visitSchedules()
    {
        return $this->hasMany(VisitSchedule::class, 'assigned_member_id');
    }

    // Persentase kehadiran anggota ini dari seluruh pertemuan yang sudah tercatat
    public function attendancePercentage(): float
    {
        $total = $this->attendances()->count();
        if ($total === 0) return 0;
        $hadir = $this->attendances()->where('status', 'hadir')->count();
        return round(($hadir / $total) * 100, 1);
    }
}
