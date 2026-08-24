<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Company;
use App\Models\Designation;
use App\Models\EmployeeDeviceAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'employee_id',
        'password',
        'username',
        'designation_id',
        'company_id',
        'qr_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeSearch($query, $value): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('email', 'like', "%{$value}%");
    }


    public function deviceAssignments()
    {
        return $this->hasMany(EmployeeDeviceAssignment::class);
    }

    public function activeDevices()
    {
        return $this->hasMany(EmployeeDeviceAssignment::class)
            ->where('status', 'active')
            ->with('device');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}
