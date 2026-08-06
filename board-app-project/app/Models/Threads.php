<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threads extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'view_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
