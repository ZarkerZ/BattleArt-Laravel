<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     * Default is 'users', but defining it explicitly is good practice.
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     * Laravel defaults to 'id', so we must override it.
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     * These match the $_POST variables from your register.php
     */
    protected $fillable = [
        'user_firstName',
        'user_middleName',
        'user_lastName',
        'user_email',
        'user_password',
        'user_userName',
        'user_dob',
        'user_type',
        'account_status',
        'user_profile_pic',
        'last_seen',
        // Add other columns here if you need to update them manually later
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'user_password',
        'remember_token',
    ];

    /**
     * CUSTOM TIMESTAMPS
     * Your DB uses 'joined_date' instead of 'created_at'.
     * Your DB does not seem to have an 'updated_at' column.
     */
    const CREATED_AT = 'joined_date';
    const UPDATED_AT = null; // Disable updated_at since it doesn't exist in your schema

    /**
     * OVERRIDE AUTH PASSWORD
     * Laravel looks for a 'password' column by default. 
     * We must tell it to return 'user_password' instead.
     */
    public function getAuthPassword()
    {
        return $this->user_password;
    }
}