<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Employee Management</title>

    {{-- ================= CSS ONLY ================= --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>

    {{-- FONT AWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    {{-- VITE CSS --}}
    @vite(['resources/css/employee.css'])

</head>

<body>

{{-- ================= EMPLOYEE TABLE ================= --}}
@include('employees.partials.table')

{{-- ================= EMPLOYEE MODAL ================= --}}
@include('employees.partials.modal')

{{-- ================= TOAST CONTAINER ================= --}}
<div id="toastContainer"
     class="toast-container position-fixed top-0 end-0 p-3"
     style="z-index:9999">
</div>

{{-- ================= JS LIBRARIES (ORDER VERY IMPORTANT) ================= --}}
{{-- JQUERY FIRST --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- BOOTSTRAP --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- DATATABLE --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

{{-- TOASTR --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- SWEET ALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- CHOICES JS --}}
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

{{-- YOUR MAIN JS LAST --}}
@vite(['resources/js/employee.js'])

</body>
</html>
