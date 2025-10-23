@extends('admin/layouts/app')
@section('title', 'Doctor Details')

@section('main')
    <div class="container mt-4 mb-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Doctor Details</h5>
                <small class="text-muted">Preview and verify doctor data</small>
            </div>

            <div class="card-body" id="doctorDetails">
                <div class="text-center text-muted py-5">Loading doctor information...</div>
            </div>
        </div>

        <div class="card mt-4 shadow-sm border-0">
            <div class="card-header bg-light fw-bold">Documents</div>
            <div class="card-body" id="documentsArea">
                <div class="text-center text-muted py-4">No documents available</div>
            </div>
        </div>

        <div class="card mt-4 shadow-sm border-0">
            <div class="card-header bg-light fw-bold">Raw JSON</div>
            <div class="card-body">
                <pre id="rawPre" class="bg-dark text-white p-3 rounded small overflow-auto">{}</pre>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const id = "{{ $id ?? '' }}".trim();
            if (!id) {
                Swal.fire("Missing ID", "Doctor ID is not provided.", "warning");
                return;
            }

            const detailsEl = document.getElementById("doctorDetails");
            const docsEl = document.getElementById("documentsArea");
            const rawEl = document.getElementById("rawPre");

            function createRow(label, value) {
                return `
            <tr>
                <th style="width: 200px">${label}</th>
                <td>${value || '—'}</td>
            </tr>
        `;
            }

            function renderProfile(data) {
                const addr = data.address ? [
                    data.address.streetAddress,
                    data.address.addressLine2,
                    data.address.city,
                    data.address.state,
                    data.address.country
                ].filter(Boolean).join(', ') : '—';

                return `
            <table class="table table-bordered mb-4">
                <tbody>
                    ${createRow("Full Name", [data.salutation, data.firstName, data.lastName].filter(Boolean).join(" "))}
                    ${createRow("Gender", data.gender)}
                    ${createRow("Nationality", data.nationality)}
                    ${createRow("Email", data.email)}
                    ${createRow("Phone", data.phone)}
                    ${createRow("Address", addr)}
                </tbody>
            </table>
        `;
            }

            function renderProfessional(data) {
                const p = data.professionalInfo || {};
                const workspace = p.currentWorkspace ? [
                    p.currentWorkspace.hospitalClinicName,
                    p.currentWorkspace.city,
                    p.currentWorkspace.country
                ].filter(Boolean).join(', ') : '—';

                return `
            <h6 class="fw-bold mb-3">Professional Info</h6>
            <table class="table table-bordered mb-4">
                <tbody>
                    ${createRow("Specialization", p.specialization)}
                    ${createRow("Sub-specialization", p.subSpecialization)}
                    ${createRow("Position", p.position)}
                    ${createRow("Years of Experience", p.yearsOfExperience)}
                    ${createRow("Current Workspace", workspace)}
                </tbody>
            </table>
        `;
            }

            function renderEducation(data) {
                const e = data.education || {};
                return `
            <h6 class="fw-bold mb-3">Education</h6>
            <table class="table table-bordered mb-4">
                <tbody>
                    ${createRow("Medical Degree", e.medicalDegree)}
                    ${createRow("Institution", e.institutionName)}
                    ${createRow("Graduation Date", e.graduationDate ? new Date(e.graduationDate).toLocaleDateString() : '—')}
                    ${createRow("Certifications", e.additionalCertifications)}
                </tbody>
            </table>
        `;
            }

            function renderAffiliations(data) {
                const a = data.affiliations || {};
                return `
            <h6 class="fw-bold mb-3">Affiliations & Preferences</h6>
            <table class="table table-bordered">
                <tbody>
                    ${createRow("Memberships", a.memberships)}
                    ${createRow("Licensing Bodies", a.licensingBodies)}
                    ${createRow("International Patients", data.internationalPatients)}
                    ${createRow("Preferred Communication", (data.preferredCommunication || []).join(', ') || '—')}
                    ${createRow("Payment Methods", (data.paymentMethods || []).join(', ') || '—')}
                </tbody>
            </table>
        `;
            }

            function renderDocuments(docs) {
                docsEl.innerHTML = '';
                const keys = [{
                        key: 'medicalLicense',
                        label: 'Medical License'
                    },
                    {
                        key: 'degreeCertificates',
                        label: 'Degree Certificates'
                    },
                    {
                        key: 'certifications',
                        label: 'Certifications'
                    },
                    {
                        key: 'resumeCV',
                        label: 'Resume / CV'
                    },
                    {
                        key: 'profilePicture',
                        label: 'Profile Picture'
                    },
                    {
                        key: 'signature',
                        label: 'Signature'
                    }
                ];

                let hasDocs = false;
                keys.forEach(({
                    key,
                    label
                }) => {
                    const obj = docs[key];
                    if (obj) {
                        hasDocs = true;
                        const files = Array.isArray(obj) ? obj : [obj];
                        files.forEach(file => {
                            if (!file.url) return;
                            const btn = document.createElement('a');
                            btn.href = "{{ env('API_HOST') }}" + file.url;
                            btn.target = "_blank";
                            btn.className = "btn btn-outline-primary btn-sm mb-2 me-2";
                            btn.innerHTML =
                                `${label}: ${file.name || ''} ${file.verified ? '<span class="badge bg-success ms-1">Verified</span>' : '<span class="badge bg-secondary ms-1">Pending</span>'}`;
                            docsEl.appendChild(btn);
                        });
                    }
                });

                if (!hasDocs) {
                    docsEl.innerHTML = '<div class="text-center text-muted py-4">No documents available</div>';
                }
            }

            try {
                Swal.fire({
                    title: "Loading data...",
                    html: '<div style="display:flex;justify-content:center;align-items:center;gap:10px;overflow:hidden;"><div class="spinner" style="width:24px;height:24px;border:3px solid #ccc;border-top:3px solid #3085d6;border-radius:50%;animation:spin 1s linear infinite;"></div><span>Please wait</span></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        // Spinner animation
                        const style = document.createElement('style');
                        style.innerHTML = `
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                `;
                        document.head.appendChild(style);
                    }
                });
                const response = await axios.get(`{{ env('API_HOST') }}/admin/doctors/${id}`, {
                    withCredentials: true
                });
                const {
                    success,
                    message,
                    doctor: data
                } = response.data;

                if (!success || !data) {
                    detailsEl.innerHTML =
                        `<div class="text-center text-danger py-5">${message || "Doctor not found"}</div>`;
                    return;
                }

                detailsEl.innerHTML = `
            <h5 class="fw-bold mb-3">Profile</h5>
            ${renderProfile(data)}
            ${renderProfessional(data)}
            ${renderEducation(data)}
            ${renderAffiliations(data)}
        `;
                renderDocuments(data.documents || {});
                rawEl.textContent = JSON.stringify(data, null, 2);
            } catch (err) {
                if (err?.response?.status === 401) {
                    Swal.fire("Error", "Please login again", "error").then(() => {
                        window.location.href = '{{ url('/admin/login') }}';
                    });
                    return;
                }
                console.error(err);
                detailsEl.innerHTML =
                    `<div class="text-center text-danger py-5">Failed to fetch doctor data.</div>`;
            } finally {
                Swal.close();
            }
        });
    </script>
@endsection
