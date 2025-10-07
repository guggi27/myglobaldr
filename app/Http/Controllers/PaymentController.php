<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;

use App\Modules\Appointment;

class PaymentController extends Controller
{
    public function verify_stripe()
    {
        $validator = Validator::make(request()->all(), [
            "payment_id" => "required",
            "type" => "required",
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $payment_id = request()->payment_id ?? "";
        $type = request()->type ?? "";
        $appointment_id = request()->appointment_id ?? "";
        $stripe_secret_key = config("config.stripe_secret_key") ?? "";

        if (empty($stripe_secret_key))
        {
            return response()->json([
                "status" => "error",
                "message" => "Sorry, we are not receiving payments from Stripe right now."
            ]);
        }

        if (!in_array($type, ["balance", "appointment"]))
        {
            return response()->json([
                "status" => "error",
                "message" => "Invalid type '" . $type . "'."
            ]);
        }

        $appointment = new Appointment();
        $appointment->id = $appointment_id;
        $appointment = $appointment->fetch_by_id();

        if ($appointment == null)
        {
            return response()->json([
                "status" => "error",
                "message" => "Appointment not found."
            ]);
        }

        if ($appointment->user_id != $user->id)
        {
            return response()->json([
                "status" => "error",
                "message" => "Un-authorized."
            ]);
        }

        $stripe = new \Stripe\StripeClient($stripe_secret_key);

        try
        {
            $payment = $stripe->paymentIntents->retrieve(
                $payment_id,
                []
            );
     
            if ($payment->status == "succeeded")
            {
                $payment_obj = [
                    "user_id" => $user->id,
                    "type" => $type,
                    "method" => "stripe",
                    "status" => $payment->status,
                    "amount" => $payment->amount / 100,
                    "payment_obj" => json_encode($payment, JSON_PRETTY_PRINT),
                    "created_at" => now()->utc(),
                    "updated_at" => now()->utc()
                ];

                if ($type == "balance")
                {
                    $payment_obj["table_id"] = 0;

                    DB::table("users")
                        ->where("id", "=", $user->id)
                        ->update([
                            "balance" => ($user->balance ?? 0) + ($payment->amount / 100),
                            "updated_at" => now()->utc()
                        ]);
                }
                else if ($type == "appointment")
                {
                    $payment_obj["table_id"] = $appointment->id ?? 0;

                    $call_id = DB::table("calls")
                        ->insertGetId([
                            "doctor_id" => $appointment->doctor_user_id,
                            "patient_id" => $user->id,
                            "call_id" => uniqid(),
                            "type" => $appointment->type ?? "",
                            "created_at" => now()->utc(),
                            "updated_at" => now()->utc(),
                        ]);

                    $appointment_update = new Appointment();
                    $appointment_update->id = $appointment->id ?? 0;
                    $appointment_update->payment_status = "paid";
                    $appointment_update->call_id = $call_id;
                    $appointment_update->update();
                }

                DB::table("payments")
                    ->insertGetId($payment_obj);

                return response()->json([
                    "status" => "success",
                    "message" => "Payment has been made."
                ]);
            }
            else
            {
                return response()->json([
                    "status" => "error",
                    "message" => "Payment status is '" . $payment->status . "'."
                ]);
            }
        }
        catch (\Exception $exp)
        {
            return response()->json([
                "status" => "error",
                "message" => $exp->getMessage()
            ]);
        }
    }

    public function fetch_stripe_intent()
    {
        $validator = Validator::make(request()->all(), [
            "amount" => "required"
        ]);

        if ($validator->fails())
        {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()->first()
            ]);
        }

        $user = auth()->user();
        $amount = request()->amount ?? 0;
        $stripe_secret_key = config("config.stripe_secret_key") ?? "";

        if (empty($stripe_secret_key))
        {
            return response()->json([
                "status" => "error",
                "message" => "Sorry, we are not receiving payments from Stripe right now."
            ]);
        }
 
        $stripe = new \Stripe\StripeClient($stripe_secret_key);
     
        // creating setup intent
        $payment_intent = $stripe->paymentIntents->create([
            'payment_method_types' => ['card'],
     
            // convert double to integer for stripe payment intent, multiply by 100 is required for stripe
            'amount' => round($amount) * 100,
            'currency' => strtolower(config("config.currency")),
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Payment intent has been fetched.",
            "client_secret" => $payment_intent->client_secret
        ]);
    }
}
