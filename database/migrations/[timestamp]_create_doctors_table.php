<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('salutation');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('nationality');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('address');
            $table->string('address_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal');
            $table->string('country');
            $table->string('medical_license_number');
            $table->string('medical_license_authority');
            $table->string('medical_license_country');
            $table->string('specialization');
            $table->string('sub_specialization');
            $table->integer('experience');
            $table->string('clinic_name');
            $table->string('clinic_address');
            $table->string('clinic_address_2')->nullable();
            $table->string('clinic_city');
            $table->string('clinic_state');
            $table->string('clinic_postal');
            $table->string('clinic_country');
            $table->string('position');
            $table->string('degree');
            $table->string('school_or_college');
            $table->date('date_of_graduation');
            $table->text('additional_certifications')->nullable();
            $table->string('continuing_medical_education')->nullable();
            $table->text('membership_in_medical_associations')->nullable();
            $table->text('licensing_bodies')->nullable();
            $table->string('willing_to_international_patients');
            $table->text('preferred_patient_countries')->nullable();
            $table->json('preferred_communication');
            $table->string('virtual_consultations');
            $table->string('second_opinions');
            $table->text('treatments_list');
            $table->text('estimated_cost');
            $table->string('international_insurance');
            $table->json('payment_methods');
            $table->string('medical_license_path');
            $table->json('degree_certificates');
            $table->string('certifications_path');
            $table->string('resume_path');
            $table->string('profile_picture');
            $table->text('signature');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}