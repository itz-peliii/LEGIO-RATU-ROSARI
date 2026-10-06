<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'category', 'amount', 'description', 'transaction_date', 'recorded_by'];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function recorder()
    {
        return $this->belongsTo(Member::class, 'recorded_by');
    }
}
