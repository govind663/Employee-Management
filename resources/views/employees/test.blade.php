<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Title --}}
    <title>Employee Management</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            font-family: system-ui, -apple-system, BlinkMacSystemFont;
        }

        .card {
            border-radius: 16px;
            border: none;
        }

        .card-header {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #fff;
            border-radius: 16px 16px 0 0;
        }

        .modal-content {
            border-radius: 18px;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
        }

        .error-text {
            color: red;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .input-error {
            border-color: #ff0505 !important;
            background-color: #ffffff;
        }

        #designationList {
            max-height: 180px;
            overflow-y: auto;
            border-radius: 10px;
            border: 1px solid #cf0e0e;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between">
                <h4>👨‍💼 Employee Management</h4>
                <button class="btn btn-warning" id="btnAddEmployee">➕ Add Employee</button>
            </div>

            <div class="card-body">
                <table id="employeeTable" class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Gender</th>
                            <th>Designation</th>
                            <th>Department</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="employeeModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalTitle">➕ Add Employee</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="employeeForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="employee_id" id="employee_id">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label><b>Name : <span class="text-danger">*</span></b></label>
                                <input type="text" name="name" id="name" class="form-control">
                                <small class="error-text" data-error="name"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><b>Email : <span class="text-danger">*</span></b></label>
                                <input type="email" name="email_id" id="email_id" class="form-control">
                                <small class="error-text" data-error="email_id"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><b>Mobile Number : <span class="text-danger">*</span></b></label>
                                <input type="text" name="mobile_no" id="mobile_no" class="form-control">
                                <small class="error-text" data-error="mobile_no"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><b>Gender : <span class="text-danger">*</span></b></label><br>
                                <input type="radio" name="gender" value="Male"> Male
                                <input type="radio" name="gender" value="Female" class="ms-3"> Female
                                <small class="error-text" data-error="gender"></small>
                            </div>

                            <div class="col-md-6 mb-3 position-relative">
                                <label><b>Designation : <span class="text-danger">*</span></b></label>
                                <input type="text" name="designation" id="designation" class="form-control"
                                    autocomplete="off">
                                <div id="designationList" class="list-group d-none position-absolute w-100"></div>
                                <small class="error-text" data-error="designation"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><b>Department : <span class="text-danger">*</span></b></label>
                                <select name="department[]" multiple class="form-select">
                                    <option>Marketing</option>
                                    <option>Finance</option>
                                    <option>HR</option>
                                    <option>Sales</option>
                                    <option>IT</option>
                                </select>
                                <small class="error-text" data-error="department"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><b>State : <span class="text-danger">*</span></b></label>
                                <select name="state_id" id="state" class="form-select">
                                    <option value="">Select</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                <small class="error-text" data-error="state_id"></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label><b>City : <span class="text-danger">*</span></b></label>
                                <select name="city_id" id="city" class="form-select">
                                    <option value="">Select</option>
                                </select>
                                <small class="error-text" data-error="city_id"></small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label><b>Address : <span class="text-danger">*</span></b></label>
                                <textarea name="address" id="address" class="form-control"></textarea>
                                <small class="error-text" data-error="address"></small>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label><b>Documents : <span class="text-danger">*</span></b></label><br>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="document_type[]"
                                        value="pan" id="doc_pan">
                                    <label class="form-check-label" for="doc_pan">PAN</label>
                                </div>
                                <input type="file" name="pan_file" class="form-control mb-2">

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="document_type[]"
                                        value="aadhaar" id="doc_aadhaar">
                                    <label class="form-check-label" for="doc_aadhaar">Aadhaar</label>
                                </div>
                                <input type="file" name="aadhaar_file" class="form-control">

                                <!-- ERROR BOXES -->
                                <small class="error-text" data-error="document_type"></small>
                                <small class="error-text" data-error="pan_file"></small>
                                <small class="error-text" data-error="aadhaar_file"></small>
                            </div>

                        </div>

                        <div class="text-end">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index:9999">
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(function() {

            /* ================= DATATABLE ================= */
            let table = $('#employeeTable').DataTable({
                ajax: "{{ route('employees.index') }}",
                columns: [{
                        data: null,
                        render: (d, t, r, m) => m.row + 1
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'email_id'
                    },
                    {
                        data: 'mobile_no'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: 'designation'
                    },
                    {
                        data: 'department',
                        render: d => d ? d.join(', ') : ''
                    },
                    {
                        data: 'state.name'
                    },
                    {
                        data: 'city.name'
                    },
                    {
                        data: null,
                        render: r => `
                            <button class="btn btn-sm btn-warning btnEdit" data-id="${r.employee_id}">Edit</button>
                            <button class="btn btn-sm btn-danger btnDelete" data-id="${r.employee_id}">Delete</button>
                        `
                    }
                ]
            });

            /* ================= BOOTSTRAP MODAL ================= */
            const employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));

            /* ================= ADD ================= */
            $('#btnAddEmployee').on('click', function() {
                $('#employeeForm')[0].reset();
                $('.error-text').text('');
                $('.input-error').removeClass('input-error');
                $('.file-preview').remove();
                $('#employee_id').val('');
                $('#modalTitle').text('➕ Add Employee');

                // ADD MODE → file required
                $('input[name="pan_file"], input[name="aadhaar_file"]').prop('required', false);

                employeeModal.show();
            });

            /* ================= EDIT ================= */
            $(document).on('click', '.btnEdit', function() {

                let id = $(this).data('id');

                $('#employeeForm')[0].reset();
                $('.error-text').text('');
                $('.input-error').removeClass('input-error');
                $('.file-preview').remove();

                $.get(`/employees/${id}/edit`, function(res) {

                    $('#modalTitle').text('✏️ Edit Employee');
                    $('#employee_id').val(res.employee_id);

                    $('#name').val(res.name);
                    $('#email_id').val(res.email_id);
                    $('#mobile_no').val(res.mobile_no);
                    $('#designation').val(res.designation);
                    $('#address').val(res.address);

                    $('input[name="gender"][value="' + res.gender + '"]').prop('checked', true);
                    $('select[name="department[]"]').val(res.department);

                    $('#state').val(res.state_id).trigger('change');
                    $(document).one('citiesLoaded', () => $('#city').val(res.city_id));

                    /* DOCUMENT CHECKBOX */
                    $('#doc_pan').prop('checked', res.documents?.includes('pan'));
                    $('#doc_aadhaar').prop('checked', res.documents?.includes('aadhaar'));

                    /* FILE NOT REQUIRED IN UPDATE */
                    $('input[name="pan_file"], input[name="aadhaar_file"]').prop('required', false);

                    if (res.pan_file) {
                        $('input[name="pan_file"]').after(`
                            <div class="file-preview mt-1">
                                <a href="/storage/${res.pan_file}" target="_blank">View PAN</a>
                            </div>
                        `);
                    }

                    if (res.aadhaar_file) {
                        $('input[name="aadhaar_file"]').after(`
                            <div class="file-preview mt-1">
                                <a href="/storage/${res.aadhaar_file}" target="_blank">View Aadhaar</a>
                            </div>
                        `);
                    }

                    employeeModal.show();
                });
            });

            /* ================= SUBMIT ================= */
            $('#employeeForm').on('submit', function(e) {
                e.preventDefault();

                $('.error-text').text('');
                $('.input-error').removeClass('input-error');

                let employee_id = $('#employee_id').val();
                let url = employee_id ? `/employees/${employee_id}` : "{{ route('employees.store') }}";

                let formData = new FormData(this);

                if (employee_id) {
                    formData.append('_method', 'PUT');

                    // UPDATE → file empty ho to send mat karo
                    if (!$('input[name="pan_file"]')[0].files.length) formData.delete('pan_file');
                    if (!$('input[name="aadhaar_file"]')[0].files.length) formData.delete('aadhaar_file');
                }

                $.ajax({
                    url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    success(res) {
                        employeeModal.hide();
                        $('#employeeForm')[0].reset();
                        table.ajax.reload(null, false);
                        showToast(res.message, 'success');
                    },

                    error(xhr) {
                        if (xhr.status === 422) {
                            $.each(xhr.responseJSON.errors, function(key, val) {
                                let cleanKey = key.replace(/\.\d+/, '');
                                $('[data-error="' + cleanKey + '"]').text(val[0]);
                                $('[name="' + cleanKey + '"], [name="' + cleanKey + '[]"]')
                                    .addClass('input-error');
                            });
                        } else {
                            showToast('Something went wrong!', 'danger');
                        }
                    }
                });
            });

            /* ================= DELETE ================= */
            $(document).on('click', '.btnDelete', function() {

                let id = $(this).data('id');
                if (!confirm('Are you sure?')) return;

                $.ajax({
                    url: `/employees/${id}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success(res) {
                        showToast(res.message, 'success');
                        table.ajax.reload(null, false);
                    },
                    error() {
                        showToast('Delete failed!', 'danger');
                    }
                });
            });

            /* ================= STATE → CITY ================= */
            $('#state').on('change', function() {
                let state_id = $(this).val();
                $('#city').html('<option value="">Select</option>');

                if (state_id) {
                    $.get(`/get-cities/${state_id}`, function(cities) {
                        $.each(cities, function(i, city) {
                            $('#city').append(
                                `<option value="${city.id}">${city.name}</option>`);
                        });
                        $(document).trigger('citiesLoaded');
                    });
                }
            });

        });

        /* ================= DESIGNATION AUTOCOMPLETE ================= */
        $('#designation').on('input', function () {
            let query = $(this).val().toLowerCase();
            if(query.length < 1){
                $('#designationList').addClass('d-none');
                return;
            }

            let designations = ['Manager', 'Developer', 'Designer', 'Analyst', 'Consultant', 'Intern', 'Administrator', 'Coordinator', 'Engineer', 'Specialist'];
            let matches = designations.filter(d => d.toLowerCase().includes(query));
            if(matches.length > 0){
                let listItems = matches.map(d => `<button type="button" class="list-group-item list-group-item-action">${d}</button>`).join('');
                $('#designationList').html(listItems).removeClass('d-none');
            } else {
                $('#designationList').addClass('d-none');
            }
        });
        $(document).on('click', '#designationList .list-group-item', function () {
            let selected = $(this).text();
            $('#designation').val(selected);
            $('#designationList').addClass('d-none');
        });

        /* ================= DOCUMENT CHECKBOX ================= */
        $('#doc_pan').on('change', function () {
            if($(this).is(':checked')) {
                $('input[name="pan_file"]').prop('required', true);
            } else {
                $('input[name="pan_file"]').prop('required', false);
            }
        });
        $('#doc_aadhaar').on('change', function () {
            if($(this).is(':checked')) {
                $('input[name="aadhaar_file"]').prop('required', true);
            } else {
                $('input[name="aadhaar_file"]').prop('required', false);
            }
        });

        /* ================= FILE PREVIEW ================= */
        $('input[name="pan_file"]').on('change', function () {
            let file = $(this)[0].files[0];
            let reader = new FileReader();
            reader.onload = function (e) {
                $('input[name="pan_file"]').after(
                    `<div class="file-preview mt-1">
                        <a href="${e.target.result}" target="_blank">View PAN</a>
                    </div>`
                );
            }
            reader.readAsDataURL(file);
        });
        $('input[name="aadhaar_file"]').on('change', function () {
            let file = $(this)[0].files[0];
            let reader = new FileReader();
            reader.onload = function (e) {
                $('input[name="aadhaar_file"]').after(
                    `<div class="file-preview mt-1">
                        <a href="${e.target.result}" target="_blank">View Aadhaar</a>
                    </div>`
                );
            }
            reader.readAsDataURL(file);
        });

        /* ================= TOAST ================= */
        function showToast(message, type = 'success') {

            let bg = type === 'success' ? 'bg-success' :
                type === 'danger' ? 'bg-danger' :
                type === 'warning' ? 'bg-warning' :
                'bg-info';

            let toast = `
                <div class="toast align-items-center text-white ${bg} border-0 mb-2"
                    role="alert" data-bs-delay="2500" data-bs-autohide="true">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            $('#toastContainer').append(toast);
            let el = $('#toastContainer .toast').last()[0];
            new bootstrap.Toast(el).show();
            el.addEventListener('hidden.bs.toast', () => el.remove());
        }
    </script>

</body>

</html>
