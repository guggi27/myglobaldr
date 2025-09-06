<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;

class ServiceController extends Controller
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

        $services = DB::table("services");

        if (!empty($search))
        {
            $services = $services->where(function ($query) use ($search) {
                $query->where("name", "LIKE", "%" . $search . "%");
            });
        }

        $services = $services->orderBy("id", "desc")
            ->paginate();

        $total = $services->total();

        return view("admin/services/index", [
            "services" => $services,
            "total" => $total,
            "search" => $search,
            "pagination" => $services->withPath(url("/admin/services"))->links("pagination::bootstrap-5")->render()
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

            $service = DB::table("services")
                ->where("name", "=", $name)
                ->first();

            if ($service != null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Service already exists."
                ]);
            }

            $service_id = DB::table("services")
                ->insertGetId([
                    "name" => $name,
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            return response()->json([
                "status" => "success",
                "message" => "Service has been added."
            ]);
        }

        return view("admin/services/add");
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

        $service = DB::table("services")
            ->where("id", "=", $id)
            ->first();

        if ($service == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Service not found."
            ]);
        }

        DB::table("services")
            ->where("id", "=", $service->id)
            ->update([
                "name" => $name,
                "updated_at" => now()->utc()
            ]);

        DB::table("doctors")
            ->whereRaw("JSON_CONTAINS(services, '\"" . ($service->name ?? "") . "\"')")
            ->update([
                "services" => DB::raw("
                    JSON_SET(
                        services,
                        JSON_UNQUOTE(
                            JSON_SEARCH(services, 'one', '" . ($service->name ?? "") . "')
                        ),
                        '" . $name . "'
                    )
                ")
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Service has been updated."
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

        $service = DB::table("services")
            ->where("id", "=", $id)
            ->first();

        if ($service == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Service not found."
            ]);
        }

        $doctors = DB::table("doctors")
            ->select("id", "services")
            ->whereRaw("JSON_CONTAINS(services, '\"" . $service->name . "\"')")
            ->get();

        foreach ($doctors as $doctor)
        {
            $services = json_decode($doctor->services ?? "[]", true);

            foreach ($services as $key => $value)
            {
                if ($value == $service->name)
                {
                    unset($services[$key]);
                }
            }

            DB::table("doctors")
                ->where("id", "=", $doctor->id)
                ->update([
                    "services" => json_encode($services)
                ]);
        }

        DB::table("services")
            ->where("id", "=", $service->id)
            ->delete();

        return response()->json([
            "status" => "success",
            "message" => "Service has been deleted."
        ]);
    }

    public function edit()
    {
        super_admin_auth();
        $id = request()->id ?? 0;

        $service = DB::table("services")
            ->where("id", "=", $id)
            ->first();

        if ($service == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Service not found."
            ]);
        }

        return view("admin/services/edit", [
            "service" => $service
        ]);
    }
}
