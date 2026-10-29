<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Renungan extends Model
{
    /** Default masa tampil renungan setelah di-acc admin (2 minggu). */
    public const DEFAULT_DISPLAY_DAYS = 14;

    protected $table = 'renungans';

    protected $fillable = [
        'user_id', 'name', 'written_on', 'content', 'status', 'last_edited_at',
        'approved_at', 'approved_by', 'rejected_at', 'reject_reason',
        'hidden_at', 'hidden_by', 'expires_at', 'display_days',
    ];

    protected function casts(): array
    {
        return [
            'written_on'     => 'date',
            'last_edited_at' => 'datetime',
            'approved_at'    => 'datetime',
            'rejected_at'    => 'datetime',
            'hidden_at'      => 'datetime',
            'expires_at'     => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function hider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hidden_by');
    }

    /**
     * Renungan yang boleh dilihat anggota lain: sudah di-acc admin,
     * belum di-hide, dan belum lewat masa tampilnya.
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('status', 'approved')
            ->whereNull('hidden_at')
            ->where(fn (Builder $q) => $q
                ->whereNull('expires_at')
                ->orWhere('expires_at', '>', now()));
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isVisible(): bool
    {
        return $this->status === 'approved'
            && $this->hidden_at === null
            && ! $this->isExpired();
    }

    public function statusLabel(): string
    {
        return match (true) {
            $this->status === 'pending'  => 'Menunggu persetujuan',
            $this->status === 'rejected' => 'Ditolak',
            $this->hidden_at !== null    => 'Disembunyikan admin',
            $this->isExpired()           => 'Masa tampil berakhir',
            $this->expires_at !== null   => 'Tampil s/d '.$this->expires_at->locale('id')->translatedFormat('d M Y'),
            default                      => 'Tampil (tanpa batas waktu)',
        };
    }

    public function statusColor(): string
    {
        return match (true) {
            $this->status === 'pending'  => 'bg-amber-100 text-amber-800',
            $this->status === 'rejected' => 'bg-red-100 text-red-700',
            ! $this->isVisible()         => 'bg-slate-200 text-slate-600',
            default                      => 'bg-emerald-100 text-emerald-800',
        };
    }
}