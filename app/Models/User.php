<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
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
        'password' => 'hashed',
    ];

    /**
     * Return the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return implode(' ', [$this->first_name, $this->middle_name, $this->last_name]);
    }

    /**
     * Wildcard search.
     */
    public function scopeWildCardSearch(Builder $query, string $wildcard): Builder
    {
        return $query->where(function ($query) use ($wildcard) {
            return $query->orWhereRaw('CONCAT_WS(\' \', first_name, middle_name, last_name) like ?', ['%' . $wildcard . '%'])
                ->orWhere('email', 'like', '%' . $wildcard . '%');
        });
    }
    
    /**
     * User has many conversation history records. 
     */
    public function conversationHistories()
    {
        return $this->hasMany(ConversationHistory::class);
    }
}
