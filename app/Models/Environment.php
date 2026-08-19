<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    //
    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function patrimonies(){
        return $this->hasMany(Patrimony::class);
    }
}
