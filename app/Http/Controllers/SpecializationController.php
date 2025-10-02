<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;

class SpecializationController extends Controller
{
    public function admin_index()
    {
        super_admin_auth();
        
        $search = request()->search ?? "";
        $time_zone = request()->time_zone ?? "";
        if (!empty($time_zone))
        {
            date_default_timezone_set($time_zone);
        }

        $specialities = DB::table("specialities");

        if (!empty($search))
        {
            $specialities = $specialities->where(function ($query) use ($search) {
                $query->where("name", "LIKE", "%" . $search . "%");
            });
        }

        $specialities = $specialities->orderBy("id", "desc")
            ->paginate();

        $total = $specialities->total();

        return view("admin/specialities/index", [
            "specialities" => $specialities,
            "total" => $total,
            "search" => $search,
            "pagination" => $specialities->withPath(url("/admin/specialities"))->links("pagination::bootstrap-5")->render()
        ]);
    }

    public function add()
    {
        super_admin_auth();

        if (request()->isMethod("post"))
        {
            $validator = Validator::make(request()->all(), [
                "name" => "required"
            ]);

            if (!$validator->passes() && count($validator->errors()->all()) > 0)
            {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->all()[0]
                ]);
            }

            $name = request()->name ?? "";
            $icon = request()->icon ?? "";

            $speciality = DB::table("specialities")
                ->where("name", "=", $name)
                ->first();

            if ($speciality != null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Speciality already exists."
                ]);
            }

            $speciality_id = DB::table("specialities")
                ->insertGetId([
                    "name" => $name,
                    "icon" => $icon,
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            return response()->json([
                "status" => "success",
                "message" => "Speciality has been added."
            ]);
        }

        return view("admin/specialities/add");
    }

    public function update()
    {
        super_admin_auth();

        $validator = Validator::make(request()->all(), [
            "id" => "required",
            "name" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $id = request()->id ?? 0;
        $name = request()->name ?? "";
        $icon = request()->icon ?? "";

        $speciality = DB::table("specialities")
            ->where("id", "=", $id)
            ->first();

        if ($speciality == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Speciality not found."
            ]);
        }

        DB::table("specialities")
            ->where("id", "=", $speciality->id)
            ->update([
                "name" => $name,
                "icon" => $icon,
                "updated_at" => now()->utc()
            ]);

        DB::table("doctors")
            ->whereRaw("JSON_CONTAINS(specialities, '\"" . ($speciality->name ?? "") . "\"')")
            ->update([
                "specialities" => DB::raw("
                    JSON_SET(
                        specialities,
                        JSON_UNQUOTE(
                            JSON_SEARCH(specialities, 'one', '" . ($speciality->name ?? "") . "')
                        ),
                        '" . $name . "'
                    )
                ")
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Speciality has been updated."
        ]);
    }

    public function destroy()
    {
        super_admin_auth();

        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $id = request()->id ?? 0;

        $speciality = DB::table("specialities")
            ->where("id", "=", $id)
            ->first();

        if ($speciality == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Speciality not found."
            ]);
        }

        $doctors = DB::table("doctors")
            ->select("id", "specialities")
            ->whereRaw("JSON_CONTAINS(specialities, '\"" . $speciality->name . "\"')")
            ->get();

        foreach ($doctors as $doctor)
        {
            $specialities = json_decode($doctor->specialities ?? "[]", true);

            foreach ($specialities as $key => $value)
            {
                if ($value == $speciality->name)
                {
                    unset($specialities[$key]);
                }
            }

            DB::table("doctors")
                ->where("id", "=", $doctor->id)
                ->update([
                    "specialities" => json_encode($specialities)
                ]);
        }

        DB::table("specialities")
            ->where("id", "=", $speciality->id)
            ->delete();

        return response()->json([
            "status" => "success",
            "message" => "Speciality has been deleted."
        ]);
    }

    public function edit()
    {
        super_admin_auth();
        $id = request()->id ?? 0;

        $speciality = DB::table("specialities")
            ->where("id", "=", $id)
            ->first();

        if ($speciality == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Speciality not found."
            ]);
        }

        return view("admin/specialities/edit", [
            "speciality" => $speciality
        ]);
    }
}
