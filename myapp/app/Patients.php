<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    protected $guarded = [];

    public function medicals()
    {
        return $this->hasMany(Medicals::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposits::class);
    }


    public function patient_referral()
    {
        return $this->hasMany(PatientReferral::class);
    }

    public function tests()
    {
        return $this->hasMany(Tests::class);
    }
}
