<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $primaryKey = 'comment_id';
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $fillable = ['user_id', 'challenge_id', 'comment_text', 'created_at'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    
    public function challenge() {
        return $this->belongsTo(Challenge::class, 'challenge_id', 'challenge_id');
    }
}