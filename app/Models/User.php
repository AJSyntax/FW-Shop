<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Order;
use App\Models\SecurityQuestion;

class User extends Authenticatable implements MustVerifyEmail, ShouldQueue
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'salt',
        'plain_password',
        'hashed_password',
        'salted_hashed_password',
        'role', // Add role to fillable
        'security_question_1_id',
        'security_answer_1',
        'security_question_2_id',
        'security_answer_2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
        'salt',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
        ];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }

    /**
     * Check if the user has the 'admin' role.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user has the 'buyer' role.
     *
     * @return bool
     */
    public function isBuyer(): bool
    {
        return $this->role === 'buyer';
    }

    /**
     * Get the orders for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the first security question for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function securityQuestion1()
    {
        return $this->belongsTo(SecurityQuestion::class, 'security_question_1_id');
    }

    /**
     * Get the second security question for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function securityQuestion2()
    {
        return $this->belongsTo(SecurityQuestion::class, 'security_question_2_id');
    }

    /**
     * Check if the user has set up security questions.
     *
     * @return bool
     */
    public function hasSecurityQuestions(): bool
    {
        return !is_null($this->security_question_1_id) && !is_null($this->security_answer_1) &&
               !is_null($this->security_question_2_id) && !is_null($this->security_answer_2);
    }
}
