<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
    public function patrimonies() {
        return $this->hasMany(Patrimony::class);
    }
}
