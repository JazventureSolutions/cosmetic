<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Audit;
use App\Models\Branch;
use App\Models\Report;
use App\Helpers\Helper;
use App\Imports\SlotsImport;
use App\Models\Patient;
use App\Models\Template;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Traits\AppointmentTrait;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class SlotController extends Controller
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
        // $title = 'Slot';
        // $slot = new Slot();
        // $branches = Branch::all();
        // $doctor = null;

        // return view('slots.edit', [
        //     'title' => $title,
        //     'branches' => $branches,
        // ]);
    }

    public function edit(Request $request, $slotId)
    {
        $title = 'Edit Slot';
        $slot =  Slot::findOrFail($slotId);
        $branches = $slot->branch;

        return view('slots.editMain', [
            'title' => $title,
            'branches' => $branches,
            'slot'  => $slot
        ]);
    }

    public function create(Request $request)
    {
        $title = 'Slot';
        $slot = new Slot();
        $branches = Branch::all();
        $doctor = null;

        return view('slots.edit', [
            'title' => $title,
            'branches' => $branches,
        ]);
    }

    public function store(Request $request)
    {

        $rules = [
            'branch_id' => ['required'],
            'date' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ];

        // dd($request->all());

        $request->validate($rules);

        $slotExist = Slot::where([
            'date' => Carbon::rawCreateFromFormat('d.m.Y', $request->date)->toDateString(),
            'start_time' => $request->start_time,
            'branch_id' => $request->branch_id,
        ])->exists();

        if ($slotExist) {
            return redirect()->back()->withInput()->with('error', 'Slot already exist');
        }

        Slot::create([
            'name' => $request->name,
            'date' => Carbon::rawCreateFromFormat('d.m.Y', $request->date)->toDateString(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'branch_id' => $request->branch_id,
            'desc' => $request->desc,
            'status' => 'available',
        ]);

        return redirect()->route('slots.add')->with('success', 'Slot has been created');
    }


    public function update(Request $request, $slot_id)
    {

        $rules = [
            'branch_id' => ['required'],
            'name' => ['required'],
        ];

        // dd($request->all());

        $request->validate($rules);
        $slot = Slot::findOrFail($slot_id);
        $slot =  $slot->update($request->all());

        return redirect()->route('slots.edit', $slot_id);
    }

    public function select2(Request $request)
    {
        $slots = Slot::select([
            'slots.id AS id',
            'slots.name AS text',
        ]);

        if ($request->has('q')) {
            $slots = $slots
                ->where('id', $request->q)
                ->orWhere('name', 'LIKE', '%' . $request->q . '%');
        }

        $slots = $slots
            ->limit(10)
            ->get();

        return response()->json([
            'results' => $slots,
            'request' => $request->all()
        ]);
    }

    public function availableSlots(Request $request)
    {
        $rules = [
            'date' => ['required'],
            'branch_id' => ['required']
        ];

        $request->validate($rules);
        $slots = Slot::where('branch_id', $request->branch_id)->where('date', Carbon::rawCreateFromFormat('d.m.Y', $request->date)->toDateString())->where('status', 'available')->get();
        return $slots;
    }

    public function allAvailableSlots(Request $request, $slot_id)
    {
        $rules = [
            'date' => ['required'],
            'branch_id' => ['required']
        ];

        $request->validate($rules);
        // $oldSlot = Slot::where('id', $slot_id)->get();
        $slots = Slot::where('branch_id', $request->branch_id)
            ->where('date', Carbon::rawCreateFromFormat('d.m.Y', $request->date)->toDateString())
            ->where(function ($query) use ($slot_id) {
                $query->where('status', 'available')->orWhere('id', $slot_id);
            })
            ->get();
        // $new_collection = $slots->merge($oldSlot);
        return $slots;
    }

    public function availableSlotLists(Request $request)
    {
        $title = 'Slots';
        if ($request->ajax()) {
            $slots = Slot::where('status', 'available')->with('branch');

            if ($request->has('date') && $request->date != '') {
                $slots = $slots->where(function ($p) use ($request) {
                    $p->where('slots.date', $request->date);
                });
            }
            return app('datatables')->of($slots)
                ->addColumn('id', function ($slot) {
                    return $slot->id;
                })
                ->addColumn('name', function ($slot) {
                    return $slot->name;
                })
                ->addColumn('date', function ($slot) {
                    return $slot->date;
                })
                ->addColumn('start_time', function ($slot) {
                    return $slot->start_time;
                })
                ->addColumn('end_time', function ($slot) {
                    return $slot->end_time;
                })
                ->addColumn('branch', function ($slot) {
                    return $slot->branch->name;
                })
                ->addColumn('desc', function ($slot) {
                    return $slot->desc;
                })
                ->make();
        }
        $branches = Helper::getCompanyBranchesForSelect(1);

        $slots = Slot::where('status', 'available')->get();
        return view('slots.list', [
            'title' => $title,
            'slots' => $slots,
            'branches' => $branches
        ]);
    }

    public function allSlotLists(Request $request)
    {
        $title = 'Slots';
        if ($request->ajax()) {
            $slots = Slot::with('branch')
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc');;

            if ($request->has('date') && $request->date != '') {
                Session::remove('date');
                Session::put('date', $request->date);
                $slots = $slots->where(function ($p) use ($request) {
                    $p->where('slots.date', $request->date);
                });
            }

            return app('datatables')->of($slots)
                ->addColumn('id', function ($slot) {
                    return $slot->id;
                })
                ->addColumn('name', function ($slot) {
                    return $slot->name;
                })
                ->addColumn('date', function ($slot) {
                    return $slot->date;
                })
                ->addColumn('start_time', function ($slot) {
                    return $slot->start_time;
                })
                ->addColumn('end_time', function ($slot) {
                    return $slot->end_time;
                })
                ->addColumn('branch', function ($slot) {
                    return $slot->branch->name;
                })
                ->addColumn('status', function ($slot) {
                    $html = '';
                    if ($slot->status == 'reserved') {
                        $html = '<button type="button" class="btn btn-block btn-sm btn-danger mb-2 mr-2" title="Edit details">
                                ' . $slot->status . '
                            </button>';
                    }
                    if ($slot->status == 'available') {
                        $html =  '<button style="color: #ffffff; background-color: green; border-color: green;" type="button" class="btn btn-block btn-sm mb-2 mr-2" title="Edit details">
                                ' . $slot->status . '
                            </button>';
                    }
                    return $html;
                })
                ->addColumn('action', function ($slot) {
                    $html = '<a href="' . route('slots.edit', $slot->id) . '" class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Edit Appointment">
                                Edit
                            </a>';
                    if ($slot->status == "available") {
                        $html .= '<form method="post" action="' . route('slots.delete') . '">
                        ' . csrf_field() . '
                        <input type="hidden" name="slot_id" value="' . $slot->id . '"/>
                        <button type="submit" class="btn btn-block btn-sm btn-danger mb-2 mr-2" onclick="return confirm(`Are you sure?`)">Delete</button>
                        </form>';
                    }
                    return $html;
                })
                ->addColumn('desc', function ($slot) {
                    return $slot->desc;
                })
                ->rawColumns(['status', 'action'])
                ->make();
        }
        $slots = Slot::get();
        return view('slots.all-list', [
            'title' => $title,
            'slots' => $slots
        ]);
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

    public function importexcel(Request $request)
    {
        if ($request->isMethod('post')) {
            // Session::remove("slots_error");
            Excel::import(new SlotsImport, $request->file('slots'));
            return redirect()->back()->with('success', 'Slots Has Been Successfully  Created...!!!!');
        } else {
            return view('slots.Importslots');
        }
    }

    public function delete(Request $request)
    {
        $rules = [
            'slot_id' => ['required', 'numeric']
        ];

        $request->validate($rules);

        $slot = Slot::find($request->slot_id);

        if ($slot == null) {
            abort(404);
        }

        $slot->delete();

        return redirect()->back();
    }
}
