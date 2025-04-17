@extends('layouts.default')

@section('content')

<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">Search Patients</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('patients.search') }}" class="form-inline mb-4">
            <input type="text" name="name" class="form-control mr-2" placeholder="Enter patient name" value="{{ old('name', $query ?? '') }}" />
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        @if(isset($patients) && count($patients))
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>DOB</th>
                        <th>Parents Name</th>
                        <th>GP Details</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                        <tr>
                            <td>{{ $patient->id }}</td>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->date_of_birth }}</td>
                            <td>
                            Father: <b>{{ $patient->father_name }}</b><br>
                            Mother: <b>{{ $patient->mother_name }}</b>
                        </td>
                            <td>{{ $patient->gp_details }}</td>
                            <td>
                                <a href={{$patient->latest_appointment_route}} class="btn btn-block btn-sm btn-info mb-2 mr-2" title="Latest Appointment Reports">Reports</a><br>
                                 <a href={{$patient->latest_appointment_edit_route}} class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Edit Appointment">Edit Appointment</a><br>
                                  <a href={{$patient->edit_route}} class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Edit Patient">Edit Patient</a>
                                </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif(isset($query))
            <div class="alert alert-info">No patients found.</div>
        @endif
    </div>
</div>

@endsection