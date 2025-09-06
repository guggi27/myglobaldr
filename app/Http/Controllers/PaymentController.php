<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Validator;

class PaymentController extends Controller
{
    public function verify_stripe()
    {
        $validator = Validator::make(request()->all(), [
            "payment_id" => "required"
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
        $stripe_secret_key = config("config.stripe_secret_key") ?? "";

        if (empty($stripe_secret_key))
        {
            return response()->json([
                "status" => "error",
                "message" => "Sorry, we are not receiving payments from Stripe right now."
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

                DB::table("users")
                    ->where("id", "=", $user->id)
                    ->update([
                        "balance" => ($user->balance ?? 0) + ($payment->amount / 100),
                        "updated_at" => now()->utc()
                    ]);

                DB::table("payments")
                    ->insertGetId([
                        "user_id" => $user->id,
                        "type" => "balance",
                        "table_id" => 0,
                        "method" => "stripe",
                        "status" => $payment->status,
                        "amount" => $payment->amount / 100,
                        "payment_obj" => json_encode($payment, JSON_PRETTY_PRINT),
                        "created_at" => now()->utc(),
                        "updated_at" => now()->utc()
                    ]);

                return response()->json([
                    "status" => "success",
                    "message" => "Balance has been added."
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
