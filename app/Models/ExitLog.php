<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scanned_by',
        'ip_address',
        'scanned_at',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
