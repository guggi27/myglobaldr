<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\Admin;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PageController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::post("/reset-password", [UserController::class, "reset_password"]);
Route::get("/reset-password/{email}/{token}", [UserController::class, "reset_password_view"])
    ->name("password.reset");

Route::post("/send-password-reset-link", [UserController::class, "send_password_reset_link"]);
Route::get("/forgot-password", [UserController::class, "forgot_password"])
    ->name("password.request");

Route::get("/logout", [UserController::class, "logout"]);
Route::any("/register", [UserController::class, "register"]);
Route::any("/login", [UserController::class, "login"])
    ->name("login");
Route::get("/doctors/{id}/detail", [DoctorController::class, "detail"])
    ->name("doctors.detail");
Route::get("/doctors", [DoctorController::class, "index"]);
Route::get("/services", [PageController::class, "services"]);
Route::get("/about-us", [PageController::class, "about_us"]);
Route::get("/", [PageController::class, "home"]);
Route::post("/set-timezone", [UserController::class, "set_timezone"]);
Route::get("/admin/login", [AdminController::class, "login"]);
Route::post("/admin/login", [AdminController::class, "do_login"]);

Route::group([
    "middleware" => ["auth"]
], function () {
    Route::post("/group-calls/end", [CallController::class, "end_group_call"]);
    Route::get("/group-calls", [CallController::class, "index_group"]);
    Route::post("/group-calls/verify", [CallController::class, "verify_group_call"]);
    Route::get("/group-calls/{id}/detail", [CallController::class, "group_call_detail"])
        ->name("group-call.detail");
    Route::post("/group-calls/reject", [CallController::class, "reject_group_call"]);
    Route::post("/group-calls/accept", [CallController::class, "accept_group_call"]);
    Route::post("/doctors/find-for-diseases", [DoctorController::class, "find_for_diseases"]);
    Route::post("/payments/verify-stripe", [PaymentController::class, "verify_stripe"]);
    Route::post("/payments/fetch-stripe-intent", [PaymentController::class, "fetch_stripe_intent"]);

    Route::get("/balance", [UserController::class, "balance"]);
    Route::any("/profile-settings", [UserController::class, "profile_settings"]);

    Route::get("/calls", [CallController::class, "index"]);
    Route::post("/calls/end", [CallController::class, "end"]);
    Route::post("/calls/fetch-message", [CallController::class, "fetch_message"]);
    Route::post("/calls/send-message", [CallController::class, "send_message"]);
    Route::post("/calls/reject", [CallController::class, "reject"]);
    Route::post("/calls/accept", [CallController::class, "accept"]);
    Route::post("/calls/is-incoming", [CallController::class, "is_incoming"]);
    Route::post("/calls/verify", [CallController::class, "verify"]);
    Route::get("/calls/{id}/detail", [CallController::class, "detail"])
        ->name("calls.detail");
    Route::post("/calls/start", [CallController::class, "start"]);
});

Route::group([
    "middleware" => [Admin::class]
], function () {
    Route::get("/admin", [AdminController::class, "index"]);
    Route::post("/admin/me", [AdminController::class, "me"]);
    Route::any("/admin/change-password", [AdminController::class, "change_password"]);
    Route::any("/admin/settings", [AdminController::class, "settings"]);
    Route::post("/admin/logout", [AdminController::class, "logout"]);

    Route::any("/admin/specialities/add", [SpecializationController::class, "add"]);
    Route::post("/admin/specialities/delete", [SpecializationController::class, "destroy"]);
    Route::post("/admin/specialities/update", [SpecializationController::class, "update"]);
    Route::get("/admin/specialities/{id}/edit", [SpecializationController::class, "edit"]);
    Route::get("/admin/specialities", [SpecializationController::class, "admin_index"]);

    Route::any("/admin/services/add", [ServiceController::class, "add"]);
    Route::post("/admin/services/delete", [ServiceController::class, "destroy"]);
    Route::post("/admin/services/update", [ServiceController::class, "update"]);
    Route::get("/admin/services/{id}/edit", [ServiceController::class, "edit"]);
    Route::get("/admin/services", [ServiceController::class, "admin_index"]);

    Route::get("/admin/calls/{id}/detail", [CallController::class, "admin_detail"]);
    Route::get("/admin/calls", [CallController::class, "admin_index"]);

    Route::post("/admin/patients/delete", [PatientController::class, "destroy"]);
    Route::get("/admin/patients", [PatientController::class, "admin_index"]);

    Route::any("/admin/doctors/add", [DoctorController::class, "add"]);
    Route::post("/admin/doctors/delete", [DoctorController::class, "destroy"]);
    Route::post("/admin/doctors/update", [DoctorController::class, "update"]);
    Route::get("/admin/doctors/{id}/edit", [DoctorController::class, "edit"]);
    Route::get("/admin/doctors", [DoctorController::class, "admin_index"]);
});