<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    //
    use HasFactory; //
    protected $fillable = ['name'];
    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function patrimonies(){
        return $this->hasMany(Patrimony::class);
    }
}
