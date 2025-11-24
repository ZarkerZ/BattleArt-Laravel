<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'notification_id';
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $fillable = ['recipient_user_id', 'sender_user_id', 'type', 'target_id', 'target_parent_id', 'is_read', 'created_at'];
}