<?php

namespace App\Helpers;

use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Medicine;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use SoapClient;
use stdClass;

class Helper
{
    public static function getCompanyBranchesForSelect($company_id = 1)
    {
        $branches = Branch::where('company_id', $company_id)->get();

        $_branches = [];
        foreach ($branches as $key => $value) {
            $_branches[$value->id] = $value->name;
        }

        return $_branches;
    }

    public static function getAppointmentStatusForSelect()
    {
        $appointment_status = config('constants.appointment_status', []);

        foreach ($appointment_status as $key => $value) {
            $appointment_status[$key] = $value['name'];
        }

        return $appointment_status;
    }

    public static function getAppointmentStatus($status)
    {
        $appointment_status = config('constants.appointment_status', []);

        if ($appointment_status[$status]) {
            return $appointment_status[$status];
        }

        return [
            'name' => 'unknown',
            'bg_color' => '#EEEEEE',
            'text_color' => '#333333'
        ];
    }

    public static function getAppointmentFollowupStatusForSelect()
    {
        $appointment_followup_status = config('constants.appointment_followup_status', []);

        foreach ($appointment_followup_status as $key => $value) {
            $appointment_followup_status[$key] = $value['name'];
        }

        return $appointment_followup_status;
    }

    public static function getAppointmentTypesForSelect()
    {
        $appointment_types = config('constants.appointment_types', []);

        foreach ($appointment_types as $key => $value) {
            $appointment_types[$key] = $value['name'];
        }

        return $appointment_types;
    }

    public static function getPatientTypesForSelect()
    {
        $patient_types = config('constants.patient_type', []);

        foreach ($patient_types as $key => $value) {
            $patient_types[$key] = $value['name']; // . ' (' . $value['appointment_duration'] . ' minutes)';
        }

        return $patient_types;
    }
    public static function getAppointmentTypes()
    {
        $AppointmentTypes = config('constants.patient_appointment_type', []);

        foreach ($AppointmentTypes as $key => $value) {
            $AppointmentTypes[$key] = $value['name']; // . ' (' . $value['appointment_duration'] . ' minutes)';
        }

        return $AppointmentTypes;
    }

    public static function getReportTypesForSelect()
    {
        $report_types = [
            '1' => 'Report',
            '0' => 'Email'
        ];

        return $report_types;
    }

    public static function fillReport(int $appointment_id, string $html)
    {
        $appointment = Appointment::findOrFail($appointment_id);
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;
        $branch = $patient->branch;
        $staff = Auth::user();

        $patient_age = $patient
            ? Carbon::parse($patient->date_of_birth)->age
            : null;

        $default_followup_text = config('constants.default_followup_text.' . $patient->type, '');
        $inspection_report = config('constants.inspection_report.' . $patient->type . '.' . $patient->branch_id, '');

        $variables_data = [
            ":header:" => '<table style="margin: 0 auto;"><tbody><tr><td><img src="data:image/gif;base64,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" class="img-fluid" style="width: 85px;"></td><td><table><tbody><tr><td><span style="font-size: 30px; font-weight: 600; text-align: center;">Circumcision Clinic</span></td></tr><tr><td><span style="font-size: 18px;">Anwar Khan <span style="font-size: 12px;">MBBS,FRCS (Edin.)</span></span></td></tr></tbody></table></td></tr></tbody></table>',
            ":date:" => Carbon::parse($appointment->date)->format('d.m.Y'),
            ":date_formatted:" => Carbon::parse($appointment->date)->englishDayOfWeek . ' ' . Carbon::parse($appointment->date)->toFormattedDateString(),
            ":time:" => $appointment->start_time,
            ":datetime:" => $appointment->date . ' ' . $appointment->start_time,
            ":datetime_formatted:" => Carbon::parse($appointment->date)->englishDayOfWeek . ' ' . Carbon::parse($appointment->date)->toFormattedDateString() . ' @ ' . Carbon::parse($appointment->start_time)->format('g:i A'),
            ":staff_name:" => $staff->name,
            ":doctor_name:" => $doctor->name,
            ":doctor_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":staff_sign:" => '<img data-sign-type="staff" class="sign-class" />',
            ":patient_sign:" => '<img data-sign-type="patient" class="sign-class" />',
            ":father_sign:" => '<img data-sign-type="father" class="sign-class" />',
            ":mother_sign:" => '<img data-sign-type="mother" class="sign-class" />',
            ":next_kin_sign:" => '<img data-sign-type="next_kin" class="sign-class" />',
            ":interpreter_sign:" => '<img data-sign-type="interpreter" class="sign-class" />',
            ":consent_sign:" => '<img data-sign-type="consent" class="sign-class" />',
            ":followup_sign:" => '<img data-sign-type="followup" class="sign-class" />',
            // ":name:" => $patient
            //     ? $patient->name
            //     : 'name',
            ":name:" => $patient
                ? ($patient_age > 16 ? 'Mr. ' : "Master ") . $patient->name
                : 'name',
            ":surname:" => $patient
                ? $patient->surname
                : 'surname',
            ":fullname:" => $patient
                ? $patient->fullname
                : 'fullname',
            ":followup:" => $appointment && $appointment->followup_date
                ? Carbon::parse($appointment->followup_date)->format('d.m.Y')
                : ':followup:',
            ":date_of_birth:" => $patient
                ? Carbon::parse($patient->date_of_birth)->format('d.m.Y')
                : 'date_of_birth',
            ":address:" => $patient
                ? $patient->address
                : 'address',
            ":email:" => $patient
                ? $patient->email
                : 'email',
            ":phone:" => $patient
                ? $patient->phone
                : 'phone',
            ":cell_number:" => $patient
                ? $patient->cell_number
                : 'cell_number',
            ":weight_of_child:" => $patient
                ? $patient->weight_of_child . ' Kg'
                : 'weight_of_child',
            ":father_name:" => $patient
                ? $patient->father_name
                : 'father_name',
            ":mother_name:" => $patient
                ? $patient->mother_name
                : 'mother_name',
            ":parent_name:" => $patient
                ? ($patient->father_name ?? $patient->mother_name)
                : 'parent_name',
            ":next_kin:" => $patient
                ? $patient->next_kin
                : 'next_kin',
            ":gp_details:" => $patient
                ? $patient->gp_details
                : 'gp_details',
            ":fees:" => $appointment->fees,
            ":fees_paid:" => $appointment->fees_paid,
            ":fees_remaining:" => $appointment->fees_remaining,
            ":branch_name:" => $branch->name,
            ":branch_address_line:" => $branch->address,
            ":branch_address:" => $branch->address_email,
            ":branch_tel:" => $branch->tel_email,
            ":followup_text:" => $default_followup_text,
            ":inspection_report:" => $inspection_report,
        ];

        foreach (Medicine::all() as $medicine) {
            $variables_data[":" . $medicine->name . "_batch:"] = $medicine->batch;
            $variables_data[":" . $medicine->name . "_expiry:"] = $medicine->expiry;
        }

        foreach ($variables_data as $variable => $variable_data) {
            $html = str_replace($variable, $variables_data[$variable] ?? '', $html);
        }

        return $html;
    }

    public static function fillReportDummy(int $appointment_id, string $html)
    {
        $appointment = Appointment::findOrFail($appointment_id);
        $patient = $appointment->patient;
        $doctor = $appointment->doctor;
        $branch = $patient->branch;
        $staff = Auth::user();

        $patient_age = $patient
            ? Carbon::parse($patient->date_of_birth)->age
            : null;

        $default_followup_text = config('constants.default_followup_text.' . $patient->type, '');
        $inspection_report = config('constants.inspection_report.' . $patient->type . '.' . $patient->branch_id, '');

        $variables_data = [
            ":header:" => '<table style="margin: 0 auto;"><tbody><tr><td><img src="data:image/gif;base64,R0lGODdhXABTAHcAACH+GlNvZnR3YXJlOiBNaWNyb3NvZnQgT2ZmaWNlACwAAAAAXABTAIf///8pKYQArd4pKWvW5vfW5tZKUoyltc575ual5u9S7+YQWhApWr0IWr17hKXWtc5zWq1SxeZ7pd5776UpGe97KZx7Kd5S76VSpd5SKZxSKd5arWMQrWNa3jEQ3jEQGTFa72MQ72MplJwpzqVaKVpaKRl7jGMxjGN73hAx3hAxGRB7hN4IGe97CJx7CN5ShN5SCJxSCN5azmMQzmMIlJwIzqVaCFpaCBlzxeYZIXMhpebWte+ljM4QEJSljK1SWq17rSExrSGlte+l5s4hhObW5qXW5kKc5qWc5kLWtaXWtUKctaWctULW5nPW5hCc5nOc5hDWtXPWtRCctXOctRB7Wu/mjO/mjGspWu8p7+bmOu/mOmvmjK3mjCnmOq3mOil7paWtjO+tjGutOu+tOmutjCmtOq2tOilSpaWtY++tY61SWu/mY+/mY2vmEO/mEGvmY63mYynmEK3mECmtY2utEO+tEGutYymtEK2tECkQWjEpxeZaWmsQY2NaWil7WoytjIwxWhB7Ws7mjM7mjEoIWu8I7+bmOs7mOkrmjIzmjAjmOozmOgitjEqtOs6tOkqtjAitOoytOghShKWtY86tY4xSWs7mY87mY0rmEM7mEErmY4zmYwjmEIzmEAitY0qtEM6tEEqtYwitEIytEAhaWkoQQmNaWgh772Mx72N7rWMxrWMplL173jEx3jExGTEp76V7KVp7KRl7zmMxzmMIlL0I76V7CFp7CBlajBAQjBAQEHMxMYwIxeZ7WmsxY2MxWjF7WikxCGN7WkoxQmN7Wgj35qX35kK95qW95kL3taX3tUK9taW9tUL35nP35hC95nO95hD3tXP3tRC9tXO9tRD35uYpY5RajDEQjDF7zrVSzrVajHMQjHNa7xAQ7xAQKRAQOpRarRAQrRB7jBAxjBAIY5R7zpRSzpRajFIQjFJazhAQzhAQCBApKb0IKb33td4pCL0ICL0AjOZarTEQrTF7jDExjDEQCFIxEJQQKVoAre/3/94xKZT3//8xKYQI/wABCBxIsKDBgwgTKlzIsKHDhxAjSpxIsaLFixgzatzIsaPHjyBDihxJcqI/AgUOPDiQcgcBa/5KygRwwAcEA//+7QoQIEe+HP9y5OBlAIKDAwRmcvTHA8K/AT0GAM1JdQCvqkJ37fpn4KhSizsc8MphiicxqlX/8RpA9epVqloN+PgakYADoWh5BVhLLACxq315EbOKNm+OfnQd+vOhM+3anLwir9Wbcy/ff2fP5sxhIDHDAhl2Ec7ZF7PfAE8jE/5rVfVktJ09J+QhNBxbwa1b97vNe/JkwqrPWo0p2+BdtIF9W80NWO/V0csjox4QuzhBCKLVnnWbG/K/vX33Rv///lct7wDVrfszMPRtzvGt+w6Q2hPogPo57gdYvZez9YEQTIXba3ptB5VVRh2gUgFCpHSAAz8Y0JN+bRFXnANbpbaXaW1JxYsDBVioUAE8/CCVVf5ZR9tpmn1X4FpD+UCciAwR4MNjvFhXgFCSoVaaWvsF4ACNEi2GXnHrAfXYd/2wFpQBByh00kkEEOAPkQP544A1smHoXmlW9WcAlgQgEMEe+6SppgD7RBBBAkn9N9AOU0EG3lucEZkAmvPsMw+bAgQKqJr77IFlYiaSRliYfaUn0J59psnmPpNSammglFr5XwHLfWeeW3txmSUCkV566aCo7pOAnAbsAthpz0n/VQBBBOzRp6CmpppqnMXRaWdkVIWTjwNZ+qODpMgCquuu/x2XWmrCjTkQAble2gsOcE6ZQAI4RHApr7JN9pdfn+Yw10B76FroqgJdaRABCRzLrmwHDOWpi8tJKxAOt5qKA0QIgJuYA52SB9kuKwyUQLKWzitnQ/6M9apzbP0jKgBopurww58JCB5kA/yg8J/+cgwRDxm+95u56DK8h8kQ3XWZamrlECe1GsP8EE5ApraWVQMh4LLOihkwn53bHSlQBKn+SzRDdAKbHIrEAuAPwwII/PRBBHSKGo4sA5BAqi9vvdABW5UX2FM5RCl2v2xGYPbZrSkHpNsIpCr33An5/5Dhos/lsMO+yc6DAN8J1fvax1e5jYOpqiKOkJeYPUfZALMC4C2qTktekA9KisddAI6bOs/GngNAG1WsTSb40rkenjpBSww1mHOQtS2Q0IPOs/fsAtEmGWmoPVW6qWUDT9NQXgumU9UEpLrPoXwX4CrxV/UHfa4CoC45AW0Vf3B1GVvqu/IAWPNYX+y/xYuom1uaptaSG+2bezq5PfapbaLvQA+fYktmclA1jMkPUN7jGw8q9qkNqWUgOFCW/Og3tx2N51lBcRsAsDa/ulCvJOvZj2D8sqQcQEBhy9qH7Bjij3gl4IMkCZB0ZliVzGkOcmzSwR5eiJCT4KAXfaLgTP/qxZvK3G4X1fFH+VLVpwjgAAFC2Fa3ypep/wBrNcMLCg+mVa1BEcpPaSIZpoQ4Ewfoo4GdegyvaoWpFOKqjQ37D/haAzLLpahd8VPXpPb4LTlBYD8GK9D4ChIvOOoReTCUyY4m4yPcbeaEhMwj9yy1BxyQkS4yO4t4IKMZJGoNXlPcgw73sAcnwgkhD0okSAggMfcNoDyVA4oGLVIA7EDSM/UyIpBwZB5h/OABFTlAgEQjsgulTXQqo0qQfnCAiy3EHzVhj71y8If/sAdYrtGkgYKykwQVwCXWeMkOFOQACOhlF/Z65H/UNxXCONBHu8FTDlK2nPqgsy2sy8Ek5MS7yhxo81OB+Zh31sKT0biHgbcx4cMKILFP+UyZlSuMQyPKGlgSkGPWYE9vHkOu3zyFPDhqkYsoFpRbyskfAWKO5eholYBaTlzFw6JCYXaAsZCLhO4cV5PqiCPwdBQ1+igmzOySA56M1J0rfQ3gjlofKG0tLGM5miBHuNMXCQZH98kPBGa5NRv9YD/5KahrSFjQgvLIADy4JNEI8CAD6AU/QsGPZBKkVskVYCUH4AEPlsADBRWgAM5EH+ICAgA7" class="img-fluid" style="width: 85px;"></td><td><table><tbody><tr><td><span style="font-size: 30px; font-weight: 600; text-align: center;">Circumcision Clinic</span></td></tr><tr><td><span style="font-size: 18px;">Anwar Khan <span style="font-size: 12px;">MBBS,FRCS (Edin.)</span></span></td></tr></tbody></table></td></tr></tbody></table>',
            ":date:" => Carbon::parse($appointment->date)->format('d.m.Y'),
            ":date_formatted:" => Carbon::parse($appointment->date)->englishDayOfWeek . ' ' . Carbon::parse($appointment->date)->toFormattedDateString(),
            ":time:" => $appointment->start_time,
            ":datetime:" => $appointment->date . ' ' . $appointment->start_time,
            ":datetime_formatted:" => Carbon::parse($appointment->date)->englishDayOfWeek . ' ' . Carbon::parse($appointment->date)->toFormattedDateString() . ' @ ' . Carbon::parse($appointment->start_time)->format('g:i A'),
            ":staff_name:" => $staff->name,
            ":doctor_name:" => $doctor->name,
            ":doctor_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":staff_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":patient_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":father_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":mother_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":next_kin_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":interpreter_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":consent_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            ":followup_sign:" => '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />',
            // ":name:" => $patient
            //     ? $patient->name
            //     : 'name',
            ":name:" => $patient
                ? ($patient_age > 16 ? 'Mr. ' : "Master ") . $patient->name
                : 'name',
            ":surname:" => $patient
                ? $patient->surname
                : 'surname',
            ":fullname:" => $patient
                ? $patient->fullname
                : 'fullname',
            ":followup:" => $appointment && $appointment->followup_date
                ? Carbon::parse($appointment->followup_date)->format('d.m.Y')
                : ':followup:',
            ":date_of_birth:" => $patient
                ? Carbon::parse($patient->date_of_birth)->format('d.m.Y')
                : 'date_of_birth',
            ":address:" => $patient
                ? $patient->address
                : 'address',
            ":email:" => $patient
                ? $patient->email
                : 'email',
            ":phone:" => $patient
                ? $patient->phone
                : 'phone',
            ":cell_number:" => $patient
                ? $patient->cell_number
                : 'cell_number',
            ":weight_of_child:" => $patient
                ? $patient->weight_of_child . ' Kg'
                : 'weight_of_child',
            ":father_name:" => $patient
                ? $patient->father_name
                : 'father_name',
            ":mother_name:" => $patient
                ? $patient->mother_name
                : 'mother_name',
            ":parent_name:" => $patient
                ? ($patient->father_name ?? $patient->mother_name)
                : 'parent_name',
            ":next_kin:" => $patient
                ? $patient->next_kin
                : 'next_kin',
            ":gp_details:" => $patient
                ? $patient->gp_details
                : 'gp_details',
            ":fees:" => $appointment->fees,
            ":fees_paid:" => $appointment->fees_paid,
            ":fees_remaining:" => $appointment->fees_remaining,
            ":branch_name:" => $branch->name,
            ":branch_address_line:" => $branch->address,
            ":branch_address:" => $branch->address_email,
            ":branch_tel:" => $branch->tel_email,
            ":followup_text:" => $default_followup_text,
            ":inspection_report:" => $inspection_report,
        ];

        foreach (Medicine::all() as $medicine) {
            $variables_data[":" . $medicine->name . "_batch:"] = $medicine->batch;
            $variables_data[":" . $medicine->name . "_expiry:"] = $medicine->expiry;
        }

        foreach ($variables_data as $variable => $variable_data) {
            $html = str_replace($variable, $variables_data[$variable] ?? '', $html);
        }

        return $html;
    }

    public static function fillReportWithDates(string $html)
    {
        $variables_data = [
            ":date:" => Carbon::now()
                ->toDateString(),
            ":time:" => Carbon::now()
                ->addHour()
                ->toTimeString(),
            ":datetime:" => Carbon::now()
                ->addHour()
                ->toDateTimeString(),
        ];

        foreach ($variables_data as $variable => $variable_data) {
            $html = str_replace($variable, $variables_data[$variable] ?? '', $html);
        }

        return $html;
    }

    public static function sendSMS(string $message = '', $destination = null)
    {
        try {
            if (!in_array($destination, [null, ''])) {
                // $client = new \GuzzleHttp\Client();
                // $req = $client->get('https://sms.montymobile.com/API/SendSMS?username=' . env('MONTYMOBILE_USERNAME') . '&apiId=' . env('MONTYMOBILE_API_ID') . '&json=True&destination=' . $destination . '&source=' . env('MONTYMOBILE_SOURCE') . '&text=' . $message);
                $sc = new SoapClient('https://www.textapp.net/webservice/service.asmx?wsdl');

                $params = new stdClass();
                $params->returnCSVString = false;
                $params->externalLogin = env('TEXTANYWHERE_USERNAME');
                $params->password = env('TEXTANYWHERE_PASSWORD');
                $params->clientBillingReference = Carbon::now()->toDateString();
                $params->clientMessageReference = 'test';
                $params->originator = env('TEXTANYWHERE_ORIGINATOR');
                $params->destinations = $destination;
                $params->body = utf8_encode($message);
                $params->validity = 72;
                $params->characterSetID = 2;
                $params->replyMethodID = 4;
                $params->replyData = '';
                $params->statusNotificationUrl = '';

                $result = $sc->__call('SendSMS', array($params));

                Log::channel('sms')->info($result->SendSMSResult);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::channel('sms')->error($e);

            return false;
        }
    }
    public static function getPatientParentTypeForSelect()
    {
        $patient_parent_types = config('constants.patient_parent_type');
        return $patient_parent_types;
    }

    public static function sendWhatsAppMessage($cell_number,$link)
    {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
        $recipientNumber = 'whatsapp:'.$cell_number;
        $message = $link;

        $twilio = new Client($twilioSid, $twilioToken);

        try {
            $twilio->messages->create(
                $recipientNumber,
                [
                    "from" => $twilioWhatsAppNumber,
                    "body" => $message,
                ]
            );
            return response()->json(['message' => 'WhatsApp message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
