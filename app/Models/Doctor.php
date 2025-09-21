<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'salutation',
        'first_name',
        'last_name',
        'gender',
        'nationality',
        'email',
        'phone',
        'address',
        'address_2',
        'city',
        'state',
        'postal',
        'country',
        'medical_license_number',
        'medical_license_authority',
        'medical_license_country',
        'specialization',
        'sub_specialization',
        'experience',
        'clinic_name',
        'clinic_address',
        'clinic_address_2',
        'clinic_city',
        'clinic_state',
        'clinic_postal',
        'clinic_country',
        'position',
        'degree',
        'school_or_college',
        'date_of_graduation',
        'additional_certifications',
        'continuing_medical_education',
        'membership_in_medical_associations',
        'licensing_bodies',
        'willing_to_international_patients',
        'preferred_patient_countries',
        'preferred_communication',
        'virtual_consultations',
        'second_opinions',
        'treatments_list',
        'estimated_cost',
        'international_insurance',
        'payment_methods',
        'medical_license_path',
        'degree_certificates',
        'certifications_path',
        'resume_path',
        'profile_picture',
        'signature'
    ];

    protected $casts = [
        'preferred_communication' => 'array',
        'payment_methods' => 'array',
        'degree_certificates' => 'array',
        'date_of_graduation' => 'date'
    ];
}