<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasTimestamps;
    use SoftDeletes;

    protected $table = 'videos';

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $fillable = [
        'user_id', 'name', 'src', 'status'
    ];

    protected $attributes = [
        'status' => self::STATUS_UPLOAD
    ];

    const STATUS_UPLOAD = 'upload';
    const STATUS_PROCESS = 'process';
    const STATUS_PROCESSED = 'processed';
    const STATUS_ERROR = 'error';

    public function user() {
        return $this->hasOne(User::class, 'user_id');
    }
}
