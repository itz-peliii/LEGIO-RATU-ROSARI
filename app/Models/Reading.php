<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Reading extends Model
{
    protected $fillable = [
        'title', 'file_path', 'total_pages',
        'start_date', 'start_page', 'pages_per_week',
        'is_active', 'uploaded_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'is_active'  => 'boolean',
    ];

    /* ===================== Relasi ===================== */

    public function overrides()
    {
        return $this->hasMany(ReadingOverride::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /* ===================== Helper jadwal ===================== */

    /** URL publik PDF (butuh `php artisan storage:link`). */
    public function fileUrl(): string
    {
        return asset('storage/' . $this->file_path);
    }

    /** Tanggal awal minggu ke-$w (0 = minggu pertama). */
    public function weekStartFor(int $w): Carbon
    {
        return $this->start_date->copy()->startOfDay()->addWeeks(max(0, $w));
    }

    /** Nomor minggu (0-based) untuk sebuah tanggal. */
    public function weekIndexFor($date): int
    {
        $date = Carbon::parse($date)->startOfDay();
        $days = (int) $this->start_date->copy()->startOfDay()->diffInDays($date, false);
        return $days <= 0 ? 0 : intdiv($days, 7);
    }

    /** Nomor minggu untuk hari ini. */
    public function currentWeekIndex(): int
    {
        return $this->weekIndexFor(now());
    }

    /** Halaman hasil hitungan otomatis untuk minggu ke-$w. */
    public function autoPageFor(int $w): int
    {
        $page = $this->start_page + max(0, $w) * $this->pages_per_week;
        $page = max(1, $page);

        if ($this->total_pages) {
            $page = min($page, $this->total_pages);
        }

        return $page;
    }

    /** Override untuk minggu ke-$w, kalau ada. */
    public function overrideFor(int $w): ?ReadingOverride
    {
        $target = $this->weekStartFor($w)->toDateString();

        return $this->overrides->first(function ($o) use ($target) {
            return Carbon::parse($o->week_start)->toDateString() === $target;
        });
    }

    /** Halaman final (override kalau ada, kalau tidak otomatis). */
    public function pageFor(int $w): int
    {
        return $this->overrideFor($w)?->page ?? $this->autoPageFor($w);
    }

    /** Minggu terjauh yang masih masuk akal untuk dibuka anggota. */
    public function lastBrowsableWeek(): int
    {
        $weeks = [$this->currentWeekIndex()];

        if ($this->total_pages && $this->pages_per_week > 0) {
            $weeks[] = intdiv(max(0, $this->total_pages - $this->start_page), $this->pages_per_week);
        }

        foreach ($this->overrides as $o) {
            $weeks[] = $this->weekIndexFor($o->week_start);
        }

        return max($weeks);
    }

    /** Ringkasan satu minggu untuk dipakai di view. */
    public function weekInfo(int $w): array
    {
        $override = $this->overrideFor($w);
        $start    = $this->weekStartFor($w);

        return [
            'index'       => $w,
            'number'      => $w + 1,
            'start'       => $start,
            'end'         => $start->copy()->addDays(6),
            'page'        => $override?->page ?? $this->autoPageFor($w),
            'auto_page'   => $this->autoPageFor($w),
            'is_override' => (bool) $override,
            'note'        => $override?->note,
            'override'    => $override,
            'is_current'  => $w === $this->currentWeekIndex(),
        ];
    }
}
