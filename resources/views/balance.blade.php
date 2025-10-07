@extends ("layouts/app")
@section ("title", "Balance")

@section ("main")

    <section class="bg-white py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Balance ({{ config("config.currency") . " " . (auth()->user()->balance ?? 0) }})</h2>

            <div>
              <label class="block text-sm font-medium text-gray-700">Enter amount</label>
              <input type="number" min="1" step="1" name="amount" id="amount" required
                     class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 px-3 py-2">
            </div>

            <div class="mt-5" id="container-stripe">
              <label class="block text-sm font-medium text-gray-700 mb-3">Enter credit card</label>
              <div id="stripe-card-element"></div>
            </div>

            <div class="mt-5">
              <button type="button"
                    id="btn-add-balance"
                    onclick="addBalance();"
                  class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                Add balance
              </button>
            </div>

        </div>
    </section>

    <script src="https://js.stripe.com/v3" async></script>
    <input type="hidden" id="stripe-publishable-key" value="{{ config('config.stripe_publishable_key') }}" />

    <script>

        let stripe = null;
        let elements = null;
        let cardElement = null;

        async function addBalance() {

            if (stripe == null || elements == null || cardElement == null) {
                return;
            }

            document.getElementById("btn-add-balance").setAttribute("disabled", "disabled");
            const amount = parseFloat(document.getElementById("amount").value || "0");

            try {
                const formData = new FormData();
                formData.append("amount", amount);

                const response = await axios.post(
                    baseUrl + "/payments/fetch-stripe-intent",
                    formData,
                    {
                        headers: {
                            Authorization: "Bearer"
                        }
                    }
                );

                if (response.data.status == "success") {
                    const clientSecret = response.data.client_secret;

                    // execute the payment
                    stripe
                        .confirmCardPayment(clientSecret, {
                            payment_method: {
                                    card: cardElement,
                                    billing_details: {
                                        "email": user.email || "",
                                        "name": user.name || "",
                                        // "phone": 0
                                    },
                                },
                            })
                            .then(async function(result) {
                 
                                // Handle result.error or result.paymentIntent
                                if (result.error) {
                                    console.log(result.error);
                                    swal.fire("Error", result.error.message, "error");
                                    
                                    document.getElementById("btn-add-balance").removeAttribute("disabled");
                                } else {
                                    console.log("The card has been verified successfully...", result.paymentIntent.id);
                 
                                    try {
                                        const formData2 = new FormData();
                                        formData2.append("payment_id", result.paymentIntent.id);

                                        const response2 = await axios.post(
                                            baseUrl + "/payments/verify-stripe",
                                            formData2
                                        );

                                        if (response2.data.status == "success") {
                                            swal.fire("Balance", response2.data.message, "success")
                                                .then(function () {
                                                    window.location.reload();
                                                });
                                        } else {
                                            swal.fire("Error", response2.data.message, "error");
                                        }
                                    } catch (exp) {
                                        //
                                    } finally {
                                        document.getElementById("btn-add-balance").removeAttribute("disabled");
                                    }
                                }
                            });
                } else {
                    swal.fire("Error", response.data.message, "error");

                    document.getElementById("btn-add-balance").removeAttribute("disabled");
                }
            } catch (exp) {
                console.log(exp.message);
                
                document.getElementById("btn-add-balance").removeAttribute("disabled");
            }
        }

        window.addEventListener("load", function () {
            stripe = Stripe(document.getElementById("stripe-publishable-key").value || "");
            elements = stripe.elements();
            cardElement = elements.create('card');
            cardElement.mount('#stripe-card-element');
        });
    </script>

@endsection