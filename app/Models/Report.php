<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $primaryKey = 'report_id';
    public $timestamps = false;
    protected $dates = ['created_at'];
    protected $fillable = ['reporter_user_id', 'type', 'target_id', 'reason', 'details', 'status', 'created_at'];
}
