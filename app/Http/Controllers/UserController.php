<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Str;
use Mail;
use Storage;
use Validator;
use App\Models\User;
use Twilio\Rest\Client;

class UserController extends Controller
{
    public function balance()
    {
        return view("balance");
    }

    public function register()
    {
        if (request()->isMethod("post")) {
            $validator = Validator::make(request()->all(), [
                "name" => "required",
                "email" => "required",
                "password" => "required"
            ]);

            if (!$validator->passes() && count($validator->errors()->all()) > 0) {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->all()[0]
                ]);
            }

            $name = request()->name ?? "";
            $email = request()->email ?? "";
            $password = request()->password ?? "";

            $user = DB::table("users")
                ->where("email", "=", $email)
                ->first();

            if ($user != null) {
                return response()->json([
                    "status" => "error",
                    "message" => "Email already exists."
                ]);
            }

            $user_arr = [
                "name" => $name,
                "email" => $email,
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "type" => "patient",
                "created_at" => now()->utc(),
                "updated_at" => now()->utc()
            ];

            DB::table("users")
                ->insertGetId($user_arr);

            return response()->json([
                "status" => "success",
                "message" => "Account has been created. Please login now."
            ]);
        }

        return view("register");
    }

    public function reset_password()
    {
        $validator = Validator::make(request()->all(), [
            "email" => "required",
            "token" => "required",
            "password" => "required",
            "confirm_password" => "required"
        ]);

        if (!$validator->passes() && count($validator->errors()->all()) > 0) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->all()[0]
            ]);
        }

        $email = request()->email ?? "";
        $token = request()->token ?? "";
        $password = request()->password ?? "";
        $confirm_password = request()->confirm_password ?? "";

        $password_reset_token = DB::table("password_reset_tokens")
            ->where("email", "=", $email)
            ->where("token", "=", $token)
            ->first();

        if ($password_reset_token == null) {
            return response()->json([
                "status" => "error",
                "message" => "Reset link is expired."
            ]);
        }

        if ($password != $confirm_password) {
            return response()->json([
                "status" => "error",
                "message" => "Password mis-match."
            ]);
        }

        DB::table("password_reset_tokens")
            ->where("email", "=", $email)
            ->where("token", "=", $token)
            ->delete();

        DB::table("users")
            ->where("email", "=", $email)
            ->update([
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Password has been reset."
        ]);
    }

    public function reset_password_view()
    {
        $token = request()->token ?? "";
        $email = request()->email ?? "";

        $password_reset_token = DB::table("password_reset_tokens")
            ->where("email", "=", $email)
            ->where("token", "=", $token)
            ->first();

        if ($password_reset_token == null) {
            abort(404);
        }

        return view("reset-password", [
            "email" => $email,
            "token" => $token
        ]);
    }

    public function send_password_reset_link()
    {
        $validator = Validator::make(request()->all(), [
            "email" => "required"
        ]);

        if (!$validator->passes() && count($validator->errors()->all()) > 0) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->all()[0]
            ]);
        }

        $email = request()->email ?? "";

        $user = DB::table("users")
            ->where("email", "=", $email)
            ->first();

        if ($user == null) {
            return response()->json([
                "status" => "error",
                "message" => "User not found."
            ]);
        }

        // $reset_token = time() . md5($email);
        $reset_token = Str::random(60);

        $message = "<p>Please click the link below to reset your password</p>";
        $message .= "<a href='" . url("/reset-password/" . $email . "/" . $reset_token) . "'>";
        $message .= "Reset password";
        $message .= "</a>";

        Mail::html($message, function ($m) use ($email) {
            $m->to($email)
                ->subject('Password reset link');
        });

        DB::table("password_reset_tokens")
            ->insertGetId([
                "email" => $email,
                "token" => $reset_token,
                "created_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Instructions to reset password has been sent."
        ]);
    }

    public function forgot_password()
    {
        return view("forgot-password");
    }

    public function profile_settings()
    {
        return view("profile-settings");
    }

    public function logout()
    {
        if (request()->is("api/*")) {
            $user = auth()->user();

            // $user->tokens()->delete();

            $user->currentAccessToken()->delete();

            // $user->tokens()->where("id", $token_id)->delete();

            return response()->json([
                "status" => "success",
                "message" => "Logout successfully."
            ]);
        }

        auth()->logout();
        return redirect()->back();
        // return redirect("/");
    }

    public function send_otp()
    {
        $validator = Validator::make(request()->all(), [
            "phone" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $phone = request()->phone ?? "";
        $first_name = request()->first_name ?? "";
        $last_name = request()->last_name ?? "";
        $phone = request()->phone ?? "";
        $email = request()->email ?? "";

        $user_obj = DB::table("users")
            ->where("phone", "=", $phone)
            ->first();

        $otp = uniqid();
        if ($user_obj == null) {
            $password = uniqid();

            DB::table("users")
                ->insertGetId([
                    "name" => $first_name . " " . $last_name,
                    "email" => $email,
                    "phone" => $phone,
                    "password" => password_hash($password, PASSWORD_DEFAULT),
                    "type" => "patient",
                    "otp" => $otp,
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);
        } else {
            DB::table("users")
                ->where("id", "=", $user_obj->id)
                ->update([
                    "otp" => $otp,
                    "updated_at" => now()->utc()
                ]);
        }

        try {
            $twilio = new Client(config("config.twilio_sid"), config("config.twilio_auth_token"));

            $message = $twilio->messages->create(
                $phone, // Receiver's phone number (E.164 format, e.g. +14155552671)
                [
                    "from" => config("config.twilio_phone"), // Your Twilio phone number
                    "body" => "Hello! Your authentication code for " . config("config.app_name") . " is " . $otp
                ]
            );
        } catch (\Exception $exp) {
            // 
        }

        return response()->json([
            "status" => "success",
            "message" => "An OTP has been sent to your mobile number. Kindly enter to login and continue."
        ]);
    }

    public function login_with_otp()
    {
        $validator = Validator::make(request()->all(), [
            "phone" => "required",
            "otp" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $phone = request()->phone ?? "";
        $otp = request()->otp ?? "";

        $user = User::where("phone", "=", $phone)
            ->where("otp", "=", $otp)
            ->whereNull("deleted_at")
            ->first();

        if ($user == null) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid OTP."
            ]);
        }

        DB::table("users")
            ->where("id", "=", $user->id)
            ->update([
                "otp" => null,
                "updated_at" => now()->utc()
            ]);

        if (request()->is("api/*")) {
            $token = $user->createToken($this->token_secret)->plainTextToken;

            return response()->json([
                "status" => "success",
                "message" => "Login successfully.",
                "access_token" => $token
            ]);
        }

        auth()->loginUsingId($user->id);

        return response()->json([
            "status" => "success",
            "message" => "Login successfully."
        ]);
    }

    public function login()
    {
        if (request()->isMethod("post")) {
            $validator = Validator::make(request()->all(), [
                "email" => "required",
                "password" => "required"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }

            $email = request()->email ?? "";
            $password = request()->password ?? "";

            $user = User::where("email", "=", $email)
                ->whereNull("deleted_at")
                ->first();

            if ($user == null) {
                return response()->json([
                    "status" => "error",
                    "message" => "Email does not exist."
                ]);
            }

            if (!in_array($user->type, ["doctor", "admin", "super_admin"])) {
                return response()->json([
                    "status" => "error",
                    "message" => "Unauthorized."
                ]);
            }

            if (!password_verify($password, $user->password)) {
                return response()->json([
                    "status" => "error",
                    "message" => "In-correct password."
                ]);
            }

            if (is_null($user->email_verified_at)) {
                return response()->json([
                    "status" => "error",
                    "message" => "Email not verified."
                ]);
            }

            if (request()->is("api/*")) {
                $token = $user->createToken($this->token_secret)->plainTextToken;

                return response()->json([
                    "status" => "success",
                    "message" => "Login successfully.",
                    "access_token" => $token
                ]);
            }

            if (
                auth()->attempt([
                    "email" => $email,
                    "password" => $password
                ], true)
            ) {
                return response()->json([
                    "status" => "success",
                    "message" => "Login successfully."
                ]);
            }

            return response()->json([
                "status" => "error",
                "message" => "Invalid credentials."
            ]);
        }

        return view("login");
    }

    public function set_timezone()
    {
        $timezone = request()->timezone ?? "";
        // session([
        //     config("config.session_timezone_key") => $timezone
        // ]);

        session()->put(config("config.session_timezone_key"), $timezone);

        // session(config("config.session_timezone_key"));
        // session()->forget(config("config.session_timezone_key"));
    }
}
