<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'username',
        'date_of_birth',
        'gender',
        'phone',
        'is_active',
        'is_public',
        'is_admin',
        'is_fulltime_hire_ready',
        'is_freelance_hire_ready',
        'profile_picture_url',
        'cover_picture_url',
        'about_me',
        'headline',
        'navbar_bg_color',
        'navbar_text_color',
        'footer_bg_color',
        'footer_text_color',
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
    ];

    public function user_social_medias()
    {
        return $this->hasMany(UserSocialMedia::class);
    }

    public function user_skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function portofolios()
    {
        return $this->hasMany(Portofolio::class);
    }
}
