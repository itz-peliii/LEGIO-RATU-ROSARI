<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kegiatan extends Model
{
    protected $table = 'kegiatans';

    protected $fillable = ['title', 'description', 'location', 'held_at'];

    protected function casts(): array
    {
        return [
            'held_at' => 'datetime',
        ];
    }

    /** Anggota yang sudah menekan "Join" di kegiatan ini. */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kegiatan_user')->withTimestamps();
    }

    public function isPast(): bool
    {
        return $this->held_at->isPast();
    }

    /**
     * Status absensi seorang anggota untuk kegiatan ini.
     *
     * - hadir        : sudah join & kegiatan sudah lewat
     * - terdaftar    : sudah join & kegiatan belum berlangsung
     * - tidak_hadir  : belum join & kegiatan sudah lewat
     * - belum        : belum join & kegiatan belum berlangsung
     */
    public function attendanceStatus(bool $joined): string
    {
        return match (true) {
            $joined && $this->isPast() => 'hadir',
            $joined                    => 'terdaftar',
            $this->isPast()            => 'tidak_hadir',
            default                    => 'belum',
        };
    }
}
