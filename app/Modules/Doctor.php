<?php

namespace App\Modules;

use DB;
use Storage;

class Doctor
{
    private $table = "doctors";
    public $id = 0;
    public $specialities = "";
    public $speciality = "";
    public $name = "";

    private function map($doctor)
    {
        $obj = [
            "id" => $doctor->id ?? 0,
            "user_id" => $doctor->user_id ?? 0,
            "name" => $doctor->name ?? "",
            "profile_image" => $doctor->profile_image ?? "",
            "services" => json_decode($doctor->services ?? "[]", true),
            "specialities" => json_decode($doctor->specialities ?? "[]", true),
            "location" => $doctor->location ?? "",
            "reviews" => (int) ($doctor->reviews ?? "0"),
            "ratings" => (double) ($doctor->ratings ?? "0"),
            "status" => $doctor->status ?? "",
            "fee" => (double) ($doctor->fee ?? "0"),
        ];

        if ($obj["profile_image"] && Storage::exists("public/" . $obj["profile_image"]))
        {
            $obj["profile_image"] = url("/storage/" . $obj["profile_image"]);
        }

        return (object) $obj;
    }

    public function fetch_by_user_id($user_id)
    {
        $user = DB::table("users")
            ->select("users.*", "doctors.services", "doctors.specialities", "doctors.fee")
            ->leftJoin("doctors", "doctors.user_id", "=", "users.id")
            ->where("users.type", "=", "doctor")
            ->where("users.id", "=", $user_id)
            ->first();

        if ($user == null)
        {
            return null;
        }

        return $this->map($user);
    }

    public function fetch()
    {
        $doctors = DB::table($this->table)
            ->select($this->table . ".*", "users.name", "users.profile_image")
            ->join("users", "users.id", "=", $this->table . ".user_id");

        if (!empty($this->speciality))
        {
            $doctors = $doctors->whereRaw(
                'JSON_CONTAINS(' . $this->table . '.specialities, ?)',
                [json_encode($this->speciality)]
            );
        }

        if (!empty($this->name))
        {
            $doctors = $doctors->where("users.name", "LIKE", "%" . $this->name . "%");
        }

        $doctors = $doctors->orderBy("id", "desc")
            ->paginate();

        $arr = [];
        foreach ($doctors as $doctor)
        {
            array_push($arr, $this->map($doctor));
        }
        return [ $arr, $doctors->total(), $doctors->lastPage() ];
    }
}