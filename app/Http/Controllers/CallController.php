<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class CallController extends Controller
{
    public function admin_detail()
    {
        super_admin_auth();

        $me = auth()->user();
        $id = request()->id ?? "";

        $call = DB::table("calls")
            ->select("calls.*", "patients.phone AS p_phone",
                "doctors.services", "doctors.specialities",
                "d_user.name AS d_name", "d_user.email AS d_email", "d_user.profile_image AS d_profile_image",
                "p_user.name AS p_name", "p_user.email AS p_email", "p_user.profile_image AS p_profile_image")
            ->leftJoin("patients", "patients.user_id", "=", "calls.patient_id")
            ->leftJoin("doctors", "doctors.user_id", "=", "calls.doctor_id")
            ->join("users AS d_user", "d_user.id", "=", "calls.doctor_id")
            ->join("users AS p_user", "p_user.id", "=", "calls.patient_id")
            ->where("calls.call_id", "=", $id)
            ->first();

        if ($call == null)
        {
            abort(404);
        }

        if ($call->d_profile_image && Storage::exists("public/" . $call->d_profile_image))
        {
            $call->d_profile_image = url("/storage/" . $call->d_profile_image);
        }

        if ($call->p_profile_image && Storage::exists("public/" . $call->p_profile_image))
        {
            $call->p_profile_image = url("/storage/" . $call->p_profile_image);
        }

        if ($call->services)
        {
            $call->services = json_decode($call->services ?? "[]", false);
        }

        if ($call->specialities)
        {
            $call->specialities = json_decode($call->specialities ?? "[]", false);
        }

        $accountSid = config("config.twilio_sid");
        $apiKeySid = config("config.twilio_api_key");
        $authToken = config("config.twilio_auth_token");

        $twilio = new Client($accountSid, $authToken);

        try
        {
            $recordings = $twilio->video->v1->recordings
                ->read(['groupingSid' => $call->call_id ?? ""]);

            foreach ($recordings as $recording)
            {
                if ($recording->type === 'audio')
                {
                    $mediaSid = $recording->sid;

                    $mediaRedirectUrl = "https://video.twilio.com/v1/Recordings/{$recording->sid}/Media";

                    $guzzle_client = new \GuzzleHttp\Client();

                    $response = $guzzle_client->get($mediaRedirectUrl, [
                        'auth' => [$accountSid, $authToken], // Basic Auth
                        'allow_redirects' => false           // Don’t auto-follow redirect
                    ]);

                    if ($response->getStatusCode() == 302)
                    {
                        $mp4Url = $response->getHeaderLine('Location'); // This is the actual downloadable file
                        dd($mp4Url);
                    }
                }
            }
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
        }

        return view("admin/calls/detail", [
            "call" => $call
        ]);
    }

    public function index_group()
    {
        $me = auth()->user();
        $search = request()->search ?? "";
        $time_zone = session()->get(config("config.session_timezone_key"), "");
        if (!empty($time_zone))
        {
            date_default_timezone_set($time_zone);
        }

        if (!in_array($me->type, ["doctor", "patient"]))
        {
            abort(401);
        }

        $calls = DB::table("group_calls")
            ->select("group_calls.*", "patient.name AS p_name", "patient.email AS p_email",
                "patient.profile_image AS p_profile_image")
            ->join("group_call_participants", "group_call_participants.group_call_id", "=", "group_calls.id")
            ->join("users AS patient", "patient.id", "=", "group_calls.patient_id")
            ->where(function ($query) use ($me) {
                $query->where("group_calls.patient_id", "=", $me->id)
                    ->orWhere("group_call_participants.doctor_id", "=", $me->id);
            })
            ->orderBy("group_calls.id", "desc")
            ->groupBy("group_calls.id")
            ->paginate();

        $arr = [];

        foreach ($calls as $group_call_participant)
        {
            $obj = [
                "id" => $group_call_participant->id ?? 0,
                "call_id" => $group_call_participant->call_id ?? "",
                "p_id" => $group_call_participant->patient_id ?? 0,
                "p_name" => $group_call_participant->p_name ?? "",
                "p_email" => $group_call_participant->p_email ?? "",
                "p_profile_image" => $group_call_participant->p_profile_image ?? "",
                "status" => $group_call_participant->status ?? "",
                "diseases" => json_decode($group_call_participant->diseases ?? "[]") ?? [],
                "start" => "",
                "end" => "",
                "created_at" => date("d F, Y h:i a", strtotime(($group_call_participant->created_at ?? "") . " UTC"))
            ];

            if ($group_call_participant->start)
            {
                $obj["start"] = date("d F, Y h:i a", strtotime($group_call_participant->start . " UTC"));
            }

            if ($group_call_participant->end)
            {
                $obj["end"] = date("d F, Y h:i a", strtotime($group_call_participant->end . " UTC"));
            }

            if ($obj["p_profile_image"] && \Storage::exists("public/" . $obj["p_profile_image"]))
            {
                $obj["p_profile_image"] = url("/storage/" . $obj["p_profile_image"]);
            }

            array_push($arr, (object) $obj);
        }

        $total = $calls->total();

        return view("group-calls/index", [
            "calls" => $arr,
            "total" => $total,
            "search" => $search,
            "pagination" => $calls->withPath(url("/group-calls"))->links()->render()
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        $search = request()->search ?? "";
        $time_zone = session()->get(config("config.session_timezone_key"), "");
        if (!empty($time_zone))
        {
            date_default_timezone_set($time_zone);
        }

        if (!in_array($user->type, ["doctor", "patient"]))
        {
            abort(401);
        }

        if ($user->type == "doctor")
        {
            $calls = DB::table("calls")
                ->select("calls.*", "patients.phone AS p_phone",
                    "p_user.name AS u_name", "p_user.email AS u_email", "p_user.profile_image AS u_profile_image")
                ->leftJoin("patients", "patients.user_id", "=", "calls.patient_id")
                ->join("users AS p_user", "p_user.id", "=", "calls.patient_id")
                ->orderBy("calls.id", "desc")
                ->paginate();
        }
        else if ($user->type == "patient")
        {
            $calls = DB::table("calls")
                ->select("calls.*",
                    "d_user.name AS u_name", "d_user.email AS u_email", "d_user.profile_image AS u_profile_image")
                ->leftJoin("doctors", "doctors.user_id", "=", "calls.doctor_id")
                ->join("users AS d_user", "d_user.id", "=", "calls.doctor_id")
                ->orderBy("calls.id", "desc")
                ->paginate();
        }

        foreach ($calls as $key => $value)
        {
            if ($value->u_profile_image && Storage::exists("public/" . $value->u_profile_image))
            {
                $calls[$key]->u_profile_image = url("/storage/" . $value->u_profile_image);
            }
        }

        $total = $calls->total();

        return view("calls/index", [
            "calls" => $calls,
            "total" => $total,
            "search" => $search,
            "pagination" => $calls->withPath(url("/calls"))->links()->render()
        ]);
    }

    public function admin_index()
    {
        super_admin_auth();
        
        $search = request()->search ?? "";
        $time_zone = session()->get(config("config.session_timezone_key"), "");
        if (!empty($time_zone))
        {
            date_default_timezone_set($time_zone);
        }

        $calls = DB::table("calls")
            ->select("calls.*", "patients.phone AS p_phone",
                "d_user.name AS d_name", "d_user.email AS d_email", "d_user.profile_image AS d_profile_image",
                "p_user.name AS p_name", "p_user.email AS p_email", "p_user.profile_image AS p_profile_image")
            ->leftJoin("patients", "patients.user_id", "=", "calls.patient_id")
            ->leftJoin("doctors", "doctors.user_id", "=", "calls.doctor_id")
            ->join("users AS d_user", "d_user.id", "=", "calls.doctor_id")
            ->join("users AS p_user", "p_user.id", "=", "calls.patient_id")
            ->orderBy("calls.id", "desc")
            ->paginate();

        foreach ($calls as $key => $value)
        {
            if ($value->d_profile_image && Storage::exists("public/" . $value->d_profile_image))
            {
                $calls[$key]->d_profile_image = url("/storage/" . $value->d_profile_image);
            }

            if ($value->p_profile_image && Storage::exists("public/" . $value->p_profile_image))
            {
                $calls[$key]->p_profile_image = url("/storage/" . $value->p_profile_image);
            }
        }

        $total = $calls->total();

        return view("admin/calls/index", [
            "calls" => $calls,
            "total" => $total,
            "search" => $search,
            "pagination" => $calls->withPath(url("/admin/calls"))->links("pagination::bootstrap-5")->render()
        ]);
    }

    public function end_group_call()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        if ($me->type != "patient")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $call = DB::table("group_calls")
            ->where("call_id", "=", $id)
            ->where("patient_id", "=", $me->id)
            ->first();

        if ($call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        $accountSid = config("config.twilio_sid");
        $apiKeySid = config("config.twilio_api_key");
        $authToken = config("config.twilio_auth_token");

        $twilio = new Client($accountSid, $authToken);

        try
        {
            DB::table("group_calls")
                ->where("id", "=", $call->id)
                ->update([
                    "status" => "completed",
                    "end" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            $twilio->video->v1->rooms($call->call_id)->update("completed");

            return response()->json([
                "status" => "success",
                "message" => "Call ended successfully."
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function end()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        $call = DB::table("calls")
            ->where("call_id", "=", $id)
            ->where(function ($query) use ($me) {
                $query->where("doctor_id", "=", $me->id)
                    ->orWhere("patient_id", "=", $me->id);
            })
            ->first();

        if ($call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        $accountSid = config("config.twilio_sid");
        $apiKeySid = config("config.twilio_api_key");
        $authToken = config("config.twilio_auth_token");

        $twilio = new Client($accountSid, $authToken);

        try
        {
            DB::table("calls")
                ->where("id", "=", $call->id)
                ->update([
                    "status" => "completed",
                    "end" => now()->utc(),
                    "updated_at" => now()->utc()
                ]);

            // $room = $twilio->video->v1->rooms($call->call_id)->fetch();
            // $roomSid = $room->sid; // ✅ This is what you need

            // $roomSid = "";
            // $rooms = $twilio->video->v1->rooms
            //     ->read(['uniqueName' => $call->call_id]);

            // foreach ($rooms as $room)
            // {
            //     if ($room->status === 'completed')
            //     {
            //         $roomSid = $room->sid;
            //         break;
            //     }
            // }

            // dd($rooms);

            $twilio->video->v1->rooms($call->call_id)->update("completed");
            
            // List recordings for a room
            /*$recordings = $twilio->video->v1->recordings
                ->read(['groupingSid' => $roomSid]);

            foreach ($recordings as $recording)
            {

                if (str_starts_with($recording->sid, 'RO') && $recording->type === 'audio')
                {
                    $mediaSid = $recording->sid;

                    $mediaRedirectUrl = "https://video.twilio.com/v1/Recordings/{$recording->sid}/Media";

                    $guzzle_client = new \GuzzleHttp\Client();

                    $response = $guzzle_client->get($mediaRedirectUrl, [
                        'auth' => [$accountSid, $authToken], // Basic Auth
                        'allow_redirects' => false           // Don’t auto-follow redirect
                    ]);

                    if ($response->getStatusCode() == 302)
                    {
                        $mp4Url = $response->getHeaderLine('Location'); // This is the actual downloadable file
                        // echo "Download URL: $mp4Url\n";

                        // dd($mp4Url);
                    }
                }
            }*/

            return response()->json([
                "status" => "success",
                "message" => "Call ended successfully."
            ]);
        }
        catch (\Exception $e)
        {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function fetch_message()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        if ($me->type == "patient")
        {
            $call = DB::table("calls")
                ->where("call_id", "=", $id)
                ->where("patient_id", "=", $me->id)
                ->first();

            if ($call == null)
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Call not found."
                ]);
            }

            return response()->json([
                "status" => "success",
                "message" => "Message has been fetched.",
                "message_content" => $call->message ?? ""
            ]);
        }

        return response()->json([
            "status" => "error",
            "message" => "Un-authorized."
        ]);
    }

    public function send_message()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required",
            "message" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $me = auth()->user();
        $id = request()->id ?? "";
        $message = request()->message ?? "";

        if ($me->type != "doctor")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $call = DB::table("calls")
            ->where("call_id", "=", $id)
            ->where("doctor_id", "=", $me->id)
            ->where("status", "=", "accepted")
            ->first();

        if ($call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        DB::table("calls")
            ->where("id", "=", $call->id)
            ->update([
                "message" => $message,
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Message has been sent."
        ]);
    }

    public function reject()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        if ($me->type != "doctor")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $call = DB::table("calls")
            ->where("call_id", "=", $id)
            ->where("doctor_id", "=", $me->id)
            ->first();

        if ($call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        DB::table("calls")
            ->where("id", "=", $call->id)
            ->update([
                "status" => "rejected",
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Call has been rejected."
        ]);
    }

    public function group_call_detail()
    {
        $me = auth()->user();
        $id = request()->id ?? "";

        $group_call = DB::table("group_calls")
            ->select("group_calls.*", "patient.name AS p_name", "patient.email AS p_email",
                "patient.profile_image AS p_profile_image")
            ->join("group_call_participants", "group_call_participants.group_call_id", "=", "group_calls.id")
            ->join("users AS patient", "patient.id", "=", "group_calls.patient_id")
            ->where("group_calls.call_id", "=", $id)
            ->where(function ($query) use ($me) {
                $query->where("group_calls.patient_id", "=", $me->id)
                    ->orWhere("group_call_participants.doctor_id", "=", $me->id);
            })
            ->first();

        if ($group_call == null)
        {
            abort(404);
        }

        return view("group-calls/detail", [
            "call" => $group_call
        ]);
    }

    public function reject_group_call()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        if ($me->type != "doctor")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $group_call = DB::table("group_calls")
            ->where("call_id", "=", $id)
            ->where("status", "=", "created")
            ->first();

        if ($group_call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        $group_call_participant = DB::table("group_call_participants")
            ->where("group_call_id", "=", $group_call->id)
            ->where("doctor_id", "=", $me->id)
            ->whereIn("status", ["sent", "accepted", "joined"])
            ->first();

        if ($group_call_participant == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        DB::table("group_call_participants")
            ->where("id", "=", $group_call_participant->id)
            ->update([
                "status" => "rejected",
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Appointment request has been rejected."
        ]);
    }

    public function accept_group_call()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        if ($me->type != "doctor")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $group_call = DB::table("group_calls")
            ->where("call_id", "=", $id)
            ->where("status", "=", "created")
            ->first();

        if ($group_call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        /*$start_at = new \DateTime($group_call->start, new \DateTimeZone("UTC"));
        $now = new \DateTime("now", new \DateTimeZone("UTC"));

        if ($start_at > $now)
        {
            return response()->json([
                "status" => "error",
                "message" => "Sorry, can't join before the time."
            ]);
        }*/

        $group_call_participant = DB::table("group_call_participants")
            ->where("group_call_id", "=", $group_call->id)
            ->where("doctor_id", "=", $me->id)
            ->whereIn("status", ["sent", "rejected", "left"])
            ->first();

        if ($group_call_participant == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        DB::table("group_call_participants")
            ->where("id", "=", $group_call_participant->id)
            ->update([
                "status" => "accepted",
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Appointment request has been accepted."
        ]);
    }

    public function accept()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        if ($me->type != "doctor")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $call = DB::table("calls")
            ->where("call_id", "=", $id)
            ->where("doctor_id", "=", $me->id)
            ->where("status", "=", "calling")
            ->first();

        if ($call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        DB::table("calls")
            ->where("id", "=", $call->id)
            ->update([
                "status" => "accepted",
                "start" => now()->utc(),
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Call has been accepted."
        ]);
    }

    public function is_incoming()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        if ($me->type != "doctor")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $call = DB::table("calls")
            ->where("call_id", "=", $id)
            ->where("doctor_id", "=", $me->id)
            ->where("status", "=", "calling")
            ->first();

        if ($call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        $user = DB::table("users")
            ->where("id", "=", $call->patient_id)
            ->where("type", "=", "patient")
            ->first();

        if ($user == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Patient not found."
            ]);
        }

        $user_obj = (object) [
            "name" => $user->name ?? ""
        ];

        $call_obj = (object) [
            "id" => $call->call_id ?? "",
            "status" => $call->status ?? "",
            "type" => $call->type ?? ""
        ];

        return response()->json([
            "status" => "success",
            "message" => "Call is in-coming.",
            "patient" => $user_obj,
            "call" => $call_obj
        ]);
    }

    public function verify_group_call()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        $group_call = DB::table("group_calls")
            ->select("group_calls.*", "patient.name AS p_name", "patient.email AS p_email",
                "patient.profile_image AS p_profile_image")
            ->join("group_call_participants", "group_call_participants.group_call_id", "=", "group_calls.id")
            ->join("users AS patient", "patient.id", "=", "group_calls.patient_id")
            ->where("group_calls.call_id", "=", $id)
            ->where(function ($query) use ($me) {
                $query->where("group_calls.patient_id", "=", $me->id)
                    ->orWhere("group_call_participants.doctor_id", "=", $me->id);
            })
            ->first();

        if ($group_call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        $accountSid = config("config.twilio_sid");
        $apiKeySid = config("config.twilio_api_key");
        $apiKeySecret = config("config.twilio_secret");
        $authToken = config("config.twilio_auth_token");

        // $twilio = new Client($accountSid, $authToken);

        $identity = "user_" . $me->id;
        $roomName = $group_call->call_id ?? "";

        // Create access token
        $token = new AccessToken($accountSid, $apiKeySid, $apiKeySecret, 3600, $identity);

        // Grant access to Video
        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);
        $token->addGrant($videoGrant);
        $authToken = $token->toJWT();

        return response()->json([
            "status" => "success",
            "message" => "Verified.",
            // "identity" => $identity,
            "token" => $authToken,
            "room" => $roomName
        ]);
    }

    public function verify()
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

        $me = auth()->user();
        $id = request()->id ?? "";

        $call = DB::table("calls")
            ->where("call_id", "=", $id)
            ->where(function ($query) use ($me) {
                $query->where("doctor_id", "=", $me->id)
                    ->orWhere("patient_id", "=", $me->id);
            })
            ->first();

        if ($call == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Call not found."
            ]);
        }

        $user = null;
        if ($call->doctor_id == $me->id)
        {
            $user = DB::table("users")
                ->where("type", "=", "patient")
                ->where("id", "=", $call->patient_id)
                ->first();
        }
        else if ($call->patient_id == $me->id)
        {
            $user = DB::table("users")
                ->select("users.*", "doctors.services", "doctors.specialities")
                ->leftJoin("doctors", "doctors.user_id", "=", "users.id")
                ->where("users.type", "=", "doctor")
                ->where("users.id", "=", $call->doctor_id)
                ->first();
        }

        if ($user == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "User not found."
            ]);
        }

        $accountSid = config("config.twilio_sid");
        $apiKeySid = config("config.twilio_api_key");
        $apiKeySecret = config("config.twilio_secret");
        $authToken = config("config.twilio_auth_token");

        $twilio = new Client($accountSid, $authToken);

        $identity = "user_" . $me->id;
        $roomName = $call->call_id;

        /*try
        {
            $room = $twilio->video->v1->rooms($roomName)->fetch();
        }
        catch (\Exception $e)
        {
            if ($e->getStatusCode() == 404)
            {
                $twilio->video->v1->rooms->create([
                    'uniqueName' => $roomName,
                    'recordParticipantsOnConnect' => true,
                    'type' => 'group', // or 'group-small'
                    // 'statusCallback' => 'https://your-url.com/events',
                    // 'videoCodecs' => ['VP8'], // optional
                    'recordingMode' => 'composed', // THIS is required
                ]);
            }
            else
            {
                // Handle other errors (e.g., network issues, auth errors)
                // dd($e->getMessage());
            }
        }*/

        // Create access token
        $token = new AccessToken($accountSid, $apiKeySid, $apiKeySecret, 3600, $identity);

        // Grant access to Video
        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);
        $token->addGrant($videoGrant);
        $authToken = $token->toJWT();

        return response()->json([
            "status" => "success",
            "message" => "Verified.",
            // "identity" => $identity,
            "token" => $authToken,
            "room" => $roomName
        ]);
    }

    public function detail()
    {
        $me = auth()->user();
        $id = request()->id ?? "";

        $call = DB::table("calls")
            ->where("call_id", "=", $id)
            // ->where(function ($query) use ($me) {
            //     $query->where("doctor_id", "=", $me->id)
            //         ->orWhere("patient_id", "=", $me->id);
            // })
            ->first();

        if ($call == null)
        {
            abort(404);
        }

        $user = null;
        // if ($call->doctor_id == $me->id)
        {
            $user = DB::table("users")
                // ->where("type", "=", "patient")
                // ->where("id", "=", $call->patient_id)
                ->first();
        }
        // else if ($call->patient_id == $me->id)
        // {
        //     $user = DB::table("users")
        //         ->select("users.*", "doctors.services", "doctors.specialities")
        //         ->leftJoin("doctors", "doctors.user_id", "=", "users.id")
        //         ->where("users.type", "=", "doctor")
        //         ->where("users.id", "=", $call->doctor_id)
        //         ->first();
        // }

        if ($user == null)
        {
            abort(404);
        }

        return view("calls/detail", [
            "user" => $user,
            "call" => $call
        ]);
    }

    public function start()
    {
        $validator = Validator::make(request()->all(), [
            "id" => "required",
            "type" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $me = auth()->user();
        $id = request()->id ?? 0;
        $type = request()->type ?? "";

        // TODO:
        /*if ($me->type != "patient")
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }*/

        if (!in_array($type, ["audio", "video"]))
        {
            return response()->json([
                "status" => "error",
                "message" => "In-valid type."
            ]);
        }

        $user = DB::table("users")
            ->where("id", "=", $id)
            ->where("type", "=", "doctor")
            ->whereNull("deleted_at")
            ->first();

        if ($user == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "User not found."
            ]);
        }

        $call_id = uniqid();

        DB::table("calls")
            ->insertGetId([
                "doctor_id" => $user->id,
                "patient_id" => $me->id,
                "call_id" => $call_id,
                "type" => $type,
                "status" => "calling",
                "created_at" => now()->utc(),
                "updated_at" => now()->utc()
            ]);

        return response()->json([
            "status" => "success",
            "message" => "Call has been initiated.",
            "call_id" => $call_id
        ]);
    }
}
