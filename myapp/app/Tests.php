<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tests extends Model
{
    protected $guarded = [];

    public function patients()
    {
        return $this->hasMany(Patients::class);
    }
    public function medicals()
    {
        return $this->hasMany(Medicals::class);
    }
}
