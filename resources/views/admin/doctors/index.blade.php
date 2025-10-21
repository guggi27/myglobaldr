@extends('admin/layouts/app')
@section('title', 'Doctors')

@section('main')

    <div class="pagetitle">
        <div style="display: flex; align-items: center;">
            <h1>Doctors</h1>
            <a href="{{ url('/admin/doctors/add') }}" class="btn btn-outline-primary btn-sm ms-3">
                Add Doctor
            </a>
        </div>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Doctors</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">

                <div class="input-group mb-3">
                    <input id="searchInput" type="text" class="form-control" placeholder="Search doctors">
                    <button id="searchBtn" class="btn btn-primary" type="button">Search</button>
                </div>

                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Profile</th>
                            <th>Specialization</th>
                            <th>Experience (yrs)</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="doctorsTableBody">
                        <tr>
                            <td colspan="8" class="text-center">Loading doctors...</td>
                        </tr>
                    </tbody>
                </table>

                <nav>
                    <ul id="pagination" class="pagination justify-content-center"></ul>
                </nav>
            </div>
        </div>
    </section>

    <script>
        const doctorsTableBody = document.getElementById("doctorsTableBody");
        const paginationEl = document.getElementById("pagination");
        const searchInput = document.getElementById("searchInput");
        const searchBtn = document.getElementById("searchBtn");

        // Add a refresh button dynamically
        const refreshBtn = document.createElement("button");
        refreshBtn.textContent = "Refresh";
        refreshBtn.className = "btn btn-outline-secondary btn-sm ms-2";
        refreshBtn.onclick = () => fetchDoctors(currentPage, searchQuery, orderBy);
        document.querySelector(".pagetitle div").appendChild(refreshBtn);

        let currentPage = 1;
        let lastPage = 1;
        let searchQuery = "";
        let orderBy = "createdAt"; // default order field
        let orderDirection = "desc"; // default direction

        async function fetchDoctors(page = 1, search = "", order = "createdAt", direction = "desc") {
            try {
                doctorsTableBody.innerHTML = `<tr><td colspan="8" class="text-center">Loading...</td></tr>`;

                const response = await axios.get(`{{ env('API_HOST') }}/admin/doctors`, {
                    params: {
                        page,
                        search,
                        orderBy: order,
                        orderDirection: direction
                    },
                    withCredentials: true
                }, );

                const payload = response.data || {};
                renderDoctors(payload.data || []);
                renderPagination(payload.meta || {});
            } catch (err) {
                console.error(err);
                doctorsTableBody.innerHTML = `
                <tr><td colspan="8" class="text-center text-danger">Failed to load doctors</td></tr>
            `;
            }
        }

        function renderDoctors(doctors) {
            if (!doctors.length) {
                doctorsTableBody.innerHTML = `
                <tr><td colspan="8" class="text-center">No doctor found.</td></tr>
            `;
                return;
            }

            doctorsTableBody.innerHTML = doctors.map(doc => `
            <tr>
                <td>${doc.salutation ? doc.salutation.toUpperCase() + " " : ""}${doc.firstName} ${doc.lastName}</td>
                <td>${doc.email}</td>
                <td>
                    ${doc.documents?.profilePicture?.url
                        ? `<img src="{{ env('API_HOST') }}${doc.documents.profilePicture.url}" alt="Profile" width="40" height="40" style="object-fit:cover;border-radius:50%;">`
                        : `<span>No Image</span>`}
                </td>
                <td>${doc.professionalInfo?.specialization || "-"}</td>
                <td>${doc.professionalInfo?.yearsOfExperience ?? "-"}</td>
                <td>
                    <button class="btn btn-sm ${doc.status ? "btn-success" : "btn-warning"}"
                        onclick="toggleStatus('${doc._id}', ${doc.status})">
                        ${doc.status ? "Approved" : "Not Approved"}
                    </button>
                </td>
                <td>${new Date(doc.createdAt).toLocaleDateString()}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="deleteDoctor('${doc._id}', '${doc.email}')">Delete</button>
                    <a class="btn btn-sm btn-secondary" href="/admin/doctors/${doc._id}">Details</a>
                </td>
            </tr>
        `).join("");
        }

        function renderPagination(meta) {
            const {
                currentPage: page,
                totalPages
            } = meta;
            currentPage = page || 1;
            lastPage = totalPages || 1;

            if (lastPage <= 1) {
                paginationEl.innerHTML = "";
                return;
            }

            let pages = "";
            for (let i = 1; i <= lastPage; i++) {
                pages += `
                <li class="page-item ${i === currentPage ? "active" : ""}">
                    <button class="page-link" onclick="fetchDoctors(${i}, '${searchQuery}', '${orderBy}', '${orderDirection}')">${i}</button>
                </li>
            `;
            }
            paginationEl.innerHTML = pages;
        }

        async function deleteDoctor(id, email) {
            const result = await Swal.fire({
                title: email,
                text: "Are you sure you want to delete this doctor?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            });

            if (!result.isConfirmed) return;

            try {
                const response = await axios.post(`{{ env('API_HOST') }}/admin/doctors/delete`, {
                    id
                }, {
                    withCredentials: true
                });

                if (response.data.status === "success") {
                    await Swal.fire("Deleted!", "Doctor removed successfully", "success");
                    fetchDoctors(currentPage, searchQuery, orderBy, orderDirection);
                } else {
                    Swal.fire("Error", response.data.message, "error");
                }
            } catch (error) {
                Swal.fire("Error", error.message, "error");
            }
        }

        async function toggleStatus(id, currentStatus) {
            try {
                const newStatus = !currentStatus;
                const response = await axios.post(`{{ env('API_HOST') }}/admin/doctors/status`, {
                    id,
                    status: newStatus
                }, {
                    withCredentials: true
                });
                console.log(response.data.success)
                if (response.data.success) {
                    Swal.fire("Updated!", `Doctor status set to ${newStatus ? "Active" : "Inactive"}.`, "success");
                    fetchDoctors(currentPage, searchQuery, orderBy, orderDirection);
                } else {
                    Swal.fire("Error", response.data.message, "error");
                }
            } catch (error) {
                Swal.fire("Error", error.message, "error");
            }
        }

        // Search handler
        searchBtn.addEventListener("click", () => {
            searchQuery = searchInput.value.trim();
            fetchDoctors(1, searchQuery, orderBy, orderDirection);
        });

        // Sorting dropdown (optional UI)
        const orderSelect = document.createElement("select");
        orderSelect.className = "form-select form-select-sm w-auto ms-2";
        orderSelect.innerHTML = `
        <option value="createdAt-desc">Newest</option>
        <option value="createdAt-asc">Oldest</option>
        <option value="firstName-asc">Name A-Z</option>
        <option value="firstName-desc">Name Z-A</option>
    `;
        orderSelect.addEventListener("change", () => {
            const [field, dir] = orderSelect.value.split("-");
            orderBy = field;
            orderDirection = dir;
            fetchDoctors(1, searchQuery, orderBy, orderDirection);
        });
        document.querySelector(".pagetitle div").appendChild(orderSelect);

        // Initial load
        fetchDoctors();
    </script>



@endsection
