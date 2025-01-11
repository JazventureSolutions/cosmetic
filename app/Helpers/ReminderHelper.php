<?php

namespace App\Helpers;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReminderHelper
{
    public static function sendReminder($date = null)
    {
        try {
            $date = Carbon::parse($date);
        } catch (\Throwable $th) {
            $date = Carbon::now()->addDays(1);
        }

        $appointments = Appointment::select('appointments.*')
            ->where('appointments.status', 'pending')
            ->where('appointments.date', $date->toDateString())
            ->with('patient')
            ->get();

        foreach ($appointments as $appointmentkey => $appointment) {
            // $message = config('constants.messages.appointment_reminder', '');
            // $message = Helper::fillReport($appointment->id, $message);

            // if (in_array($appointment->patient->type, ['new_born', 'old_boy'])) {
            //     $message = str_replace(':parents_confirm:', ' Please also confirm that both parents are attending to sign the consent forms.', $message);
            // }

            $is_pre_assessment = $appointment->appointment_type == "pre_assessment_appointment";

            $link = route('messages.appointment-reminder', ['id' => $appointment->id]);
            $message = "Request for " . ($is_pre_assessment ? "pre assessment" : "circumcision") . " appointment confirmation: Please Confirm by a text message. No response will result in cancellation. {$link}";

            try {
                Helper::sendSMS($message, $appointment->patient->cell_number);
            } catch (\Exception $e) {
                Log::channel('sms')->error($e->getLine() . ' | ' . $e->getMessage());
            }
        }

        return redirect()->back();
    }
}
