<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\PatientInquiry;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PatientRegisterLinkController extends Controller
{
    public function register($unique_key)
    {
        $patient_inq = PatientInquiry::where([
            'unique_key' => $unique_key,
            // 'patient_id' => null
        ])->first();

        if ($patient_inq == null) {
            abort(404);
        }

        if ($patient_inq->patient_id != '0') {
            return view('error', ['title' => "Link Used", 'code' => 'Sorry!', 'message' => 'This link has already been used.', 'iframe' => '<iframe src="https://embed.lottiefiles.com/animation/75406" style="width:100%;height:80vh;"></iframe>']);
        }

        $title = 'Patient';
        $patient = new Patient();
        $patient->id = 0;

        $patient_types = Helper::getPatientTypesForSelect();
        $branches = Helper::getCompanyBranchesForSelect(1);
        $AppointmentTypes = Helper::getAppointmentTypes();

        return view('patient-register.register', [
            'title' => $title,
            'patient' => $patient,
            'patient_types' => $patient_types,
            'branches' => $branches,
            'AppointmentTypes'=>$AppointmentTypes
        ]);
    }

    public function postRegister(Request $request, $unique_key)
    {

        $patient_inq = PatientInquiry::where([
            'unique_key' => $unique_key,
            'patient_id' => '0'
        ])->first();

        if ($patient_inq == null) {
            abort(404);
        }

        $rules = [
            'id' => ['required', 'numeric'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'branch_id' => ['required']
        ];

        $request->validate($rules);

        $patient = new Patient();

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
            'type' => $request->type,
            'appoint_type' => $request->appoint_type,
            'Appoint_reason' => $request->Appoint_reason,
            'approved' => $patient->approved ?? "0",
            'branch_id' => $request->branch_id,
            'remote_patent_status' => $patient_inq->remote_patent_status ?? 0,
            // 'period_id' => $patient->period_id ?? "1"
        ];

        $patient = Patient::create($data);

        $patient_inq->patient_id = $patient->id;
        $patient_inq->save();

        return view('error', ['title' => "Thank You!", 'code' => 'Thank You!', 'message' => 'Thank you for submitting your Information.', 'iframe' => '<iframe src="https://embed.lottiefiles.com/animation/74878" style="width:100%;height:80vh;"></iframe>']);
    }

    public function index(Request $request)
    {
        $title = 'Patient Inquiries';

        if ($request->ajax()) {

            $patient_inqs = PatientInquiry::select([
                'patient_inquiries.id',
                'patient_inquiries.cell_number',
                'patient_inquiries.email',
                'patient_inquiries.patient_id',
            ])
                ->leftJoin('patients', function ($join) {
                    $join->on('patient_inquiries.patient_id', 'patients.id');
                });

            if ($request->has('branch_id')) {
                $patient_inqs = $patient_inqs
                    ->where(function ($where) use ($request) {
                        $where->where('patient_inquiries.patient_id', '0')
                            ->orWhere('patients.branch_id', $request->branch_id);
                    });
            }

            return app('datatables')->of($patient_inqs)
                ->addColumn('remote_patent_status', function ($patient_inq) {
                    if ($patient_inq->remote_patent_status == 1) {
                        return '<span style="padding:2px 4px;border-radius:4px;background-color:#ebde7f91;color:#a38701">Away</span>';
                    }else{
                        return '<span style="padding:2px 4px;border-radius:4px;background-color:#c4f395;color:#48a357">Avalible</span>';
                    }
                })
                ->addColumn('registered', function ($patient_inq) {
                    $html = '';
                    if ($patient_inq->patient_id == '0') {
                        $html = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Not Yet Registered</span>';
                    } else {
                        $html = '<a href="' . route('patients.edit', ['id' => $patient_inq->patient_id]) . '" target="_blank" class="mr-2"><span class="label label-lg font-weight-bold label-light-success label-inline">( ' . $patient_inq->patient->name . ' )</span></a>';
                        if ($patient_inq->patient->latest_appointment) {
                            $html .= '<a href="' . $patient_inq->patient->latest_appointment_route . '" target="_blank" class="mr-2"><span class="label label-lg font-weight-bold label-light-success label-inline">Appointment</span></a>';
                        }
                    }
                    return $html;
                })
                ->addColumn('actions', function ($patient_inq) {
                    $html = '<a href="' . $patient_inq->edit_route . '" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit details">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) "/>
                                        <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon" title="Delete">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                        </a>';

                    return $html;
                })
                ->rawColumns(['remote_patent_status','registered', 'actions'])
                ->make();
        }

        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('patient-register.list', [
            'title' => $title,
            'branches' => $branches
        ]);
    }

    public function edit($id = 0)
    {
        $title = 'Patient Inquiry';
        $patient_inq = new PatientInquiry();
        $patient_inq->id = 0;

        if (Route::currentRouteName() == 'patient-register.edit') {
            $patient_inq = PatientInquiry::findOrFail($id);
        }

        return view('patient-register.edit', [
            'title' => $title,
            'patient_inq' => $patient_inq
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id' => ['required', 'numeric'],
            'cell_number' => ['required'],
        ];

        $request->validate($rules);

        $isEdit = is_numeric($request->id) && $request->id > 0;
        $patient_inq = new PatientInquiry();

        if ($isEdit) {
            $patient_inq = PatientInquiry::findOrFail($request->id);
        }

        $data = [
            'email' => $request->email,
            'cell_number' => $request->cell_number,
            'remote_patent_status' => $request->remote_patent_status
        ];

        if ($request->form_type == 'submit') {
            $data = array_merge($data, [
                'unique_key' => $this->generateUniqueKey()
            ]);
        }

        if (config('constants.config.enable_sms') && in_array($request->form_type, ['sms', 'sms_email'])) {
            // $phone = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $patient_inq->cell_number);
            $message = config('constants.messages.patient_register', '');
            $message = str_replace(':patient_register_link:', $patient_inq->link, $message);

            if (Helper::sendSMS($message, $request->cell_number)) {
                $data = array_merge($data, [
                    'sms_sent_at' => Carbon::now()->addHour()->toDateTimeString()
                ]);
            }
        }

        if (config('constants.config.enable_email') && in_array($request->form_type, ['email', 'sms_email'])) {
            // $phone = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $patient_inq->cell_number);
            $message = config('constants.messages.patient_register', '');
            $message = str_replace(':patient_register_link:', $patient_inq->link, $message);

            Mail::send([], [], function (Message $m) use ($request, $message) {
                $m->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $m->to($request->email)
                    // ->bcc('transcendconsultingrooms@gmail.com')
                    ->subject('Appointment Booking')
                    ->setBody($message);

                Log::channel('email')->info(json_encode(
                    [
                        'from' => [env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')],
                        'to' => $request->email,
                        'bcc' => '',
                        'subject' => 'Appointment Booking',
                        // 'body' => $message
                    ]
                ));
            });

            $data = array_merge($data, [
                'email_sent_at' => Carbon::now()->addHour()->toDateTimeString()
            ]);
        }

        if ($isEdit) {
            $patient_inq->update($data);
        } else {
            $patient_inq = PatientInquiry::create($data);
        }

        return redirect()->route('patient-register.edit', ['id' => $patient_inq->id]);
    }

    public function generateUniqueKey()
    {
        $unique_key = '';

        do {
            $unique_key = Str::random(10); // sha1(time());
        } while (PatientInquiry::where("unique_key", "=", $unique_key)->first() != null);

        return $unique_key;
    }
}
