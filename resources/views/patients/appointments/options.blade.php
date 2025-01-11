@if ($appointment->status)

<div class="fc-list-item-options">
    <form action="{{ $appointment->delete_route }}" method="POST" onclick="return confirm('Are you sure?')" style="float: right;">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
        <button type="submit" class="btn btn-danger btn-icon btn-sm appointment-delete" title="Delete">
            <i class="far fa-trash-alt icon-md"></i>
        </button>
    </form>
    <a href="{{ $appointment->edit_route }}" class="btn btn-success btn-icon btn-sm mr-2" style="float: right;" title="Edit">
        <i class="far fa-edit icon-md"></i>
    </a>
    <a href="{{ $appointment->reports_route }}" class="btn btn-success btn-icon btn-sm mr-2" style="float: right;" title="Reports">
        <i class="far fa-file-pdf icon-md"></i>
    </a>
</div>

@else

<div class="fc-list-item-options">
    @if ($appointment->available)
    <a href="{{ $appointment->add_route }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus icon-md"></i>Add Appointment
    </a>
    @endif
</div>

@endif
