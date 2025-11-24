<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $primaryKey = 'like_id';
    public $timestamps = false; // Your table uses 'created_at' but no updated_at
    protected $dates = ['created_at'];
    protected $fillable = ['user_id', 'challenge_id', 'created_at'];
}