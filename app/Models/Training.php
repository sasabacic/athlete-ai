<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type',
        'distance',
        'duration',
        'training_date',

    ];

    public function user (){
        return $this->belongsTo(User::class);
    }
}
