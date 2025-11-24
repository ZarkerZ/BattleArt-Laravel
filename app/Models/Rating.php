<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $primaryKey = 'rating_id';
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $fillable = ['rated_user_id', 'rater_user_id', 'rating_value', 'created_at'];
}