<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    // Define roles as a constant array
    public const ROLES = [
        1 => 'Admin',
        2 => 'Trainer',
        3 => 'Customer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'dob',
        'user_role',
        'description',
        'cost_per_month',
        'experience',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'subscription_details', // corrected typo in the attribute name
        'bookings',
        'photo',
    ];

    /**
     * Get the user's subscription details attribute.
     *
     * @return array
     */
    public function getSubscriptionDetailsAttribute()
    {
        $data = [];
        $subscription = $this->subscriptions()->latest()->first();
        if ($subscription) {
            $data['total_days'] = Carbon::parse($subscription->valid_from)->diffInDays(Carbon::parse($subscription->valid_till));
            $data['total_remaining_days'] = Carbon::parse($subscription->valid_till)->diffInDays(Carbon::now());
            $data['start_date'] = Carbon::parse($subscription->valid_from)->format('d M Y');
            $data['end_date'] = Carbon::parse($subscription->valid_till)->format('d M Y');
        }
        return $data;
    }

    /**
     * Get the user's subscriptions.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the user's bookings attribute.
     *
     * @return mixed
     */
    public function getBookingsAttribute()
    {
        if ($this->user_role == 2) {
            return Booking::where('trainer_id', $this->getKey())->paginate(20);
        } elseif ($this->user_role == 3) {
            return Booking::where('client_id', $this->getKey())->paginate(20);
        } else {
            return Booking::paginate(20);
        }
    }

    /**
     * Get the user's photo attribute.
     *
     * @return string
     */
    public function getPhotoAttribute()
    {
        if ($this->hasMedia()) {
            return $this->getFirstMediaUrl() ?? asset('images/default-product.png');
        }
        return asset('images/default-product.png');
    }
}
