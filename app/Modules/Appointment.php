<?php

namespace App\Modules;

use DB;
use Storage;

class Appointment
{
    private $table = "appointments";

    public $id = 0;
    public $user_id = 0;
    public $doctor_user_id = 0;
    public $first_name = "";
    public $last_name = "";
    public $phone = "";
    public $email = "";
    public $symptoms = "";
    public $reason_for_visit = "";
    public $attachments = [];
    public $type = ""; // audio, video
    public $slot = null;
    public $services = [];
    public $fee = 0;
    public $discount = 0;
    public $total = 0;
    public $payment_status = ""; // unpaid, paid
    public $status = ""; // pending, cancelled, approved, done
    public $call_id = 0;

    public function __construct($user_id = 0, $doctor_user_id = 0, $first_name = "",
        $last_name = "", $phone = "", $email = "", $symptoms = "",
        $reason_for_visit = "", $attachments = [], $fee = 0, $slot = null,
        $services = [], $type = "", $discount = 0, $total = 0)
    {
        $this->user_id = $user_id;
        $this->doctor_user_id = $doctor_user_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->phone = $phone;
        $this->email = $email;
        $this->symptoms = $symptoms;
        $this->reason_for_visit = $reason_for_visit;
        $this->attachments = $attachments;
        $this->fee = $fee;
        $this->slot = $slot;
        $this->services = $services;
        $this->type = $type;
        $this->discount = $discount;
        $this->total = $total;
    }

    public function map($appointment)
    {
        $obj = [
            "id" => $appointment->id ?? 0,
            "user_id" => $appointment->user_id ?? 0,
            "doctor_user_id" => $appointment->doctor_user_id ?? 0,
            "first_name" => $appointment->first_name ?? "",
            "last_name" => $appointment->last_name ?? "",
            "phone" => $appointment->phone ?? "",
            "email" => $appointment->email ?? "",
            "symptoms" => $appointment->symptoms ?? "",
            "reason_for_visit" => $appointment->reason_for_visit ?? "",
            "type" => $appointment->type ?? "",
            "fee" => (double) ($appointment->fee ?? "0"),
            "discount" => (double) ($appointment->discount ?? "0"),
            "total" => (double) ($appointment->total ?? "0"),
            "payment_status" => $appointment->payment_status ?? "",
            "status" => $appointment->status ?? "",
            "services" => json_decode($appointment->services ?? "[]", true),
            "slot" => json_decode($appointment->slot ?? "{}", true),
            "attachments" => json_decode($appointment->attachments ?? "[]", true),
            "doctor" => null,
            "call_id" => $appointment->call_id ?? 0,
            "call_unique_id" => $appointment->call_unique_id ?? "",
            "created_at" => date("d F, Y", strtotime($appointment->created_at . " UTC")),
            "updated_at" => date("d F, Y", strtotime($appointment->updated_at . " UTC")),
        ];

        $obj["doctor"] = (object) [
            "id" => $appointment->doctor_user_id ?? 0,
            "name" => $appointment->doctor_name ?? "",
            "fee" => $appointment->fee ?? 0,
            "discount" => $appointment->discount ?? 0,
        ];

        /*foreach ($obj["attachments"] as $key => $value)
        {
            if ($value && Storage::exists("private/" . $value))
            {
                $obj["attachments"][$key] = url("/storage/" . $value);
            }
        }*/

        return (object) $obj;
    }

    public function fetch_by_user()
    {
        $appointments = DB::table($this->table)
            ->select($this->table . ".*", "users.name AS doctor_name", "doctors.fee",
                "doctors.discount", "calls.call_id AS call_unique_id")
            ->join("users", "users.id", "=", $this->table . ".doctor_user_id")
            ->join("doctors", "doctors.user_id", "=", $this->table . ".doctor_user_id")
            ->leftJoin("calls", "calls.id", "=", $this->table . ".call_id")
            ->where($this->table . ".user_id", "=", $this->user_id)
            ->paginate();

        $arr = [];
        foreach ($appointments as $appointment)
            array_push($arr, $this->map($appointment));

        return [
            "data" => $arr,
            "pages" => $appointments->lastPage(),
            "links" => $appointments->links("pagination::bootstrap-5")->render()
        ];
    }

    public function fetch_by_doctor()
    {
        $appointments = DB::table($this->table)
            ->select($this->table . ".*", "users.name AS doctor_name", "doctors.fee",
                "doctors.discount", "calls.call_id AS call_unique_id")
            ->join("users", "users.id", "=", $this->table . ".doctor_user_id")
            ->join("doctors", "doctors.user_id", "=", $this->table . ".doctor_user_id")
            ->leftJoin("calls", "calls.id", "=", $this->table . ".call_id")
            ->where("doctors.user_id", "=", $this->doctor_user_id)
            ->paginate();

        $arr = [];
        foreach ($appointments as $appointment)
            array_push($arr, $this->map($appointment));

        return [
            "data" => $arr,
            "pages" => $appointments->lastPage(),
            "links" => $appointments->links("pagination::bootstrap-5")->render()
        ];
    }

    public function fetch_by_id()
    {
        $appointment = DB::table($this->table)
            ->select($this->table . ".*", "users.name AS doctor_name", "doctors.fee",
                "doctors.discount")
            ->where($this->table . ".id", "=", $this->id)
            ->join("users", "users.id", "=", $this->table . ".doctor_user_id")
            ->join("doctors", "doctors.user_id", "=", $this->table . ".doctor_user_id")
            ->first();
        if ($appointment == null)
            return null;
        return $this->map($appointment);
    }

    public function update()
    {
        $obj = [
            "updated_at" => now()->utc()
        ];

        if (!empty($this->status))
            $obj["status"] = $this->status;

        if (!empty($this->payment_status))
            $obj["payment_status"] = $this->payment_status;

        if ($this->call_id > 0)
            $obj["call_id"] = $this->call_id;

        DB::table($this->table)
            ->where("id", "=", $this->id)
            ->update($obj);
    }

    public function add()
    {
        $obj = [
            "user_id" => $this->user_id,
            "doctor_user_id" => $this->doctor_user_id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "phone" => $this->phone,
            "email" => $this->email,
            "symptoms" => $this->symptoms,
            "reason_for_visit" => $this->reason_for_visit,
            "attachments" => json_encode($this->attachments),
            "fee" => $this->fee,
            "slot" => json_encode($this->slot ?? "{}"),
            "services" => json_encode($this->services ?? "[]"),
            "type" => $this->type,
            "discount" => $this->discount,
            "total" => $this->total,
            "payment_status" => "unpaid",
            "status" => "pending",
            "created_at" => now()->utc(),
            "updated_at" => now()->utc(),
        ];

        return DB::table($this->table)
            ->insertGetId($obj);
    }
}