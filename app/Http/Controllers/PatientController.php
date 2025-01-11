<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function select2(Request $request)
    {
        $patients = Patient::select([
            'patients.id AS id',
            DB::raw('CONCAT(patients.name, " (", patients.id, ")") AS text'),
            'patients.type AS type',
        ])->where('approved', "1");

        if ($request->has('q')) {
            $patients = $patients
                ->where('id', $request->q)
                ->orWhere('name', 'LIKE', '%' . $request->q . '%');
        }

        $patients = $patients
            ->limit(10)
            ->get();

        return response()->json([
            'results' => $patients,
            'request' => $request->all()
        ]);
    }

    public function approve(Request $request)
    {
        $patient = Patient::findOrFail($request->id);

        if ($patient == null) {
            abort(404);
        }

        $patient->approved = "1";
        $patient->save();

        return redirect()->route('unapproved-patients');
    }

    public function index(Request $request)
    {
        $title = 'Patients';

        if ($request->ajax()) {

            $patients = Patient::select(['patients.*', 'appointments.followup_date', 'appointments.start_time', 'appointments.status', 'appointments.followup_status', 'appointments.date'])
                ->leftJoin('appointments', 'patients.id', '=', 'appointments.patient_id')
                ->leftJoin('audits', 'patients.id', '=', 'audits.patient_id')
                ->with(['appointments', 'audit']);

            if (Route::currentRouteName() == 'patients') {
                $patients = $patients->where('approved', "1")
                    ->has('appointments', '>', 0);

                if ($request->has('date') && $request->date != '') {
                    $patients = $patients->where(function ($p) use ($request) {
                        $p
                            ->where('appointments.date', $request->date)
                            ->orWhere(function ($query) use ($request) {
                                $query->where('appointments.date', null)
                                    ->where('appointments.pre_assessment_date', $request->date);
                            });
                    });
                }
            } elseif (Route::currentRouteName() == 'unapproved-patients') {
                $patients = $patients
                    ->where('approved', "0");
            } elseif (Route::currentRouteName() == 'unappointed-patients') {
                $patients = $patients
                    ->where('approved', "1")
                    ->has('appointments', '==', 0);
            } elseif (Route::currentRouteName() == 'followup-patients') {
                $patients = $patients
                    ->where('approved', "1")
                    ->has('appointments', '>', 0)
                    ->where('status', '!=', 'canceled')
                    ->whereIn('followup_status', ['pending']) //, 'not_attended'])
                    ->whereNotNull('appointments.followup_date');

                // if ($request->has('date') && $request->date != '') {
                //     $patients = $patients->where(function ($query) use ($request) {
                //         $query->where('appointments.followup_date', $request->date);
                //     });
                // }

                if ($request->has('start_date') && $request->start_date != '') {
                    $patients = $patients->where(function ($query) use ($request) {
                        $query->where('appointments.followup_date', '>=', $request->start_date);
                    });
                }

                if ($request->has('end_date') && $request->end_date != '') {
                    $patients = $patients->where(function ($query) use ($request) {
                        $query->where('appointments.followup_date', '<=', $request->end_date);
                    });
                }
            }

            if ($request->has('appointment_type') && $request->appointment_type != '') {
                $patients = $patients->where('appointments.appointment_type', $request->appointment_type);
            }

            if ($request->has('branch_id') && $request->branch_id != '') {
                $patients = $patients->where('patients.branch_id', $request->branch_id);
            }

            if (Route::currentRouteName() == 'followup-patients') {
                $followup_report_ids = config("constants.followup_report_ids", []);

                $followup_ids = [];
                foreach ($followup_report_ids as $fr_ids) {
                    $followup_ids = array_merge($followup_ids, $fr_ids ?? []);
                }

                $patients = $patients
                    ->where('followup_date', '!=', null)
                    ->with(['reports' => function ($query) use ($followup_ids) {
                        $query->whereIn('id', $followup_ids);
                    }])
                    ->orderBy('followup_date', 'asc');
            } else {
                $patients = $patients
                    ->orderBy('date', 'asc')
                    ->orderBy('start_time', 'asc');
            }

            return app('datatables')->of($patients)
                ->addColumn('id', function ($patient) {
                    if (Route::currentRouteName() == 'followup-patients') {
                        return '<input type="checkbox" name="select-checkbox[]" value=' . $patient->id . ' class="select-checkbox"> ' . $patient->id;
                    } else {
                        return $patient->id;
                    }
                })
                ->addColumn('name', function ($patient) {
                    return '<b>' . $patient->name . '</b><br> ' . $patient->address;
                })
                ->addColumn('parents_name', function ($patient) {
                    return 'Father: <b>' . $patient->father_name . '</b><br> Mother: <b>' . $patient->mother_name . '</b>';
                })
                ->addColumn('appointment_time', function ($patient) {
                    if ($patient->latest_appointment) {
                        return Carbon::parse($patient->latest_appointment->date)->format('d.m.Y') . '<br><br> ' . $patient->latest_appointment->start_time;
                    }
                    return "-";
                })
                ->addColumn('date_of_birth', function ($patient) {
                    return Carbon::parse($patient->date_of_birth)->format('d.m.Y');
                })
                ->addColumn('fees', function ($patient) {
                    if ($patient->latest_appointment) {
                        return 'T: &pound;' . $patient->latest_appointment->fees . '<br> P: &pound;' . $patient->latest_appointment->fees_paid . '<br> <b>R: &pound;' . $patient->latest_appointment->fees_remaining . '</b>';
                    }
                    return "-";
                })
                ->addColumn('contact_details', function ($patient) {
                    return 'Email: ' . $patient->email . ',<br> ' . $patient->cell_number . ',<br> ' . $patient->phone;
                })
                ->addColumn('weight_of_child', function ($patient) {
                    return  $patient->weight_of_child;
                })
                ->addColumn('status', function ($patient) {
                    if ($patient->latest_appointment) {
                        if (Route::currentRouteName() == 'followup-patients') {
                            return $patient->followup_status;
                        } else {
                            $status = Helper::getAppointmentStatus($patient->status);
                            return '<span style="padding:2px 4px;border-radius:4px;background-color:' . $status['bg_color'] . ';color:' . $status['text_color'] . '">' . $status['name'] . '</span>';
                        }
                    } elseif (Route::currentRouteName() == 'unapproved-patients') {
                        $similar_patients = $patient->similar_patients->count();
                        return 'Unapproved' . ($similar_patients > 0 ? '<br><br><span style="padding:2px 4px;border-radius:4px;background-color:#F64E60;color:#FFE2E5;">' . $similar_patients . ' similar</span>' : '');
                    } elseif (Route::currentRouteName() == 'unappointed-patients') {
                        return 'Unappointed';
                    }
                })

                ->addColumn('remote_patent_status', function ($patient) {

                    if ($patient->remote_patent_status == 1) {
                        return '<span style="padding:2px 4px;border-radius:4px;background-color:#ebde7f91;color:#a38701">Away</span>';
                    }else{
                        return '<span style="padding:2px 4px;border-radius:4px;background-color:#c4f395;color:#48a357">Avalible</span>';
                    }
                })
                ->addColumn('appoint_type', function ($patient) {
                        if($patient->appoint_type =='Procedure')
                        {
                            return '<span style="padding:2px 4px;border-radius:4px;background-color:#f395c6;color:#ffffff">Procedure</span>';
                        }
                        elseif($patient->appoint_type =='Consultation')
                        {
                            return '<span style="padding:2px 4px;border-radius:4px;background-color:#9d81d7;color:#ffffff">Consultation</span>';
                        }
                        else
                        {
                            return '';
                        }

                })

                ->addColumn('actions', function ($patient) {
                    $html = '';

                    if (Route::currentRouteName() == 'unapproved-patients') {
                        $html = '<form action="' . $patient->approve_route . '" method="post" style="float: left">
                            ' . csrf_field() . '
                            <input type="hidden" name="id" value="' . $patient->id . '" />
                            <button type="submit" class="btn btn-block btn-sm btn-primary mr-2" title="Approve">
                                <i class="far fa-check-circle icon-md"></i> Approve
                            </button>
                        </form>
                        <a href="' . $patient->edit_route . '" class="btn btn-block btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit details">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) "/>
                                        <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                        </a>';
                    } elseif (Route::currentRouteName() == 'unappointed-patients') {
                        $html .= '<a href="' . $patient->add_appointment_route . '" class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Add Appointment">
                            Add Appointment
                        </a>';

                        $html .= '<a class="btn btn-block btn-sm btn-default btn-text-primary btn-hover-primary mb-2 mr-2 btn-send-payment-message" data-patient-id="' . $patient->id . '" title="Send Payment Message">
                            Send Payment Message';

                        if ($patient->payment_message_sent) {
                            $html .= ' <br><span><b>(Sent on ' . Carbon::parse($patient->payment_message_sent)->toDayDateTimeString() . ')</b></span>';
                        }

                        $html .= '</a>';
                    } else {
                        if ($patient->appointments->count() > 0) {
                            $html .= '<a href="' . $patient->latest_appointment_route . '" class="btn btn-block btn-sm btn-info mb-2 mr-2" title="Latest Appointment Reports">
                                Reports
                            </a>
                            <a href="' . $patient->latest_appointment_edit_route . '" class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Edit Appointment">
                                Edit Appointment
                            </a>';
                        }

                        $html .= '
                        <a href="' . $patient->edit_route . '" class="btn btn-block btn-sm btn-primary mb-2 mr-2" title="Edit details">
                            Edit Patient
                        </a>';
                    }

                    // $html .= '
                    //     <form action="' . route('patients.delete') . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                    //         ' . csrf_field() . '
                    //         <input type="hidden" name="id" value="' . $patient->id . '">
                    //         <button type="submit" class="btn btn-block btn-sm btn-danger mb-2 mr-2" title="Edit details">
                    //             Delete Patient
                    //         </button>
                    //     <form>';

                    return $html;
                })
                ->rawColumns(['id', 'name', 'parents_name', 'appointment_time', 'fees', 'status', 'remote_patent_status','appoint_type','contact_details', 'actions'])
                ->filterColumn('id', function ($query, $keyword) {
                    $query
                        ->where('patients.id', 'LIKE', '%' . $keyword . '%');
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query
                        ->where('name', 'LIKE', '%' . $keyword . '%');
                })
                ->filterColumn('status', function ($query, $keyword) {
                    if (Route::currentRouteName() != 'followup-patients') {
                        $query
                            ->where('appointments.status', 'LIKE', '%' . $keyword . '%');
                    }
                })
                ->filterColumn('remote_patent_status', function ($query, $keyword) {
                    if (Route::currentRouteName() != 'followup-patients') {
                        if($keyword == 'Away'){
                            $query->where('appointments.remote_patent_status', '1');
                        }else{
                            $query->where('appointments.remote_patent_status', '0');
                        }
                    }
                })
                ->filterColumn('followup_date', function ($query, $keyword) {
                    $query
                        ->where('appointments.followup_date', 'LIKE', '%' . $keyword . '%');
                })
                ->orderColumn('appointment_time', function ($query, $order) {
                    $query
                        ->orderBy('date', $order)
                        ->orderBy('start_time', $order);
                })
                ->make();
        }

        $selected_appointment_type = $request->appointment_type ?? -1;
        $branches = Helper::getCompanyBranchesForSelect(1);
        $appointment_types = Helper::getAppointmentTypesForSelect();

        if (Route::currentRouteName() == 'followup-patients') {
            return view('patients.followup-list', [
                'title' => $title,
                'branches' => $branches,
                'appointment_types' => $appointment_types,
                'selected_appointment_type' => $selected_appointment_type,
            ]);
        } else {
            return view('patients.list', [
                'title' => $title,
                'branches' => $branches,
                'appointment_types' => $appointment_types,
                'selected_appointment_type' => $selected_appointment_type,
            ]);
        }
    }

    public function edit($id = 0)
    {
        $title = 'Patient';
        $mode = 'patient';
        $patient = new Patient();
        $patient->id = 0;
        $patient->approved = "1";

        if (Route::currentRouteName() == 'patients.edit') {
            $patient = Patient::findOrFail($id);
        }

        $patient_types = Helper::getPatientTypesForSelect();
        $AppointmentTypes = Helper::getAppointmentTypes();
        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('patients.edit', [
            'title' => $title,
            'mode' => $mode,
            'patient' => $patient,
            'patient_types' => $patient_types,
            'AppointmentTypes'=>$AppointmentTypes,
            'branches' => $branches
        ]);
    }

    public function store(Request $request)
    {

        $rules = [
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'branch_id' => ['required']
        ];

        $request->validate($rules);

        $isEdit = is_numeric($request->id) && $request->id > 0;
        $patient = new Patient();

        if ($isEdit) {
            $patient = Patient::findOrFail($request->id);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cell_number' => $request->cell_number,
            'house_no' => $request->house_no,
            'street' => $request->street,
            'city' => $request->city,
            'post_code' => $request->post_code,
            'date_of_birth' => $request->date_of_birth,
            'weight_of_child' => $request->weight_of_child ?? '0.00',
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'next_kin' => $request->next_kin,
            'next_kin_relationship' => $request->next_kin_relationship,
            'next_kin_address' => $request->next_kin_address,
            'next_kin_phone' => $request->next_kin_phone,
            'gp_details' => $request->gp_details,
            'appoint_type' => $request->appoint_type,
            'Appoint_reason' => $request->Appoint_reason,
            'type' => $request->type,
            'approved' => $patient->approved ?? "1",
            'branch_id' => $request->branch_id,
            // 'period_id' => $patient->period_id ?? "1"
        ];

        if ($patient->id > 0) {
            $patient->update($data);
        } else {
            $patient = Patient::create($data);
            return redirect()->route('patients.appointments.add', ['patient_id' => $patient->id]);
        }

        if ($patient->approved == "0") {
            return redirect()->route('unapproved-patients');
        }

        return redirect()->route('patients');
    }

    public function delete(Request $request)
    {
        $patient = Patient::find($request->id);

        if ($patient == null) {
            abort(404);
        }

        $patient->additional_reports()->delete();

        $report_path = config('constants.storage_paths.reports', '');
        $report_path = str_replace('{patient_id}', $patient->id, $report_path);
        $report_path = str_replace('{filename}', '', $report_path);

        Storage::disk('reports')->deleteDirectory($report_path);

        $patient->appointments()->delete();
        $patient->reports()->delete();
        $patient->inquiry()->delete();
        $patient->delete();

        return redirect()->back();
    }

    public function sendPaymentMessage(Request $request)
    {
        $patient = Patient::findOrFail($request->patient_id);

        if ($patient == null) {
            abort(404);
        }

        $message = config('constants.messages.appointment_deposit', '');

        // Email
        if (config('constants.config.enable_email') && $patient->email) {
            Mail::send([], [], function (Message $m) use ($patient, $message) {
                $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $m->to($patient->email)
                    // ->bcc('transcendconsultingrooms@gmail.com')
                    ->subject('Please make a deposit')
                    ->setBody($message);

                Log::channel('email')->info(json_encode(
                    [
                        'from' => [env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')],
                        'to' => $patient->email,
                        'bcc' => '',
                        'subject' => 'Please make a deposit',
                        // 'body' => $message
                    ]
                ));
            });
        }

        // SMS
        if (config('constants.config.enable_sms')) {

            $link = route('messages.appointment-deposit');
            $message = "Please use the following link to pay the deposit of £50.00 (NON REFUNDABLE). {$link}";

            if (Helper::sendSMS($message, $patient->cell_number)) {
                $patient->payment_message_sent = Carbon::now()->addHour()->toDateTimeString();
            }
        }

        $patient->save();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
            ]);
        }

        return redirect()->back();
    }

    public function similar(Request $request)
    {
        $patients = Patient::where('id', '!=', $request->id)
            ->where(function ($query) use ($request) {
                if (!in_array($request->input('name'), [null, ''])) {
                    $query->orWhere('name', $request->input('name'));
                }

                if (!in_array($request->input('cell_number'), [null, ''])) {
                    $query->orWhere('cell_number', $request->input('cell_number'));
                }

                if (!in_array($request->input('phone'), [null, ''])) {
                    $query->orWhere('phone', $request->input('phone'));
                }

                if (!in_array($request->input('email'), [null, ''])) {
                    $query->orWhere('email', $request->input('email'));
                }
            });


        $patients = $patients->get();

        $html = '';
        foreach ($patients as $patient) {
            $html .=
                '<div class="d-flex align-items-center mb-7">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-50px mr-5">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero"></path>
                            </g>
                        </svg>
                    </div>
                    <!--end::Avatar-->
                    <!--begin::Text-->
                    <div class="flex-grow-1">
                        <a href="' . $patient->edit_route . '" class="text-dark fw-bolder text-hover-primary fs-6" target="_blank">' . $patient->name . '</a>
                        <span class="text-muted d-block fw-bold">' . $patient->cell_number . ' - ' . $patient->phone . ' - ' . $patient->email . '</span>
                    </div>
                    <!--end::Text-->
                </div>';
        }

        return response()->json([
            'status' => 'success',
            'html' => $html,
            'count' => $patients->count()
        ]);
    }
}
