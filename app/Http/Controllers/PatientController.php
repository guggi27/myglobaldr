<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;
use App\Modules\Appointment;

class PatientController extends Controller
{
    public function fetch_appointment()
    {
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

        set_timezone();
        $user = auth()->user();
        $id = request()->id ?? 0;

        $appointment = new Appointment();
        $appointment->id = $id;
        $appointment_obj = $appointment->fetch_by_id();

        if ($appointment_obj == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Appointment not found."
            ]);
        }

        if ($appointment_obj->user_id != $user->id)
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Data has been fetched.",
            "appointment" => $appointment_obj
        ]);
    }

    public function appointment_basic_info()
    {
        $validator = Validator::make(request()->all(), [
            "first_name" => "required",
            "last_name" => "required",
            "phone" => "required",
            "email" => "required",
            "doctor_user_id" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $doctor_user_id = request()->doctor_user_id ?? 0;
        $first_name = request()->first_name ?? "";
        $last_name = request()->last_name ?? "";
        $phone = request()->phone ?? "";
        $email = request()->email ?? "";
        $symptoms = request()->symptoms ?? "";
        $reason_for_visit = request()->reason_for_visit ?? "";
        $slot = json_decode(request()->slot ?? "{}");
        $services = json_decode(request()->service ?? "[]") ?? [];
        $appointment_type = request()->appointment_type ?? "";
        $attachments = request()->file("attachments");

        if (!in_array($appointment_type, ["audio", "video"]))
        {
            return response()->json([
                "status" => "error",
                "message" => "Invalid appointment type '" . $appointment_type . "'."
            ]);
        }

        $doctor = DB::table("doctors")
            ->where("user_id", "=", $doctor_user_id)
            ->first();

        if ($doctor == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Doctor not found."
            ]);
        }

        $attachments_arr = [];
        if ($attachments)
        {
            foreach ($attachments as $attachment)
            {
                $file_path = "attachments/" . uniqid() . "." . $attachment->extension();
                $attachment->storeAs("private", $file_path);
                array_push($attachments_arr, $file_path);
            }
        }

        $fee = (double) ($doctor->fee ?? "0") ?? 0;
        $discount = (double) ($doctor->discount ?? "0") ?? 0;

        $total = 0;
        $total += $fee;

        foreach ($services as $service)
            $total += (double) ($service->price ?? "0") ?? 0;

        $total -= $discount;

        $appointment = new Appointment($user?->id ?? 0, $doctor->user_id,
            $first_name, $last_name, $phone, $email, $symptoms, $reason_for_visit,
            $attachments_arr, $fee, $slot, $services, $appointment_type, $discount,
            $total);
        $id = $appointment->add();

        return response()->json([
            "status" => "success",
            "message" => "Appointment has been booked.",
            "id" => $id,
        ]);
    }

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
