<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;

class PatientController extends Controller
{
    public function destroy()
    {
        super_admin_auth();

        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if (!$validator->passes() && count($validator->errors()->all()) > 0)
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->all()[0]
            ]);
        }

        $id = request()->id ?? 0;

        $user = DB::table("users")
            ->where("type", "=", "patient")
            ->where("id", "=", $id)
            ->first();

        if ($user == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "User not found."
            ]);
        }

        DB::table("users")
            ->where("id", "=", $user->id)
            ->update([
                "deleted_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Patient has been deleted."
        ]);
    }

    public function admin_index()
    {
        super_admin_auth();
        
        $search = request()->search ?? "";
        $time_zone = request()->time_zone ?? "";
        if (!empty($time_zone))
        {
            date_default_timezone_set($time_zone);
        }

        $users = DB::table("users")
            ->select("users.*", "patients.phone")
            ->leftJoin("patients", "patients.user_id", "=", "users.id")
            ->where("users.type", "=", "patient");

        if (!empty($search))
        {
            $users = $users->where(function ($query) use ($search) {
                $query->where("users.name", "LIKE", "%" . $search . "%")
                    ->orWhere("users.email", "LIKE", "%" . $search . "%")
                    ->orWhere("patients.phone", "LIKE", "%" . $search . "%");
            });
        }

        $users = $users->orderBy("users.id", "desc")
            ->paginate();

        foreach ($users as $key => $value)
        {
            if ($value->profile_image && Storage::exists("public/" . $value->profile_image))
            {
                $users[$key]->profile_image = url("/storage/" . $value->profile_image);
            }
        }

        $total = $users->total();

        return view("admin/patients/index", [
            "users" => $users,
            "total" => $total,
            "search" => $search,
            "pagination" => $users->withPath(url("/admin/patients"))->links("pagination::bootstrap-5")->render()
        ]);
    }
}
