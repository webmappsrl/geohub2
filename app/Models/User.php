<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Nova\Auth\Impersonatable;
use Wm\WmPackage\Model\User as ModelUser;



class User extends ModelUser
{
    use HasFactory, Notifiable, Impersonatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
        'role' => UserRole::class,
    ];

    /**
     * This method checks if the user is admin
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return $this->role == UserRole::Admin->value;
    }

    /**
     * This method checks if the user is editor
     *
     * @return boolean
     */
    public function isEditor(): bool
    {
        return $this->role == UserRole::Editor->value;

    }
    /**
     * This method checks if the user is contributor
     *
     * @return boolean
     */
    public function isContributor(): bool
    {
        return $this->role == UserRole::Contributor->value;
    }

    /**
     * This method returns the user role
     *
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }
    // Relationship with  EcTracks

    public function ecTracks()
    {
        return $this->hasMany(EcTrack::class);
    }

    // Relationship with EcPois

    public function ecPois()
    {
        return $this->hasMany(EcPoi::class);
    }

    // Impersonate user

    public function canImpersonate()
    {
        if ($this->isAdmin()) return true;
        return false;
    }
    public function canBeImpersonated()
    {
        if (!$this->isAdmin()) return true;
        return false;
    }
}
