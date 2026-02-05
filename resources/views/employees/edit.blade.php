<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
        }
        .card {
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">✏ Edit Employee</h4>
        </div>

        <div class="card-body">

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('employees.update', $employee->employee_id) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email_id" class="form-control" value="{{ old('email_id', $employee->email_id) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile_no" class="form-control" value="{{ old('mobile_no', $employee->mobile_no) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">State</label>
                        <select id="state" name="state_id" class="form-select">
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ old('state_id', $employee->state_id) == $state->id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">City</label>
                        <select id="city" name="city_id" class="form-select">
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id', $employee->city_id) == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $employee->address) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Update Employee</button>
                </div>
            </form>

        </div>
    </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$('#state').change(function(){
    let state_id = $(this).val();

    $.get('/get-cities/' + state_id, function(data){
        $('#city').html('<option value="">Select City</option>');

        $.each(data, function(index, city){
            $('#city').append('<option value="'+city.id+'">'+city.name+'</option>');
        });
    });
});
</script>

</body>
</html>
