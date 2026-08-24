<?php

namespace App\Models;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeviceAssignment extends Model
{
    protected $fillable = [
        'user_id', 'device_id', 'assigned_at', 'returned_at', 'status', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
    use HasFactory;

}
