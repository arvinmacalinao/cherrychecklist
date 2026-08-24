<?php

namespace App\Models;

use App\Models\EmployeeDeviceAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_type', 'brand', 'model', 'serial_number', 'imei', 'asset_tag', 'status', 'created_at', 'updated_at'
    ];

    public function assignments()
    {
        return $this->hasMany(EmployeeDeviceAssignment::class);
    }

    public function activeAssignment()
    {
        return $this->hasOne(EmployeeDeviceAssignment::class)
            ->where('status', 'active')
            ->with('user');
    }
}
