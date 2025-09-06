<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;
use App\Models\User;

class AdminController extends Controller
{
    public function settings()
    {
        if (request()->isMethod("post"))
        {
            $user = auth()->user();
            
            if (!$user->is_super_admin())
            {
                abort(401);
            }

            $emergency_number = request()->emergency_number ?? "";
            $whatsapp_number = request()->whatsapp_number ?? "";

            DB::table("settings")
                ->upsert([
                    [
                        "setting_key" => "emergency_number",
                        "setting_value" => $emergency_number,
                        "updated_at" => now()->utc()
                    ],
                    [
                        "setting_key" => "whatsapp_number",
                        "setting_value" => $whatsapp_number,
                        "updated_at" => now()->utc()
                    ]
                ], ["setting_key"], ["setting_value", "updated_at"]);

            cache()->forget("settings_emergency_number");
            cache()->forget("settings_whatsapp_number");

            return redirect()->back();
        }

        $settings = DB::table("settings")->get();

        return view("admin/settings", [
            "settings" => $settings
        ]);
    }

    public function login()
    {
        return view("admin/login");
    }

    public function logout()
    {
        $user = auth()->user();
        auth()->logout();

        return response()->json([
            "status" => "success",
            "message" => "Admin has been logged-out."
        ]);
    }

    public function me()
    {
        $user = auth()->user();

        if ($user->profile_image && Storage::exists("public/" . $user->profile_image))
            $user->profile_image = url("/storage/" . $user->profile_image);
        else
            $user->profile_image = "";

        $response = [
            "status" => "success",
            "message" => "Data has been fetched.",
            "user" => [
                "id" => $user->id,
                "name" => $user->name ?? "",
                "email" => $user->email ?? "",
                "profile_image" => $user->profile_image,
                "type" => $user->type ?? ""
            ]
        ];

        // $response["unread_contact_us"] = DB::table("contact_us")
        //     ->where("is_read", "=", 0)
        //     ->count();

        // request()->session()->put($this->user_session_key, $user->id);

        return response()->json($response);
    }

    public function do_login()
    {
        $validator = Validator::make(request()->all(), [
            "email" => "required",
            "password" => "required"
        ]);

        if (!$validator->passes() && count($validator->errors()->all()) > 0)
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->all()[0]
            ]);
        }

        $email = request()->email ?? "";
        $password = request()->password ?? "";

        $user = User::where("email", "=", $email)->first();

        if ($user == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Email does not exist."
            ]);
        }

        if (!password_verify($password, $user->password))
        {
            return response()->json([
                "status" => "error",
                "message" => "In-correct password."
            ]);
        }

        /*if (is_null($user->email_verified_at))
        {
            return response()->json([
                "status" => "error",
                "message" => "Email not verified."
            ]);
        }*/

        if (!in_array($user->type, ["admin", "super_admin"]))
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        if (!auth()->attempt([
                "email" => $email,
                "password" => $password
            ], true))
        {
            return response()->json([
                "status" => "error",
                "message" => "Invalid credentials"
            ]);
        }

        // $token = $user->createToken($this->admin_token_secret)->plainTextToken;

        // if (request()->hasSession())
        //     request()->session()->put(config("config.admin_token_secret"), $token);

        return response()->json([
            "status" => "success",
            "message" => "Login successfully.",
            // "access_token" => $token
        ]);
    }

    public function index()
    {
        $doctors = DB::table("users")
            ->where("type", "=", "doctor")
            ->whereNull("deleted_at")
            ->count();

        $patients = DB::table("users")
            ->where("type", "=", "patient")
            ->whereNull("deleted_at")
            ->count();

        $calls = DB::table("calls")
            ->count();

        $payments = DB::table("payments")
            ->count();

        return view("admin/index", [
            "doctors" => $doctors,
            "patients" => $patients,
            "calls" => $calls,
            "payments" => $payments
        ]);
    }

    public function change_password()
    {
        if (request()->isMethod("post"))
        {
            $validator = Validator::make(request()->all(), [
                "password" => "required",
                "new_password" => "required",
                "confirm_password" => "required"
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }

            $user = auth()->user();
            $password = request()->password ?? "";
            $new_password = request()->new_password ?? "";
            $confirm_password = request()->confirm_password ?? "";

            if (!password_verify($password, $user->password))
            {
                return response()->json([
                    "status" => "error",
                    "message" => "In-correct password."
                ]);
            }

            if ($new_password != $confirm_password)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Password mis-match."
                ]);
            }

            DB::table("users")
                ->where("id", "=", $user->id)
                ->update([
                    "password" => password_hash($new_password, PASSWORD_DEFAULT),
                    "updated_at" => now()->utc()
                ]);

            return response()->json([
                "status" => "success",
                "message" => "Password has been changed."
            ]);
        }

        return view("admin/change-password");
    }
}
