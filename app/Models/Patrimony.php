<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrimony extends Model
{
    //
    use HasFactory; //
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
