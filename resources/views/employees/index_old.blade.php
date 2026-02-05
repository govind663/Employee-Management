<!DOCTYPE html>
<html>
<head>
    <title>Employee Management</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
        }
        .card {
            border-radius: 12px;
        }
        .btn-add {
            float: right;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">👨‍💼 Employee Management</h4>
            <a href="{{ route('employees.create') }}" class="btn btn-primary">➕ Add Employee</a>
        </div>

        <div class="card-body">
            <table id="employeeTable" class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>State</th>
                        <th>City</th>
                        <th width="140">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employees as $key => $emp)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $emp->name }}</td>
                        <td>{{ $emp->email_id }}</td>
                        <td>{{ $emp->mobile_no }}</td>
                        <td>{{ $emp->state->name }}</td>
                        <td>{{ $emp->city->name }}</td>
                        <td>
                            <a href="{{ route('employees.edit',$emp->employee_id) }}" class="btn btn-sm btn-warning">✏ Edit</a>

                            <form action="{{ route('employees.destroy',$emp->employee_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this employee?')">
                                    🗑 Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#employeeTable').DataTable({
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
        });
    });
</script>

</body>
</html>
