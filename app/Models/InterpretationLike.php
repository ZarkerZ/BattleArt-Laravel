<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class InterpretationLike extends Model
{
    protected $primaryKey = 'like_id';
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $fillable = ['user_id', 'interpretation_id', 'created_at'];
}