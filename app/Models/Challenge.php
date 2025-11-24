<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;
    protected $primaryKey = 'challenge_id';

    // Disable updated_at, enable created_at with custom name?
    // Your DB has 'created_at', so default timestamps are okay,
    // but you don't have 'updated_at'.
    public $timestamps = false;
    protected $dates = ['created_at'];

    protected $fillable = ['user_id', 'challenge_name', 'challenge_description', 'category', 'original_art_filename', 'created_at'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function interpretations() {
        return $this->hasMany(Interpretation::class, 'challenge_id', 'challenge_id');
    }
}
