<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patrimony extends Model
{
    //
    protected $fillable = [
        'code',
        'description',
        'image',
        'environment_id',
        'state_id',
    ];
    
    public function environment(){
        return $this->belongsTo(Environment::class);
    }

    public function state(){
        return $this->belongsTo(State::class);
    }
}
