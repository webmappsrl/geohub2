<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Wm\WmPackage\Model\User as ModelUser;

class User extends ModelUser
{
    use HasFactory, Notifiable;

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
    ];

    /**
     * This method checks if the user is admin
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * This method checks if the user is editor
     *
     * @return boolean
     */
    public function isEditor(): bool
    {
        if ($this->is_admin) {
            return false;
        }
        return $this->is_editor;
    }
    /**
     * This method checks if the user is contributor
     *
     * @return boolean
     */
    public function isContributor(): bool
    {
        if (!$this->is_editor && !$this->is_admin) {
            return true;
        }
        return false;
    }
    
    /**
     * This method returns the user role
     *
     * @return string
     */
    public function getRole(): string
    {
        if ($this->is_admin) {
            return 'admin';
        } elseif ($this->is_editor && !$this->is_admin) {
            return 'editor';
        }
        return 'contributor';
    }
}
