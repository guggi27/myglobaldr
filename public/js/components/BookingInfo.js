function BookingInfo() {
  const [appointmentId, setAppointmentId] = React.useState(localStorage.getItem("appointment_id"));
  const [appointment, setAppointment] = React.useState(null);
  const [selectedAppointmentType, setSelectedAppointmentType] = React.useState(localStorage.getItem("selected_appointment_type"));
  const [selectedService, setSelectedService] = React.useState(JSON.parse(localStorage.getItem("selected_service") || "{}"));
  const [selectedSlot, setSelectedSlot] = React.useState(JSON.parse(localStorage.getItem("selected_slot") || "{}"));
  const [total, setTotal] = React.useState(0);

  const [stripe, setStripe] = React.useState(null);
  const [elements, setElements] = React.useState(null);
  const [cardElement, setCardElement] = React.useState(null);
  const [paying, setPaying] = React.useState(false);

  async function fetchAppointmentDetails() {
    try {
      const formData = new FormData();
      formData.append("id", appointmentId);

      const response = await axios.post(
        baseUrl + "/api/appointments/fetch",
        formData
      );

      if (response.data.status == "success") {
        setAppointment(response.data.appointment);
      } else {
        swal.fire("Error", response.data.message, "error");
      }
    } catch (exp) {
      console.log(exp.message);
      // swal.fire("Error", exp.message, "error")
    }
  }

  async function payViaStripe() {
    if (stripe == null || elements == null || cardElement == null) {
      return;
    }

    setPaying(true);

    try {
      const formData = new FormData();
      formData.append("amount", total);

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
              
              setPaying(false);
            } else {
              console.log("The card has been verified successfully...", result.paymentIntent.id);

              try {
                const formData2 = new FormData();
                formData2.append("payment_id", result.paymentIntent.id);
                formData2.append("type", "appointment");
                formData2.append("appointment_id", appointmentId);

                const response2 = await axios.post(
                  baseUrl + "/payments/verify-stripe",
                  formData2
                );

                if (response2.data.status == "success") {
                  swal.fire("Payment", response2.data.message, "success")
                    .then(function () {
                      window.location.reload();
                    });
                } else {
                  swal.fire("Error", response2.data.message, "error");
                }
              } catch (exp) {
                //
              } finally {
                setPaying(false);
              }
            }
          });
      } else {
        swal.fire("Error", response.data.message, "error");
        setPaying(false);
      }
    } catch (exp) {
      console.log(exp.message);
      setPaying(false);
    }
  }

  React.useEffect(function () {
    const consultationFee = appointment?.doctor?.fee || 0;
    const selectedServicePrice = parseFloat(selectedService.price || "0");
    const discount = appointment?.doctor?.discount || 0;
    setTotal(consultationFee + selectedServicePrice - discount);
  }, [appointment, selectedService]);

  React.useEffect(function () {
    fetchAppointmentDetails();

    const stripe = Stripe(document.getElementById("stripe-publishable-key").value || "");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#stripe-card-element');

    setStripe(stripe);
    setElements(elements);
    setCardElement(cardElement);
  }, []);

  return (
    <>
      <h2>Booking info</h2>

      <p><b>Date & time:</b></p>
      <p>{ selectedSlot?.day } { selectedSlot?.slot }</p>

      <p><b>Appointment with:</b></p>
      <p>{ appointment?.doctor?.name || "" } - { selectedService.name } - at { selectedService.price } PKR -
        via <span style={{
          textTransform: "capitalize"
        }}>{ selectedAppointmentType.split("_").join(" ") }</span>
      </p>

      <p>Consultation fee: { appointment?.doctor?.fee } PKR</p>
      <p>{ selectedService.name } fee: { selectedService.price } PKR</p>
      <p>Discount: { appointment?.doctor?.discount } PKR</p>      

      <p>Total: { total } PKR</p>

      <div className="row" id="container-stripe">
        <div className="col-md-6">
          <label className="block text-sm font-medium text-gray-700 mb-3">Enter card details</label>
          <div id="stripe-card-element"></div>

          <button type="button" onClick={ function () {
            payViaStripe();
          } } disabled={ paying }>Pay now</button>
        </div>
      </div>
    </>
  );
}