<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Modules\Doctor;

class PageController extends Controller
{
    public function about_us()
    {
        return view("about-us");
    }

    public function services()
    {
        $doctor = new Doctor();
        list($doctors, $total, $pages) = $doctor->fetch();

        $specialities = DB::table('specialities')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('doctors')
                    ->whereRaw('JSON_CONTAINS(doctors.specialities, JSON_QUOTE(specialities.name))');
            })
            ->get();

        return view("services", [
            "doctors" => $doctors,
            "total" => $total,
            "pages" => $pages,
            "specialities" => $specialities
        ]);
    }

    public function home()
    {
        // $specialities = DB::table('specialities')
        //     ->whereExists(function ($query) {
        //         $query->select(DB::raw(1))
        //             ->from('doctors')
        //             ->whereRaw('JSON_CONTAINS(doctors.specialities, JSON_QUOTE(specialities.name))');
        //     })
        //     ->get();

        // $diseases = DB::table('diseases')->get();

        return view("home", [
            // "specialities" => $specialities,
            // "diseases" => $diseases
        ]);
    }
}
