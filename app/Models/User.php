<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'address',
        'barangay',
        'latitude',
        'longitude',
        'e_library_card_id',
        'is_admin',
        'is_suspended',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_suspended' => 'boolean',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->e_library_card_id)) {
                $user->e_library_card_id = \Illuminate\Support\Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the book loans for the user.
     */
    public function bookLoans()
    {
        return $this->hasMany(BookLoan::class);
    }

    /**
     * Get the penalties for the user.
     */
    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    /**
     * Get the payments for the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the delivery schedules where user is the rider.
     */
    public function deliverySchedules()
    {
        return $this->hasMany(DeliverySchedule::class, 'rider_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get QR code for e-library card
     */
    public function getELibraryCardQrCode()
    {
        if (!$this->e_library_card_id) {
            return null;
        }

        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)
            ->generate($this->e_library_card_id);
    }
}
