<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = ['name', 'alias', 'hr_ref_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
