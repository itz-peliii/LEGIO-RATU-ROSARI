<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Legio extends Model
{
    protected $table = 'legios';

    protected $fillable = ['name', 'description', 'photo_path', 'sort_order'];
}
