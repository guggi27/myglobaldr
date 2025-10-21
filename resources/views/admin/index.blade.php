@extends ("admin/layouts/app")
@section('title', 'Dashboard')

@section('main')
    <section class="section dashboard">

        <div class="row g-4">

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-user-doctor" style="color:#4154f1;"></i>
                    </div>
                    <div>
                        <h5>Doctors</h5>
                        <h6 id="doctors-number"></h6>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-hospital-user" style="color: mediumseagreen;"></i>
                    </div>
                    <div>
                        <h5>Patients</h5>
                        <h6 id="patients-number"></h6>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-phone" style="color: skyblue;"></i>
                    </div>
                    <div>
                        <h5>Calls</h5>
                        <h6 id="calls-number"></h6>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="fa-solid fa-money-bill" style="color: darkorange;"></i>
                    </div>
                    <div>
                        <h5>Payments</h5>
                        <h6 id="payments-number"></h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="bi-solid bi-bag-dash-fill" style="color: magenta;"></i>
                    </div>
                    <div>
                        <h5>New Tasks</h5>
                        <h6 id="tasks-number"></h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
                <div class="info-card">
                    <div class="card-icon">
                        <i class="bi-solid bi-archive-fill" style="color: darkgray;"></i>
                    </div>
                    <div>
                        <h5>Total Projects</h5>
                        <h6 id="projects-number"></h6>
                    </div>
                </div>
            </div>
    </section>

    <script>
        async function onInit() {
            const doctorsNumber = document.getElementById('doctors-number');
            const patientsNumber = document.getElementById('patients-number');
            const callsNumber = document.getElementById('calls-number');
            const tasksNumber = document.getElementById('tasks-number');
            const paymentsNumber = document.getElementById('payments-number');
            const projectsNumber = document.getElementById('projects-number');
            try {
                const {
                    data
                } = await axios.get('{{ env('API_HOST') }}/admin/dashboard', {
                    withCredentials: true
                });

                if (data.success) {
                    doctorsNumber.innerText = data.doctors;
                    patientsNumber.innerText = data.patients;
                    callsNumber.innerText = data.calls;
                    tasksNumber.innerText = data.tasks;
                    paymentsNumber.innerText = data.payments;
                    projectsNumber.innerText = data.projects;
                } else {
                    Swal.fire("Error", data.message, "error");
                }
            } catch (err) {
                Swal.fire("Error", err.message, "error");
            } finally {
                // submitButton.removeAttribute("disabled");
            }

        }
        onInit();
    </script>

@endsection
