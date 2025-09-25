<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Modules\Doctor;

class UpdateSchedule extends Controller
{
    public function index(Request $request)
    {
        $id = request()->id ?? 0;

        $doctor = new Doctor();
        $user = $doctor->fetch_by_user_id($id);

        if ($user == null)
        {
            abort(404);
        }

        // dd($user);

        /*$specialities = [
            (object) [
                "id" => 1,
                "name" => "Physiologist",
                "services" => [
                    (object) ["id" => 1, "speciality_id" => 1, "name" => "Regular Check-up", "price" => 3000],
                    (object) ["id" => 2, "speciality_id" => 1, "name" => "Therapy Session", "price" => 2000],
                    (object) ["id" => 3, "speciality_id" => 1, "name" => "ADHD Therapy", "price" => 2000],
                    (object) ["id" => 4, "speciality_id" => 1, "name" => "Mindfulness (Program 10 Days)", "price" => 15000],
                    (object) ["id" => 5, "speciality_id" => 1, "name" => "Insomnia Routines Consultancy", "price" => 2000],
                    (object) ["id" => 6, "speciality_id" => 1, "name" => "Stress Management Checkup", "price" => 1000],
                ]
            ],
            (object) [
                "id" => 2,
                "name" => "Cardiologist",
                "services" => [
                    (object) ["id" => 7, "speciality_id" => 2, "name" => "Heart Check-up", "price" => 5000],
                    (object) ["id" => 8, "speciality_id" => 2, "name" => "ECG Test", "price" => 1500],
                    (object) ["id" => 9, "speciality_id" => 2, "name" => "Blood Pressure Monitoring", "price" => 1000],
                ]
            ],
            (object) [
                "id" => 3,
                "name" => "Dermatologist",
                "services" => [
                    (object) ["id" => 10, "speciality_id" => 3, "name" => "Skin Check-up", "price" => 2500],
                    (object) ["id" => 11, "speciality_id" => 3, "name" => "Acne Treatment", "price" => 4000],
                    (object) ["id" => 12, "speciality_id" => 3, "name" => "Allergy Test", "price" => 3500],
                ]
            ],
            (object) [
                "id" => 4,
                "name" => "Neurologist",
                "services" => [
                    (object) ["id" => 13, "speciality_id" => 4, "name" => "Migraine Consultancy", "price" => 3000],
                    (object) ["id" => 14, "speciality_id" => 4, "name" => "Neuro Therapy", "price" => 6000],
                ]
            ],
        ];*/

        $services = DB::table("services")
            ->orderBy("name", "asc")
            ->get();

        $specialities = DB::table("specialities")
            ->orderBy("name", "asc")
            ->get();

        return view("doctors/schedules/update/update", compact('services', 'specialities', 'user', 'id'));
    }
}