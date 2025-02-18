<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Slot;
use App\Models\Audit;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Feedback;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Template;
use App\Traits\AppointmentTrait;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
    use AppointmentTrait;

    public function availableTimes(Request $request)
    {
        $patient_id = $request->patient_id ?? 0;

        $patient = Patient::find($patient_id);

        $branch_id = $patient->branch_id ?? 0;
        $appointment_id = $request->appointment_id ?? 0;
        $appointment_date = $request->appointment_date == '__.__.____' ? null : $request->appointment_date;
        $used_appointment_times = [];
        $available_times = [];

        $used_appointment_times = Appointment::select('appointments.start_time')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->where('patients.branch_id', $branch_id)
            ->where('date', Carbon::rawCreateFromFormat('d.m.Y', $appointment_date)->toDateString());

        if ($appointment_id > 0) {
            $used_appointment_times = $used_appointment_times
                ->where('appointments.id', '!=', $appointment_id);
        }

        $used_appointment_times = $used_appointment_times
            ->where('status', '!=', 'canceled')
            ->pluck('start_time')
            ->toArray();

        foreach ([
            ['08', 'AM', '08'],
            ['09', 'AM', '09'],
            ['10', 'AM', '10'],
            ['11', 'AM', '11'],
            ['12', 'PM', '12'],
            ['01', 'PM', '13'],
            ['02', 'PM', '14'],
            ['03', 'PM', '15'],
            ['04', 'PM', '16'],
            ['05', 'PM', '17'],
            ['06', 'PM', '18'],
            ['07', 'PM', '19'],
            ['08', 'PM', '20'],
        ] as $hour) {
            foreach (['00', '15', '30', '45'] as $minute) {
                $time = $hour[2] . ':' . $minute;
                $time_formatted = $hour[0] . ':' . $minute . ' ' . $hour[1];

                $available_times[] = (object)[
                    'time' => $time,
                    'time_formatted' => $time_formatted,
                    'available' => !in_array($time, $used_appointment_times)
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'times' => $available_times
        ]);
    }

    public function index(Request $request)
    {

        $title = 'Appointments';

        if ($request->ajax()) {

            $request->request->add([
                'start_date' => $request->has('start_date') ? Carbon::parse($request->start_date)->toDateString() : null,
                'end_date' => $request->has('end_date') ? Carbon::parse($request->end_date)->toDateString() : null,
            ]);

            $appointments = $this->fetchAppointments($request);

            if ($request->input('patient_id', 0) > 0) {
                return response()->json([
                    'status' => 'success',
                    'html' => view('patients.appointments.list-ajax', [
                        'appointments' => $appointments->paginate(10)
                    ])->render()
                ]);
            }

            $appointments = $appointments->get();
            // $appointment_slots = config('constants.appointment_slots.adult', []);
            $updated_appointment_slots = [];

            // $start_date = $request->input('start_date', "");
            // $end_date = $request->input('end_date', "");


            foreach ($appointments as $key => $appointment) {
                if($appointment->patient->remote_patent_status == 1){
                    $rp_color = '#f5c000';
                    $rp_text = 'Father Is Away';
                }else{
                    $rp_color = '#0876ff';
                    $rp_text = 'Avalible';
                }
                $_appointment = (object)[
                    "appointment_id" => $appointment->id,
                    "title" => $appointment->patient->name,
                    "titleClickable" => $appointment
                        ? ('<a data-href="' . $appointment->patient->edit_route . '" target="_blank">' . $appointment->patient->name . ' (' . $appointment->patient->id . ')</a><br>' . $appointment->patient->date_of_birth_formatted .'<br><span style="color:' . $rp_color . '">' .$rp_text.'</>')
                        : '',
                    "start" =>  $appointment->date . 'T' . Carbon::parse($appointment->start_time)->toTimeString(),
                    // "end" => Carbon::parse($appointment->date . 'T' . $appointment->start_time)->addHour(),
                    "description" => $appointment->notes ?? '',
                    "backgroundColor" => $appointment->status_color['bg_color'] ?? '#fff',
                    // 'status' => $appointment->status,
                    // 'edit_route' => $appointment->edit_route,
                    // 'delete_route' => $appointment->delete_route,
                    // 'patient_route' => $appointment->patient->edit_route,
                    'reports_route' => $appointment->reports_route,
                    // 'not_expired' => Carbon::now()->diffInMilliseconds($appointment->date . 'T' . $appointment->end_time, false) > 0,
                    'options_html' => $appointment->options_html,
                    'rp_color' => $rp_color,
                    'rp_text' => $rp_text,
                ];

                $updated_appointment_slots[] = $_appointment;
            }

            return response()->json([
                'appointments' => $updated_appointment_slots,
            ]);
        }

        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('patients.appointments.list', [
            'title' => $title,
            'branches' => $branches
        ]);
    }
    public function insertSlots()
    {
        $appointments = Appointment::
            // whereBetween('date', ['2021-10-19', '2023-10-30'])
            //  where('date', '=', '2023-03-25')
            whereIn('status', ['completed', 'did_not_attend', 'confirmed','pending','canceled'])
            ->orderBy('start_time', 'asc')
            // ->limit(15)
            ->get()
            ->groupBy('date');

        foreach ($appointments as $_key => $_appointments) {
            for ($i = 0; $i < count($_appointments); $i++) {
                if ($_appointments[$i]->slot_id == 0) {
                    $slot = new Slot();
                    $slot->start_time = $_appointments[$i]->start_time;

                    $nextAppointment = $_appointments[$i + 1] ?? null;
                    $nextStartTime = null;

                    if ($nextAppointment) {
                        $nextStartTime = $nextAppointment->start_time;
                    } else {
                        $nextStartTime = "18:00";
                    }

                    $slot->end_time = $nextStartTime;
                    $slot->date = $_appointments[$i]->date;
                    $slot->branch_id = $_appointments[$i]->branch_id;
                    $slot->status = 'reserved';
                    $slot->save();
                    $_appointments[$i]->slot_id = $slot->id;
                    $_appointments[$i]->save();

                }
            }
            $appointments[$_key] = [];
        }
        dd('done');
    }
    public function edit(Request $request, $appointment_id = 0)
    {
        $title = 'Appointment';
        $appointment = new Appointment();
        $patient = null;
        $doctor = null;
        $branches = null;

        if (Route::currentRouteName() == 'patients.appointments.edit') {
            $appointment = Appointment::findOrFail($appointment_id);
            $branches = $appointment->branch;
            $patient = $appointment->patient;
            $doctor = $appointment->doctor;
        } else {
            $appointment->id = 0;
            $appointment->date = $request->date;
            $appointment->start_time = $request->start_time;
            // $appointment->end_time = $request->end_time;

            if ($request->has('patient_id')) {
                $patient = Patient::findOrFail($request->patient_id);
            }

            if ($request->has('doctor_id')) {
                $doctor = Doctor::findOrFail($request->doctor_id);
            }
        }

        $appointment_status = Helper::getAppointmentStatusForSelect();
        $appointment_followup_status = Helper::getAppointmentFollowupStatusForSelect();
        $appointment_types = Helper::getAppointmentTypesForSelect();
        $PatientAppointTypes = Helper::getAppointmentTypes();

        dd($patient);

        return view('patients.appointments.edit', [
            'branches' => $branches,
            'title' => $title,
            'patient' => $patient,
            'doctor' => $doctor,
            'appointment' => $appointment,
            'appointment_status' => $appointment_status,
            'appointment_followup_status' => $appointment_followup_status,
            'appointment_types' => $appointment_types,
            'AppointmentTypes'=>$PatientAppointTypes,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id' => ['required', 'numeric'],
            'patient' => ['required', 'numeric'],
            'doctor' => ['required', 'numeric'],
            'date' => ['required'],
            'start_time' => ['required'],
            'status' => ['required'],
            'followup_status' => ['required'],
            'appointment_type' => ['required'],
            'fees' => ['required'],
            'fees_paid' => ['required'],
            'branch_id' => ['required'],
        ];

        $request->validate($rules);

        $isEdit = is_numeric($request->id) && $request->id > 0;
        $appointment = new Appointment();

        if ($isEdit) {
            $appointment = Appointment::findOrFail($request->id);
            $slot = slot::findOrFail($request->slot_id);
        }

        $patient = Patient::findOrFail($request->patient);
        $patient->remote_patent_status = $request->remote_patent_status ?? 0;
        $patient->appoint_type = $request->appoint_type;
        if($request->appoint_type =='Procedure')
        {
            $patient->Appoint_reason ='Procedure';
        }
        else
        {
            $patient->Appoint_reason =$request->Appoint_reason;
        }

        $patient->save();

        $data = [
            'patient_id' => $request->patient,
            'doctor_id' => $request->doctor,
            'date' => Carbon::rawCreateFromFormat('d.m.Y', $request->date)->toDateString(),
            'slot_id' => $request->slot_id,
            'start_time' => $slot->start_time?? $request->start_time,
            // 'end_time' => $request->end_time,
            'followup_date' => $request->followup_date && $request->followup_date !== "__.__.____" ? Carbon::rawCreateFromFormat('d.m.Y', $request->followup_date)->toDateString() : null,
            'pre_assessment_date' => $request->pre_assessment_date && $request->pre_assessment_date !== "__.__.____" ? Carbon::rawCreateFromFormat('d.m.Y', $request->pre_assessment_date)->toDateString() : null,
            'status' => $request->status,
            'followup_status' => $request->followup_status,
            'appointment_type' => $request->appointment_type,
            'fees' => $request->fees ?? 0,
            'fees_paid' => $request->fees_paid ?? 0,
            'patient_sign' => $request->patient_sign,
            'father_sign' => $request->father_sign,
            'mother_sign' => $request->mother_sign,
            'interpreter_sign' => $request->interpreter_sign,
            'next_kin_sign' => $request->next_kin_sign,
            'notes' => $request->notes,
            'branch_id' => $request->branch_id,


        ];

        $old_status = $appointment->status;
        $new_status = $request->status;

        if ($request->id > 0) {
            if($appointment->slot_id) {
                $slot = Slot::where('id', $appointment->slot_id)->first();
                $slot->status = "available";
                $slot->save();
            }
            $newSlot = Slot::where('id', $request->slot_id)->first();
            $newSlot->status = "reserved";
            $newSlot->save();
            if($request->status == 'canceled') {
                $slot = Slot::where('id', $request->slot_id)->first();
                $slot->status = "available";
                $slot->save();
            }
            $appointment->update($data);
        } else {
            $appointment = Appointment::create($data);
            $slot = Slot::where('id', $request->slot_id)->first();
            $slot->status = "reserved";
            $slot->save();

        }

        // SMS
        if (config('constants.config.enable_sms')) {
            if ($old_status != $new_status) {
                switch ($new_status) {
                    case 'completed':
                        $message = config('constants.messages.appointment_completed', '');

                        if (Helper::sendSMS($message[$patient->branch_id] ?? "", $patient->cell_number)) {
                            $appointment->completed_sms_sent_at = Carbon::now()->addHour()->toDateTimeString();
                        }

                        $appointment->save();
                        break;

                    case 'canceled':
                        $message = config('constants.messages.appointment_canceled', '');

                        if (Helper::sendSMS($message, $patient->cell_number)) {
                            $appointment->canceled_sms_sent_at = Carbon::now()->addHour()->toDateTimeString();
                        }

                        $appointment->save();
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        return redirect()->route('patients.appointments.edit', ['appointment_id' => $appointment->id]);
    }

    public function delete(Request $request)
    {
        $rules = [
            'appointment_id' => ['required', 'numeric']
        ];

        $request->validate($rules);

        $appointment = Appointment::find($request->appointment_id);

        if ($appointment == null) {
            abort(404);
        }
        $slot = Slot::where('id', $appointment->slot_id)->first();
        $slot->delete();
        $appointment->additional_reports()->delete();
        $appointment->delete();

        return redirect()->back();
    }

    public function sendAppointmentEmailWithAttachments(Request $request)
    {
        $rules = [
            'id' => ['required', 'numeric'],
            'email_body' => ['required']
        ];

        $request->validate($rules);

        $body = $request->email_body ?? '';
        $appointment = Appointment::findOrFail($request->id);
        $patient = Patient::findOrFail($appointment->patient_id);

        $reportIds = config('constants.appointment_types.' . $appointment->appointment_type)['report_ids'] ?? [];

        $reports = Template::where([
            // 'patient_type' => $patient->type,
            'is_active' => '1',
            'is_report' => '0'
        ])
            ->whereIn('patient_type', ['any', $patient->type])
            ->whereIn('id', $reportIds)
            ->get()
            ->sortBy(function ($report, $key) use ($reportIds) {
                return array_search($report->id, $reportIds);
            });

        $attachments = [];
        foreach ($reports as $report) {

            $html = Helper::fillReport($appointment->id, $report->html);

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($html);
            $stream = $pdf->stream();

            $attachment = [
                'name' => str_replace(' ', '_', $report->name) . '.pdf',
                'stream' => $stream,
            ];
            $attachments[] = $attachment;
        }

        // Email
        if (config('constants.config.enable_email') && $patient->email) {
            Mail::send([], [], function (Message $m) use ($patient, $appointment, $body, $attachments) {

                $patient_age = $patient
                    ? Carbon::parse($patient->date_of_birth)->age
                    : null;

                $patient_name = $patient
                    ? ($patient_age > 16 ? 'Mr. ' : "Master ") . $patient->name
                    : "Patient";

                $is_pre_assessment = $appointment->appointment_type == "pre_assessment_appointment";

                $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

                $m->to($patient->email)
                    ->bcc('transcendconsultingrooms@gmail.com')
                    ->subject('Circumcision ' . ($is_pre_assessment ? "Pre assessment " : "") . 'Appointment Booked for ' . $patient_name . ' at ' . $appointment->date_formatted . ' @ ' . $appointment->start_time)
                    ->setBody($body, 'text/html');

                foreach ($attachments as $attachment) {
                    $m->attachData($attachment['stream'], $attachment['name'], [
                        'mime' => 'application/pdf',
                    ]);
                }

                Log::channel('email')->info(json_encode(
                    [
                        'from' => [env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')],
                        'to' => $patient->email,
                        'bcc' => 'transcendconsultingrooms@gmail.com',
                        'subject' => 'Circumcision Appointment Booked for ' . $patient_name . ' at ' . $appointment->date_formatted . ' @ ' . $appointment->start_time,
                        // 'body' => $body
                    ]
                ));
            });

            // Mail::send([], [], function (Message $m) use ($patient, $appointment, $body) {

            //     $patient_age = $patient
            //         ? Carbon::parse($patient->date_of_birth)->age
            //         : null;

            //     $patient_name = $patient
            //         ? ($patient_age > 16 ? 'Mr. ' : "Master ") . $patient->name
            //         : "Patient";

            //     $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

            //     $m->to('transcendconsultingrooms@gmail.com')
            //         ->subject('Circumcision Appointment Booked for ' . $patient_name . ' at ' . $appointment->date_formatted . ' @ ' . $appointment->start_time)
            //         ->setBody($body, 'text/html');
            // });

            $appointment->email_sent_at = Carbon::now()->addHour()->toDateTimeString();
        }

        // SMS
        if (config('constants.config.enable_sms')) {
            // $patient_age = $patient
            //     ? Carbon::parse($patient->date_of_birth)->diffInMonths()
            //     : null;

            // $medicine = '';
            // if ($patient_age) {
            //     if ($patient_age < 3 && $patient_age > -3) {
            //         $medicine = 'Paracetamol';
            //     } else {
            //         $medicine = 'Paracetamol and Ibuprofen liquid';
            //     }
            // }

            // $message = config('constants.messages.appointment_arranged', '');
            // $message = Helper::fillReport($appointment->id, $message);
            // $message = str_replace(':medicine:', $medicine, $message);

            $is_pre_assessment = $appointment->appointment_type == "pre_assessment_appointment";

            $link = route('messages.appointment-arranged', ['id' => $appointment->id]);
            $message = "Circumcision " . ($is_pre_assessment ? "Pre assessment " : "") . "appointment: Please use the following link to see appointment details. {$link}";

            if (Helper::sendSMS($message, $patient->cell_number)) {
                $appointment->sms_sent_at = Carbon::now()->addHour()->toDateTimeString();
            }
        }

        $appointment->save();

        return redirect()->route('patients.appointments.edit', ['appointment_id' => $appointment->id]);
    }

    public function followupStatusChange(Request $request)
    {
        // $logs = [];

        if ($request->has('patient_ids') && is_array($request->patient_ids)) {

            $patients = Patient::whereIn('id', $request->patient_ids)
                ->with('appointments', 'appointments.doctor')
                ->get();

            $followup_report_ids = config('constants.followup_report_ids', []);

            $all_followup_ids = [];
            foreach ($followup_report_ids as $fr_ids) {
                $all_followup_ids = array_merge($all_followup_ids, $fr_ids ?? []);
            }

            $templates = Template::whereIn('id', $all_followup_ids)
                ->get();

            foreach ($patients as $patient) {
                $marked = false;

                $followup_ids = $followup_report_ids[$patient->type] ?? [];
                // $logs["followup_report_ids"] = json_encode($followup_report_ids);
                // $logs["followup_ids"] = json_encode($followup_ids);

                $reports = $patient->reports()
                    ->whereIn('template_id', $followup_ids)
                    ->get();

                $doctor = $patient->latest_appointment->doctor;

                $report = new Report();
                $report->patient_id = $patient->id;

                foreach ($followup_ids as $followup_id) {
                    if (!$marked && $reports->where('template_id', $followup_id)->count() == 0) {
                        // $logs["followup_id : " . $followup_id] = json_encode($reports->where('id', $followup_id));
                        $template = $templates->where('id', $followup_id)->first();
                        $report->template_id = $followup_id;
                        $html = str_replace(':followup_text:', 'Did not attented', $template->html);
                        $html = str_replace(':followup_sign:', '<img data-sign-type="doctor" class="sign-class" src="' . $doctor->sign . '" />', $html);
                        $report->html = Helper::fillReport($patient->latest_appointment->id, $html);
                        $marked = true;
                    }
                }

                // if (!$marked && $reports->where('id', $followup_ids[0])->count() == 0) {
                //     $logs["followup_ids[0]"] = json_encode($reports->where('id', $followup_ids[0]));
                //     $template = $templates->where('id', $followup_ids[0])[0];
                //     $report->template_id = $followup_ids[0];
                //     $report->html = Helper::fillReport($patient->latest_appointment->id, $template->html);
                //     $marked = true;
                // } elseif (!$marked && $reports->where('id', $followup_ids[1])->count() == 0) {
                //     $logs["followup_ids[1]"] = json_encode($reports->where('id', $followup_ids[1]));
                //     $template = $templates->where('id', $followup_ids[1])[0];
                //     $report->template_id = $followup_ids[1];
                //     $report->html = Helper::fillReport($patient->latest_appointment->id, $template->html);
                //     $marked = true;
                // } elseif (!$marked && $reports->where('id', $followup_ids[2])->count() == 0) {
                //     $logs["followup_ids[2]"] = json_encode($reports->where('id', $followup_ids[2]));
                //     $template = $templates->where('id', $followup_ids[2])[0];
                //     $report->template_id = $followup_ids[2];
                //     $report->html = Helper::fillReport($patient->latest_appointment->id, $template->html);
                //     $marked = true;
                // } elseif (!$marked && $reports->where('id', $followup_ids[3])->count() == 0) {
                //     $logs["followup_ids[3]"] = json_encode($reports->where('id', $followup_ids[3]));
                //     $template = $templates->where('id', $followup_ids[3])[0];
                //     $report->template_id = $followup_ids[3];
                //     $report->html = Helper::fillReport($patient->latest_appointment->id, $template->html);
                //     $marked = true;
                // } elseif (!$marked && $reports->where('id', $followup_ids[4])->count() == 0) {
                //     $logs["followup_ids[4]"] = json_encode($reports->where('id', $followup_ids[4]));
                //     $template = $templates->where('id', $followup_ids[4])[0];
                //     $report->template_id = $followup_ids[4];
                //     $report->html = Helper::fillReport($patient->latest_appointment->id, $template->html);
                //     $marked = true;
                // }
                $report->save();

                $patient->latest_appointment->update(['followup_status' => "not_attended"]);
                Audit::updateOrCreate([
                    "patient_id" => $patient->id,
                    "appointment_id" => $patient->latest_appointment->id,
                ], [
                    'dna' => 'Yes'
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            // 'logs' => $logs,
        ]);
    }
}
