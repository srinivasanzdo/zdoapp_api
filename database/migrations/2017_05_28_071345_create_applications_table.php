<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('gender');
            $table->string('nric');
            $table->date('dob');
            $table->string('occupation');
            $table->bigInteger('contact');
            $table->string('nok_relationship');
            $table->bigInteger('contact_nok');
            $table->string('shieldplan_paplan_date');
            $table->string('exclusionplan');
            $table->string('preexisting_condition');
            $table->string('high_blood_pressure');
            $table->string('diabetes');
            $table->string('high_cholesterol');
            $table->date('diagnosisdate');
            $table->string('pending_claims');
            $table->string('other_entitlement_policies');
            $table->string('cause_complaint');
            $table->string('signs_symptoms');
            $table->date('preffereddate');
            $table->integer('status')->unsigned();
            $table->integer('createdby')->unsigned();
            $table->longText('remark');
            $table->longText('amend_remark');
            $table->longText('reject_remark');
            $table->timestamps();
            $table->foreign('status')->references('id')->on('statuss');
            $table->foreign('createdby')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
