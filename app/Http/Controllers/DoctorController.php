<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;
use App\Modules\Doctor;
use App\Modules\Appointment;

class DoctorController extends Controller
{
    public function change_appointment_status()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required",
            "status" => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        set_timezone();
        $user = auth()->user();
        if ($user->type != "doctor")
            abort(401);

        $id = request()->id ?? 0;
        $status = request()->status ?? "";

        if (!in_array($status, ["pending", "cancelled", "approved", "done"])) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid status '" . $status . "'."
            ]);
        }

        $appointment = new Appointment();
        $appointment->id = $id;
        $appointment_obj = $appointment->fetch_by_id();

        if ($appointment_obj == null) {
            return response()->json([
                "status" => "error",
                "message" => "Appointment not found."
            ]);
        }

        if ($appointment_obj->doctor_user_id != $user->id) {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $appointment->status = $status;
        $appointment->update();

        return response()->json([
            "status" => "success",
            "message" => "Status has been updated."
        ]);
    }

    public function appointments()
    {
        set_timezone();
        $user = auth()->user();
        // if ($user->type != "doctor")
        //     abort(401);

        $appointment = new Appointment();

        $response = [
            "data" => [],
            "pages" => 0,
            "links" => ""
        ];

        if ($user->type == "doctor") {
            $appointment->doctor_user_id = $user->id;
            $response = $appointment->fetch_by_doctor();
        } else if ($user->type == "patient") {
            $appointment->user_id = $user->id;
            $response = $appointment->fetch_by_user();
        }

        // Add dummy data for preview if no real data exists
        if (empty($response['data']) || request()->has('preview')) {
            $response['data'] = $this->getDummyAppointments();
            $response['links'] = ''; // No pagination for dummy data
        }

        return view("doctors/appointments", $response);
    }

    private function getDummyAppointments()
    {
        return [
            (object) [
                'id' => 1,
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+1 (555) 123-4567',
                'symptoms' => 'Persistent headaches, dizziness, and fatigue for the past week. Symptoms worsen in the evening.',
                'reason_for_visit' => 'Regular checkup and consultation regarding recent headaches and sleep issues',
                'slot' => [
                        'day' => 'Monday',
                        'time' => '10:00 AM'
                    ],
                'services' => [
                        ['name' => 'General Consultation', 'price' => 1500],
                        ['name' => 'Blood Pressure Check', 'price' => 500],
                        ['name' => 'ECG Test', 'price' => 800]
                    ],
                'fee' => 2000,
                'discount' => 200,
                'total' => 2600,
                'payment_status' => 'Paid',
                'attachments' => [
                    'medical_report_2024.pdf',
                    'lab_results.pdf'
                ],
                'call_unique_id' => 'call_abc123',
                'status' => 'approved'
            ],
            (object) [
                'id' => 2,
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '+1 (555) 987-6543',
                'symptoms' => 'Chest pain and shortness of breath during physical activity',
                'reason_for_visit' => 'Cardiac evaluation and stress test consultation',
                'slot' => [
                        'day' => 'Tuesday',
                        'time' => '2:30 PM'
                    ],
                'services' => [
                        ['name' => 'Cardiac Consultation', 'price' => 2500],
                        ['name' => 'Stress Test', 'price' => 1200],
                    ],
                'fee' => 2500,
                'discount' => 0,
                'total' => 3700,
                'payment_status' => 'Pending',
                'attachments' => [
                    'previous_ecg.pdf'
                ],
                'call_unique_id' => '',
                'status' => 'pending'
            ],
            (object) [
                'id' => 3,
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@email.com',
                'phone' => '+1 (555) 456-7890',
                'symptoms' => 'Lower back pain radiating to legs, numbness in toes',
                'reason_for_visit' => 'Follow-up appointment for chronic back pain management',
                'slot' => [
                        'day' => 'Wednesday',
                        'time' => '11:15 AM'
                    ],
                'services' => [
                        ['name' => 'Orthopedic Consultation', 'price' => 1800],
                        ['name' => 'X-Ray Review', 'price' => 600],
                        ['name' => 'Physical Therapy Assessment', 'price' => 900]
                    ],
                'fee' => 1800,
                'discount' => 300,
                'total' => 3000,
                'payment_status' => 'Paid',
                'attachments' => [
                    'xray_spine_2024.pdf',
                    'mri_report.pdf',
                    'therapy_notes.pdf'
                ],
                'call_unique_id' => 'call_xyz789',
                'status' => 'done'
            ],
            (object) [
                'id' => 4,
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@email.com',
                'phone' => '+1 (555) 234-5678',
                'symptoms' => 'Recurring migraines with visual disturbances',
                'reason_for_visit' => 'Neurological consultation for migraine management',
                'slot' => [
                        'day' => 'Thursday',
                        'time' => '9:00 AM'
                    ],
                'services' => [
                        ['name' => 'Neurological Consultation', 'price' => 2200],
                        ['name' => 'Visual Field Test', 'price' => 800]
                    ],
                'fee' => 2200,
                'discount' => 100,
                'total' => 2900,
                'payment_status' => 'Failed',
                'attachments' => [],
                'call_unique_id' => '',
                'status' => 'cancelled'
            ],
            (object) [
                'id' => 5,
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'email' => 'david.wilson@email.com',
                'phone' => '+1 (555) 345-6789',
                'symptoms' => 'Joint pain and stiffness, particularly in hands and knees',
                'reason_for_visit' => 'Arthritis evaluation and treatment planning',
                'slot' => [
                        'day' => 'Friday',
                        'time' => '3:45 PM'
                    ],
                'services' => [
                        ['name' => 'Rheumatology Consultation', 'price' => 1900],
                        ['name' => 'Joint X-Ray', 'price' => 700],
                        ['name' => 'Inflammation Markers Test', 'price' => 600]
                    ],
                'fee' => 1900,
                'discount' => 150,
                'total' => 3050,
                'payment_status' => 'Paid',
                'attachments' => [
                    'joint_xrays.pdf'
                ],
                'call_unique_id' => 'call_def456',
                'status' => 'approved'
            ]
        ];
    }

    public function fetch()
    {
        $doctor = new Doctor();
        $doctor->speciality = request()->speciality ?? "";
        $doctor->name = request()->name ?? "";
        list($doctors, $total, $pages) = $doctor->fetch();

        return response()->json([
            "status" => "success",
            "message" => "Data has been fetched.",
            "doctors" => $doctors,
            "total" => $total,
            "pages" => $pages
        ]);
    }

    public function find_for_diseases()
    {
        $validator = Validator::make(request()->all(), [
            "diseases" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $diseases = request()->diseases ?? [];
        $time_zone = session()->get(config("config.session_timezone_key"), "");

        $already_has_group_call = DB::table("group_calls")
            ->where("patient_id", "=", $user->id)
            ->where("status", "=", "created")
            ->exists();

        if ($already_has_group_call) {
            return response()->json([
                "status" => "error",
                "message" => "You already have a group call in place."
            ]);
        }

        $validDiseases = DB::table('diseases')
            ->whereIn('name', $diseases)
            ->pluck('name')
            ->toArray();

        $invalidDiseases = array_diff($diseases, $validDiseases);

        if (!empty($invalidDiseases)) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid diseases: " . implode(', ', $invalidDiseases)
            ]);
        }

        $query = DB::table('doctors');
        foreach ($diseases as $index => $disease) {
            if ($index === 0) {
                $query->whereJsonContains('diseases', $disease);
            } else {
                $query->orWhereJsonContains('diseases', $disease);
            }
        }
        $doctors = $query->get();

        $now = new \DateTime("now", new \DateTimeZone($time_zone));
        $now->modify("+8 hours");

        $rounded_minutes = 0;
        $minutes = $now->format("i");

        if ($minutes > 0 && $minutes <= 15)
            $rounded_minutes = 15;
        else if ($minutes > 15 && $minutes <= 30)
            $rounded_minutes = 30;
        else if ($minutes > 30 && $minutes <= 45)
            $rounded_minutes = 45;
        else if ($minutes > 45)
            $now->modify("+1 hour");

        $now->setTime(
            (int) $now->format("H"),
            $rounded_minutes,
            0
        );

        $now->setTImezone(new \DateTimeZone("UTC"));
        $start_at = $now->format("Y-m-d H:i:s");

        $call_id = uniqid();

        $group_call_id = DB::table("group_calls")
            ->insertGetId([
                "patient_id" => $user->id,
                "call_id" => $call_id,
                "diseases" => json_encode($diseases),
                "type" => "video",
                "status" => "created",
                "start" => $start_at,
                "created_at" => now()->utc(),
                "updated_at" => now()->utc()
            ]);

        $arr = [];
        foreach ($doctors as $doctor) {
            array_push($arr, [
                "group_call_id" => $group_call_id,
                "doctor_id" => $doctor->user_id ?? 0,
                "status" => "sent",
                "created_at" => now()->utc(),
                "updated_at" => now()->utc()
            ]);
        }

        DB::table("group_call_participants")
            ->insert($arr);

        $start_at_in_my_timezone = $start_at;
        if (!empty($time_zone)) {
            date_default_timezone_set($time_zone);
            $start_at_in_my_timezone = date("d F, Y h:i:s a", strtotime($start_at . " UTC"));
        }

        return response()->json([
            "status" => "success",
            "message" => "Your appointment has been booked at: " . $start_at_in_my_timezone,
            "call_id" => $call_id
        ]);
    }

    public function payment()
    {
        $id = request()->id ?? 0;

        $doctor = new Doctor();
        $user = $doctor->fetch_by_user_id($id);

        if ($user == null) {
            abort(404);
        }

        return view("doctors/payment", [
            "user" => $user
        ]);
    }

    public function basic_info()
    {
        $id = request()->id ?? 0;

        $doctor = new Doctor();
        $user = $doctor->fetch_by_user_id($id);

        if ($user == null) {
            abort(404);
        }

        return view("doctors/basic-info", [
            "user" => $user
        ]);
    }

    public function appointment_type()
    {
        $id = request()->id ?? 0;

        $doctor = new Doctor();
        $user = $doctor->fetch_by_user_id($id);

        if ($user == null) {
            abort(404);
        }

        return view("doctors/appointment-type", [
            "user" => $user
        ]);
    }

    public function appointment()
    {
        $id = request()->id ?? 0;

        $doctor = new Doctor();
        $user = $doctor->fetch_by_user_id($id);

        if ($user == null) {
            abort(404);
        }

        return view("doctors/appointment", [
            "user" => $user
        ]);
    }

    public function detail()
    {
        $id = request()->id ?? 0;

        $doctor = new Doctor();
        $user = $doctor->fetch_by_user_id($id);

        if ($user == null) {
            abort(404);
        }

        return view("doctors/detail", [
            "user" => $user
        ]);
    }

    public function index()
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

        return view("doctors/index", [
            "specialities" => $specialities,
            "doctors" => $doctors,
            "total" => $total,
            "pages" => $pages
        ]);

        /*$speciality = request()->speciality ?? "";

        $users = DB::table("users")
            ->select("users.*", "doctors.services", "doctors.specialities", "doctors.fee")
            ->leftJoin("doctors", "doctors.user_id", "=", "users.id")
            ->where("users.type", "=", "doctor");

        if (!empty($speciality))
        {
            $users = $users->where("doctors.specialities", "LIKE", "%" . $speciality . "%");
        }

        $users = $users->orderBy("users.id", "desc")
            ->paginate();

        foreach ($users as $key => $value)
        {
            if ($value->profile_image && Storage::exists("public/" . $value->profile_image))
            {
                $users[$key]->profile_image = url("/storage/" . $value->profile_image);
            }

            if ($value->services)
            {
                $users[$key]->services = json_decode($value->services ?? "[]", false);
            }

            if ($value->specialities)
            {
                $users[$key]->specialities = json_decode($value->specialities ?? "[]", false);
            }
        }

        $total = $users->total();

        return view("doctors/index-old", [
            "speciality" => $speciality,
            "users" => $users,
            "total" => $total,
            "pagination" => $users->withPath(url("/doctors?speciality=" . $speciality))
                ->links("pagination::bootstrap-5")
                ->render()
        ]);*/
    }

    public function admin_index()
    {
        super_admin_auth();

        $search = request()->search ?? "";
        $time_zone = request()->time_zone ?? "";
        if (!empty($time_zone)) {
            date_default_timezone_set($time_zone);
        }

        $users = DB::table("users")
            ->select("users.*", "doctors.services", "doctors.specialities")
            ->leftJoin("doctors", "doctors.user_id", "=", "users.id")
            ->where("users.type", "=", "doctor");

        if (!empty($search)) {
            $users = $users->where(function ($query) use ($search) {
                $query->where("users.name", "LIKE", "%" . $search . "%")
                    ->orWhere("users.email", "LIKE", "%" . $search . "%")
                    ->orWhere("doctors.services", "LIKE", "%" . $search . "%")
                    ->orWhere("doctors.specialities", "LIKE", "%" . $search . "%");
            });
        }

        $users = $users->orderBy("users.id", "desc")
            ->paginate();

        foreach ($users as $key => $value) {
            if ($value->profile_image && Storage::exists("public/" . $value->profile_image)) {
                $users[$key]->profile_image = url("/storage/" . $value->profile_image);
            }

            if ($value->services) {
                $users[$key]->services = json_decode($value->services ?? "[]", false);
            }

            if ($value->specialities) {
                $users[$key]->specialities = json_decode($value->specialities ?? "[]", false);
            }
        }

        $total = $users->total();

        return view("admin/doctors/index", [
            "users" => $users,
            "total" => $total,
            "search" => $search,
            "pagination" => $users->withPath(url("/admin/doctors"))->links("pagination::bootstrap-5")->render()
        ]);
    }

    public function add()
    {
        super_admin_auth();

        if (request()->isMethod("post")) {
            $validator = Validator::make(request()->all(), [
                "name" => "required",
                "email" => "required",
                "password" => "required"
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "status" => "error",
                    "message" => $validator->errors()->first()
                ]);
            }

            $name = request()->name ?? "";
            $email = request()->email ?? "";
            $password = request()->password ?? "";
            $services = json_decode(request()->services ?? "[]", false);
            $specialities = json_decode(request()->specialities ?? "[]", false);
            $diseases = json_decode(request()->diseases ?? "[]", false);
            $fee = request()->fee ?? 0;

            if (!is_numeric($fee)) {
                return response()->json([
                    "status" => "error",
                    "message" => "Fee is not valid."
                ]);
            }

            $fee = (double) $fee;

            $validServices = DB::table('services')
                ->whereIn('name', $services)
                ->pluck('name')
                ->toArray();

            $invalidServices = array_diff($services, $validServices);

            if (!empty($invalidServices)) {
                return response()->json([
                    "status" => "error",
                    "message" => "Invalid services: " . implode(', ', $invalidServices)
                ]);
            }

            $validSpecialities = DB::table('specialities')
                ->whereIn('name', $specialities)
                ->pluck('name')
                ->toArray();

            $invalidSpecialities = array_diff($specialities, $validSpecialities);

            if (!empty($invalidSpecialities)) {
                return response()->json([
                    "status" => "error",
                    "message" => "Invalid specialities: " . implode(', ', $invalidSpecialities)
                ]);
            }

            $validDiseases = DB::table('diseases')
                ->whereIn('name', $diseases)
                ->pluck('name')
                ->toArray();

            $invalidDiseases = array_diff($diseases, $validDiseases);

            if (!empty($invalidDiseases)) {
                return response()->json([
                    "status" => "error",
                    "message" => "Invalid diseases: " . implode(', ', $invalidDiseases)
                ]);
            }

            $user = DB::table("users")
                ->where("email", "=", $email)
                ->first();

            if ($user != null) {
                return response()->json([
                    "status" => "error",
                    "message" => "Email already exists."
                ]);
            }

            $user_id = DB::table("users")
                ->insertGetId([
                    "name" => $name,
                    "email" => $email,
                    "password" => password_hash($password, PASSWORD_DEFAULT),
                    "type" => "doctor",
                    "email_verified_at" => now()->utc(),
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            $doctor_id = DB::table("doctors")
                ->insertGetId([
                    "user_id" => $user_id,
                    "services" => json_encode($services),
                    "specialities" => json_encode($specialities),
                    "diseases" => json_encode($diseases),
                    "fee" => $fee,
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            return response()->json([
                "status" => "success",
                "message" => "Doctor has been added."
            ]);
        }

        $services = DB::table("services")
            ->orderBy("name", "asc")
            ->get();

        $specialities = DB::table("specialities")
            ->orderBy("name", "asc")
            ->get();

        $diseases = DB::table("diseases")
            ->orderBy("name", "asc")
            ->get();

        return view("admin/doctors/add", [
            "services" => $services,
            "specialities" => $specialities,
            "diseases" => $diseases
        ]);
    }

    public function update()
    {
        super_admin_auth();

        $validator = Validator::make(request()->all(), [
            "id" => "required",
            "name" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $id = request()->id ?? 0;
        $name = request()->name ?? "";
        $services = json_decode(request()->services ?? "[]", false);
        $specialities = json_decode(request()->specialities ?? "[]", false);
        $diseases = json_decode(request()->diseases ?? "[]", false);
        $fee = request()->fee ?? 0;

        if (!is_numeric($fee)) {
            return response()->json([
                "status" => "error",
                "message" => "Fee is not valid."
            ]);
        }

        $fee = (double) $fee;

        $validServices = DB::table('services')
            ->whereIn('name', $services)
            ->pluck('name')
            ->toArray();

        $invalidServices = array_diff($services, $validServices);

        if (!empty($invalidServices)) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid services: " . implode(', ', $invalidServices)
            ]);
        }

        $validSpecialities = DB::table('specialities')
            ->whereIn('name', $specialities)
            ->pluck('name')
            ->toArray();

        $invalidSpecialities = array_diff($specialities, $validSpecialities);

        if (!empty($invalidSpecialities)) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid specialities: " . implode(', ', $invalidSpecialities)
            ]);
        }

        $validDiseases = DB::table('diseases')
            ->whereIn('name', $diseases)
            ->pluck('name')
            ->toArray();

        $invalidDiseases = array_diff($diseases, $validDiseases);

        if (!empty($invalidDiseases)) {
            return response()->json([
                "status" => "error",
                "message" => "Invalid diseases: " . implode(', ', $invalidDiseases)
            ]);
        }

        $user = DB::table("users")
            ->where("type", "=", "doctor")
            ->where("id", "=", $id)
            ->whereNull("deleted_at")
            ->first();

        if ($user == null) {
            return response()->json([
                "status" => "error",
                "message" => "Doctor not found."
            ]);
        }

        DB::table("users")
            ->where("id", "=", $user->id)
            ->update([
                "name" => $name,
                "updated_at" => now()->utc()
            ]);

        $doctor = DB::table("doctors")
            ->where("user_id", "=", $user->id)
            ->first();

        if ($doctor == null) {
            $doctor_id = DB::table("doctors")
                ->insertGetId([
                    "user_id" => $user->id,
                    "services" => json_encode($services),
                    "specialities" => json_encode($specialities),
                    "diseases" => json_encode($diseases),
                    "fee" => $fee,
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);
        } else {
            DB::table("doctors")
                ->where("id", "=", $doctor->id)
                ->update([
                    "services" => json_encode($services),
                    "specialities" => json_encode($specialities),
                    "diseases" => json_encode($diseases),
                    "fee" => $fee,
                    "updated_at" => now()->utc()
                ]);
        }

        return response()->json([
            "status" => "success",
            "message" => "Doctor has been updated."
        ]);
    }

    public function destroy()
    {
        super_admin_auth();

        $validator = Validator::make(request()->all(), [
            "id" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $id = request()->id ?? 0;

        $user = DB::table("users")
            ->where("type", "=", "doctor")
            ->where("id", "=", $id)
            ->first();

        if ($user == null) {
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
            "message" => "Doctor has been deleted."
        ]);
    }

    public function edit()
    {
        super_admin_auth();
        $id = request()->id ?? 0;

        $user = DB::table("users")
            ->where("id", "=", $id)
            ->where("type", "=", "doctor")
            ->whereNull("deleted_at")
            ->first();

        if ($user == null) {
            return response()->json([
                "status" => "error",
                "message" => "Doctor not found."
            ]);
        }

        if ($user->profile_image && Storage::exists("public/" . $user->profile_image)) {
            $user->profile_image = url("/storage/" . $user->profile_image);
        }

        $doctor = DB::table("doctors")
            ->where("user_id", "=", $user->id)
            ->orderBy("id", "desc")
            ->first();

        if ($doctor != null) {
            if ($doctor->services) {
                $doctor->services = json_decode($doctor->services ?? "[]", false);
            }

            if ($doctor->specialities) {
                $doctor->specialities = json_decode($doctor->specialities ?? "[]", false);
            }

            if ($doctor->diseases) {
                $doctor->diseases = json_decode($doctor->diseases ?? "[]", false);
            }
        }

        $services = DB::table("services")
            ->orderBy("name", "asc")
            ->get();

        $specialities = DB::table("specialities")
            ->orderBy("name", "asc")
            ->get();

        $diseases = DB::table("diseases")
            ->orderBy("name", "asc")
            ->get();

        return view("admin/doctors/edit", [
            "user" => $user,
            "doctor" => $doctor,
            "services" => $services,
            "specialities" => $specialities,
            "diseases" => $diseases
        ]);
    }
}