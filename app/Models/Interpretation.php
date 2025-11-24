<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Interpretation extends Model
{
    protected $primaryKey = 'interpretation_id';
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $fillable = ['challenge_id', 'user_id', 'description', 'art_filename', 'created_at'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function challenge() {
        return $this->belongsTo(Challenge::class, 'challenge_id', 'challenge_id');
    }

    public function likes() {
        return $this->hasMany(InterpretationLike::class, 'interpretation_id', 'interpretation_id');
    }
}