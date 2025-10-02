<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;

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
        // map 'name' attribute (used in forms/views) to database 'username' column via accessor/mutator
        'name',
        'username',
        'email',
        'password',
        'alamat',
        'foto',
        'role',
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
        ];
    }

    /**
     * Accessor to return username when code asks for ->name
     */
    public function getNameAttribute(): ?string
    {
        // Prefer 'username' column if it exists, otherwise fall back to 'name'
        if (Schema::hasColumn($this->getTable(), 'username')) {
            return $this->attributes['username'] ?? null;
        }
        return $this->attributes['name'] ?? null;
    }

    /** 
     * Mutator to set the appropriate column when code assigns ->name
     */
    public function setNameAttribute($value): void
    {
        if (Schema::hasColumn($this->getTable(), 'username')) {
            $this->attributes['username'] = $value;
            return;
        }
        $this->attributes['name'] = $value;
    }
}
