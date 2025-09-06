<?php

function fetch_setting($key)
{
    $cache_key = "settings_" . $key;
    if (cache()->has($cache_key))
    {
        return cache()->get($cache_key);
    }

    $setting = DB::table("settings")
        ->where("setting_key", "=", $key)
        ->first();

    if ($setting == null)
    {
        return "";
    }

    // cache()->put($cache_key, $setting->setting_value ?? "", 2592000); // 30 days
    cache()->forever($cache_key, $setting->setting_value ?? "");
    return $setting->setting_value ?? "";
}

function fetch_setting_by_key($key, $settings)
{
    foreach ($settings as $setting)
    {
        if ($setting->setting_key == $key)
        {
            return $setting->setting_value ?? "";
        }
    }
    return "";
}

function get_areas_of_interest()
{
    $arr = [
        [ "name" => "Diabetes", "bg_color" => "#E1BEE7", "url" => url("/areas?type=?diabetes"), "img" => asset('/img/areas-of-interest/diabetes.png') ],
        [ "name" => "Hypertension", "bg_color" => "#F8BBD0", "url" => url("/areas?type=?hypertension"), "img" => asset('/img/areas-of-interest/blood_pressure.png') ],
        [ "name" => "Angry", "bg_color" => "#FFF59D", "url" => url("/areas?type=?angry"), "img" => asset('/img/areas-of-interest/angry.png') ],
        [ "name" => "Coughing", "bg_color" => "#B3E5FC", "url" => url("/areas?type=?coughing"), "img" => asset('/img/areas-of-interest/coughing_alt.png') ],
        [ "name" => "Geriatrics", "bg_color" => "#B2DFDB", "url" => url("/areas?type=?geriatrics"), "img" => asset('/img/areas-of-interest/geriatrics.png') ],
        [ "name" => "Diarrhea", "bg_color" => "#B3E5FC", "url" => url("/areas?type=?diarrhea"), "img" => asset('/img/areas-of-interest/diarrhea.png') ],
        [ "name" => "Urology", "bg_color" => "#FFE0B2", "url" => url("/areas?type=?urology"), "img" => asset('/img/areas-of-interest/urology.png') ],
        [ "name" => "Allergies", "bg_color" => "#E1BEE7", "url" => url("/areas?type=?allergies"), "img" => asset('/img/areas-of-interest/allergies.png') ],
        [ "name" => "Headache", "bg_color" => "#F8BBD0", "url" => url("/areas?type=?headache"), "img" => asset('/img/areas-of-interest/headache.png') ],
        [ "name" => "Virus ALT", "bg_color" => "#F0F4C3", "url" => url("/areas?type=?virus_alt"), "img" => asset('/img/areas-of-interest/virus_alt.png') ],
        [ "name" => "Hematology", "bg_color" => "#FECDD2", "url" => url("/areas?type=?hematology"), "img" => asset('/img/areas-of-interest/hematology.png') ],
        [ "name" => "Lungs", "bg_color" => "#B3E5FC", "url" => url("/areas?type=?lungs"), "img" => asset('/img/areas-of-interest/lungs.png') ],
        [ "name" => "Mosquito", "bg_color" => "#FFE0B2", "url" => url("/areas?type=?mosquito"), "img" => asset('/img/areas-of-interest/mosquito.png') ],
    ];

    foreach ($arr as $key => $value)
    {
        $arr[$key] = (object) $value;
    }

    return $arr;
}

if (!function_exists ("fetch_group_calls"))
{
    function fetch_group_calls()
    {
        $user = null;

        if (auth()->check())
        {
            $user = auth()->user();
        }

        if ($user == null)
        {
            return [];
        }

        if ($user->type != "doctor")
        {
            return [];
        }

        $time_zone = session()->get(config("config.session_timezone_key"), "");
        if (!empty($time_zone))
        {
            date_default_timezone_set($time_zone);
        }

        $group_call_participants = \DB::table("group_call_participants")
            ->select("group_call_participants.*", "group_calls.call_id", "group_calls.patient_id",
                "group_calls.diseases", "group_calls.start AS gc_start",
                "patient.name AS p_name", "patient.profile_image AS p_profile_image")
            ->join("group_calls", "group_calls.id", "=", "group_call_participants.group_call_id")
            ->join("users AS patient", "patient.id", "=", "group_calls.patient_id")
            ->where("group_call_participants.doctor_id", "=", $user->id)
            ->where("group_call_participants.status", "=", "sent")
            ->where("group_calls.status", "=", "created")
            ->get();

        $arr = [];

        foreach ($group_call_participants as $group_call_participant)
        {
            $obj = [
                "id" => $group_call_participant->id ?? 0,
                "call_id" => $group_call_participant->call_id ?? "",
                "p_id" => $group_call_participant->patient_id ?? 0,
                "p_name" => $group_call_participant->p_name ?? "",
                "p_profile_image" => $group_call_participant->p_profile_image ?? "",
                "diseases" => json_decode($group_call_participant->diseases ?? "[]") ?? [],
                "start" => ""
            ];

            if ($group_call_participant->gc_start)
            {
                $obj["start"] = date("d F, Y h:i a", strtotime($group_call_participant->gc_start . " UTC"));
            }

            if ($obj["p_profile_image"] && \Storage::exists("public/" . $obj["p_profile_image"]))
            {
                $obj["p_profile_image"] = url("/storage/" . $obj["p_profile_image"]);
            }

            array_push($arr, (object) $obj);
        }

        return $arr;
    }
}

if (!function_exists ("pad_zero"))
{
    function pad_zero($value)
    {
        $value = (double) $value;
        if ($value < 10 && $value > 0)
        {
            return "0" . $value;
        }
        return $value;
    }
}

if (!function_exists ("get_duration"))
{
    function get_duration($startString, $endString)
    {

        if (empty($startString) || empty($endString))
        {
            return "";
        }

        $startString = str_replace(",", "", $startString);
        $endString = str_replace(",", "", $endString);

        // Create DateTime objects
        $start = new \DateTime($startString);
        $end   = new \DateTime($endString);

        // Difference as DateInterval
        $interval = $start->diff($end);

        $str = "";
        if ($interval->days > 0)
        {
            $str .= pad_zero($interval->days) . "d ";
        }

        if ($interval->h > 0)
        {
            $str .= pad_zero($interval->h) . "h ";
        }

        if ($interval->i > 0)
        {
            $str .= pad_zero($interval->i) . "m ";
        }

        if ($interval->s > 0)
        {
            $str .= pad_zero($interval->s) . "s ";
        }

        $str = substr($str, 0, strlen($str) - 1);

        return $str;
    }
}

if (!function_exists ("random_color"))
{
    function random_color()
    {
        $colors = [
            '#1E3A8A', // blue-800
            '#047857', // emerald-700
            '#9333EA', // purple-600
            '#F59E0B', // amber-500
            '#EF4444', // red-500
        ];

        return $colors[array_rand($colors)];
    }
}

if (!function_exists("super_admin_auth"))
{
    function super_admin_auth()
    {
        if (!auth()->check())
        {
            return response()->json([
                "status" => "error",
                "message" => "Not logged-in."
            ])->throwResponse();
        }

        if (!in_array(auth()->user()->type, ["super_admin"]))
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ])->throwResponse();
        }
    }
}

if (!function_exists("admin_auth"))
{
    function admin_auth()
    {
        if (!auth()->check())
        {
            return response()->json([
                "status" => "error",
                "message" => "Not logged-in."
            ])->throwResponse();
        }

        if (!in_array(auth()->user()->type, ["admin", "super_admin"]))
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ])->throwResponse();
        }
    }
}

if (!function_exists("validate_keys"))
{
    function validate_keys($arr, $allowed_keys)
    {
        $keys = array_keys($arr);
        $extraKeys = array_diff($keys, $allowed_keys);
        $missingKeys = array_diff($allowed_keys, $keys);
        return empty($extraKeys) && empty($missingKeys);
    }
}