<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientInquiry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function patientRegister(Request $request)
    {
        $patient_inq = PatientInquiry::findOrFail($request->id);

        return view('messages.patient_register', [
            'title' => config('app.name'),
            'patient_register_link' => $patient_inq->link
        ]);
    }

    public function appointmentReminder(Request $request)
    {
        $appointment = Appointment::select('appointments.*')
            ->where('appointments.id', $request->id)
            ->with('patient')
            ->first();
        $patient = $appointment->patient;
        $parents_away = '';
        if($patient->remote_patent_status == 1)
        {
            $parents_away = 'Thank you for booking your sons circumcision with Dr Khan. It is a regulatory requirement that both parents sign the consent form. As dad is away, there are a few things that you will need to do:

                - Mum must bring ID for both herself and baby to the appointment.
                - Dad must be available to video call during the appointment.
                - Dad must show his photo ID while on the call.
                - We will send dad a link to sign the consent form during the appointment. He must sign before we can go ahead with the procedure. ';
        }
        $branch = $patient->branch;
        $datetime_formatted = Carbon::parse($appointment->date)->englishDayOfWeek . ' ' . Carbon::parse($appointment->date)->toFormattedDateString() . ' @ ' . Carbon::parse($appointment->start_time)->format('g:i A');

        $parents_confirm = '';
        if (in_array($appointment->patient->type, ['new_born', 'old_boy'])) {
            // $parents_confirm = ' Please also confirm that both parents are attending to sign the consent forms.';
            $parents_confirm = ' Both parents are required to attend & to bring photo ID documents for both parents & ID for the child.';
        }

        $is_pre_assessment = $appointment->appointment_type == "pre_assessment_appointment";

        return view('messages.appointment_reminder', [
            'title' => config('app.name'),
            'datetime_formatted' => $datetime_formatted,
            'branch_address_line' => $branch->address,
            'parents_confirm' => $parents_confirm,
            'is_pre_assessment' => $is_pre_assessment,
            'parents_away'=>$parents_away
        ]);
    }

    public function appointmentArranged(Request $request)
    {

        $appointment = Appointment::select('appointments.*')
            ->where('appointments.id', $request->id)
            ->with('patient')
            ->first();
        $patient = $appointment->patient;

        $parents_away = '';
        if($patient->remote_patent_status == 1)
        {

            $parents_away = 'Thank you for booking your sons circumcision with Dr Khan. It is a regulatory requirement that both parents sign the consent form. As dad is away, there are a few things that you will need to do:

                - Mum must bring ID for both herself and baby to the appointment.
                - Dad must be available to video call during the appointment.
                - Dad must show his photo ID while on the call.
                - We will send dad a link to sign the consent form during the appointment. He must sign before we can go ahead with the procedure. ';
        }

        $branch = $patient->branch;
        $datetime_formatted = Carbon::parse($appointment->date)->englishDayOfWeek . ' ' . Carbon::parse($appointment->date)->toFormattedDateString() . ' @ ' . Carbon::parse($appointment->start_time)->format('g:i A');

        $is_pre_assessment = $appointment->appointment_type == "pre_assessment_appointment";

        // no
        $parents_confirm = '';
        if (!$is_pre_assessment) {
            if (in_array($patient->type, ['new_born', 'old_boy'])) {
                $parents_confirm = 'Both parents are required to attend & to bring photo ID documents for both parents & child. ';
            } else {
                $parents_confirm = 'You are required to bring photo ID documents of the Patient. ';
            }
        }

        $patient_age = $patient
            ? Carbon::parse($patient->date_of_birth)->diffInMonths()
            : null;

        // no
        $medicine = '';
        if (!$is_pre_assessment && is_numeric($patient_age)) {
            if ($patient_age < 3 && $patient_age > -3) {
                $medicine = 'Paracetamol liquid';
            } else {
                $medicine = 'Paracetamol and Ibuprofen';
            }
        }

        return view('messages.appointment_arranged', [
            'title' => config('app.name'),
            'datetime_formatted' => $datetime_formatted,
            'branch_address_line' => $branch->address,
            'parents_confirm' => $parents_confirm,
            'medicine' => $medicine,
            "is_pre_assessment" => $is_pre_assessment,
            'parents_away'=>$parents_away
        ]);
    }

    public function appointmentDeposit(Request $request)
    {
        return view('messages.appointment_deposit', [
            'title' => config('app.name'),
        ]);
    }

    public function appointmentCanceled(Request $request)
    {
        return view('messages.appointment_canceled', [
            'title' => config('app.name'),
        ]);
    }

    public function appointmentCompleted(Request $request)
    {
        $patient = Patient::findOrFail($request->id);

        return view('messages.appointment_completed', [
            'title' => config('app.name'),
            'branchId' => $patient->branch_id,
        ]);
    }
}
