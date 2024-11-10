<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
