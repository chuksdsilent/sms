<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedPatientReferralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_referral', function (Blueprint $table) {
            $table->id();
            $table->string("trx_id");
            $table->string("referred_by");
            $table->timestamps();
            $table->foreignId('patients_id', 100)->constrained('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_referral');
    }
}
