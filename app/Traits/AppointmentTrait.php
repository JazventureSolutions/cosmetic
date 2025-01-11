<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

trait AppointmentTrait
{
    public function fetchAppointments(Request $request)
    {
        $appointments = Appointment::select([
            'appointments.*'
        ])
        /*->whereNotIn('status', array('canceled','did_not_attend')) */
            ->with('patient');

        $branch_id = $request->input('branch_id', 0);
        if ($branch_id > 0) {
            $appointments = $appointments
                ->whereHas('patient', function ($query) use ($branch_id) {
                    $query->where('patients.branch_id', $branch_id);
                });
        }

        $patient_id = $request->input('patient_id', 0);
        if ($patient_id > 0) {
            $appointments = $appointments
                ->where('patient_id', $patient_id);
        }

        $start_date = $request->input('start_date', "");
        $end_date = $request->input('end_date', "");

        if (!in_array($start_date, ['', null]) && !in_array($end_date, ['', null])) {
            $appointments = $appointments
                ->whereBetween('date', [$start_date, $end_date]);
        }

        if (!in_array($start_date, ['', null])) {
            $appointments = $appointments
                ->where('date', '>=', $start_date);
        }

        if (!in_array($end_date, ['', null])) {
            $appointments = $appointments
                ->where('date', '<=', $end_date);
        }

        return $appointments
            ->orderBy('date')
            ->orderBy('start_time');
    }

    public function fetchAppointmentsforExport(Request $request)
    {
        $appointments = Appointment::select([
            'appointments.*'
        ])->whereNotIn('status', array('canceled','did_not_attend'));

        $branch_id = $request->input('branch_id', 0);
        if ($branch_id > 0) {
            $appointments = $appointments
                ->whereHas('patient', function ($query) use ($branch_id) {
                    $query->where('patients.branch_id', $branch_id);
                });
        }

        $patient_id = $request->input('patient_id', 0);
        if ($patient_id > 0) {
            $appointments = $appointments
                ->where('patient_id', $patient_id);
        }

        $start_date = $request->input('start_date', "");
        $end_date = $request->input('end_date', "");

        if (!in_array($start_date, ['', null]) && !in_array($end_date, ['', null])) {
            $appointments = $appointments
                ->whereBetween('date', [$start_date, $end_date]);
        }

        if (!in_array($start_date, ['', null])) {
            $appointments = $appointments
                ->where('date', '>=', $start_date);
        }

        if (!in_array($end_date, ['', null])) {
            $appointments = $appointments
                ->where('date', '<=', $end_date);
        }

        return $appointments
            ->orderBy('date')
            ->orderBy('start_time');
    }
}
