<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Company;
use App\Models\Designation;
use App\Models\EmployeeDeviceAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
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

    protected static function booted(): void
    {
        // Every locally-created user (HR sync's first insert, or an admin
        // manually creating one) gets a permanent QR credential immediately.
        // updateOrCreate() on an existing row only fires "updating", never
        // "creating", so this never touches an already-issued token.
        static::creating(function (User $user) {
            if (! $user->qr_token) {
                $user->qr_token = (string) Str::uuid();
            }
        });
    }

    public function scopeSearch($query, $value): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('email', 'like', "%{$value}%");
    }

    /**
     * Rotate this user's QR credential. The previous token stops working
     * the instant this is saved, since lookups are always by exact match.
     */
    public function regenerateQrToken(): string
    {
        $this->forceFill(['qr_token' => (string) Str::uuid()])->save();

        return $this->qr_token;
    }

    public function qrCodeUrl(): string
    {
        return route('qr.show', ['token' => $this->qr_token]);
    }

    /**
     * Appends an incrementing numeric suffix until $base is free. Shared by
     * manual admin creation and the HR sync — HR's own per-employee username
     * isn't guaranteed globally unique, so the sync needs the same
     * collision handling the "Create User" form already does.
     */
    public static function uniqueUsername(string $base, ?int $excludeUserId = null): string
    {
        $username = $base;
        $counter = 1;

        while (
            static::where('username', $username)
                ->when($excludeUserId, fn ($query) => $query->where('id', '!=', $excludeUserId))
                ->exists()
        ) {
            $username = $base.$counter;
            $counter++;
        }

        return $username;
    }

    public function dashboardRedirect(): \Illuminate\Http\RedirectResponse
    {
        if ($this->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($this->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }

        return redirect('/');
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
