<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Lesson;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\Testimonial;
use App\Models\TrainerDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = ['roleId'];

    public function getRoleAttribute()
    {
        return $this->attributes['role_id'];
    }

    protected $fillable = ['name', 'email', 'password', 'profile_url', 'profile_public_id', 'ph_no', 'date_of_birth'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'is_premium' => 'boolean',
        'is_first_time_appointment' => 'boolean',
        'date_of_birth' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $attributes = [
        'is_verified' => false,
        'is_premium' => false,
        'is_first_time_appointment' => true,
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user')
            ->withPivot('is_completed')
            ->withTimestamps();
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_user')
            ->withTimestamps();
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function trainerDetails()
    {
        return $this->hasOne(TrainerDetail::class, 'trainer_id');
    }
}
