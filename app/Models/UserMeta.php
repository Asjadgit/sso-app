<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $fillable = ['user_id', 'meta_key', 'meta_value'];


    // Relationship with User
    public function user()
    {
        return $this->belongsTo(CentralUser::class);
    }
}
