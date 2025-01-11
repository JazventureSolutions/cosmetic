<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\RemoteParentSignature;
use App\Models\Patient;
use App\Models\Template;
use App\Models\Report;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

class RemoteParentSignatureController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Remote Parent Signature';

        if ($request->ajax()) {

            $remote_parent_signatures = RemoteParentSignature::with('patient');;


            return app('datatables')->of($remote_parent_signatures)
                ->addColumn('name', function ($appointment) {
                    return $appointment->patient->name ?? '-';
                })
                ->addColumn('email', function ($appointment) {
                    return $appointment->patient->email ?? '-';
                })
                ->addColumn('status', function ($remote_parent_signature) {
                    $html = '';
                    if ($remote_parent_signature->is_submit == 1 && $remote_parent_signature->is_approve == 2) {
                        $html = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Reject</span>';
                    }elseif ($remote_parent_signature->is_submit == 1 && $remote_parent_signature->is_approve == 1) {
                        $html = '<span class="label label-lg font-weight-bold label-light-succes label-inline">Approve</span>';
                    }elseif ($remote_parent_signature->is_submit == 1 && $remote_parent_signature->is_approve == 0) {
                        $html = '<span class="label label-lg font-weight-bold label-light-primary label-inline">Pending</span>';
                    }else{
                        $html = '<span class="label label-lg font-weight-bold label-light-default label-inline">Not Submitted</span>';
                    }
                    return $html;
                })
                ->addColumn('actions', function ($remote_parent_signature) {
                    $html = '<a href="' . $remote_parent_signature->edit_route . '" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit details">
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

                    return $html;
                })
                ->rawColumns(['name','email','status','actions'])
                ->make();
        }

        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('remote-parent-signature.list', [
            'title' => $title,
            'branches' => $branches
        ]);
    }

    public function show($id = 0){
        $title = 'Remote Parent Signature';
        $mode = 'Remote Parent Signature';
        if ($id == 0) {
            $remote_parent_signature = new RemoteParentSignature();
        } else {
            $remote_parent_signature = RemoteParentSignature::findOrFail($id);
            $patient = Patient::findOrFail($remote_parent_signature->patient_id);
        }
        $patient_types = Helper::getPatientTypesForSelect();
        unset($patient_types['adult']);
        $patient_parent_types = Helper::getPatientParentTypeForSelect();
        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('remote-parent-signature.show', [
            'title' => $title,
            'mode' => $mode,
            'patient' => $patient ?? '',
            'remote_parent_signature' => $remote_parent_signature,
            'patient_types' => $patient_types,
            'patient_parent_types' => $patient_parent_types,
            'branches' => $branches
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'patient_id' => ['required'],
            'type' => ['required'],
            'parentType'=> ['required'],
        ];
        $request->validate($rules);
        $id = intval($request->id) ?? 0;
        if($id == 0){
            $remote_parent_signature = new RemoteParentSignature();
        }else{
            $remote_parent_signature = RemoteParentSignature::findOrFail($id);
        }
        $remote_parent_signature->patient_id =  $request->patient_id;
        $remote_parent_signature->patient_parent_type = $request->parentType;
        $remote_parent_signature->type = $request->type;
        $remote_parent_signature->cell_number = $request->cell_number;
        $remote_parent_signature->unique_key = $this->generateUniqueKey();
        if($request->approve_reject == 1){
            $remote_parent_signature->is_approve = 1;
        }elseif($request->approve_reject == 2){
            $remote_parent_signature->is_submit = 0;
            $remote_parent_signature->is_approve = 0;
            $remote_parent_signature->signature = '';
        }else{
            $remote_parent_signature->is_submit = $remote_parent_signature->is_submit ?? 0;
            $remote_parent_signature->is_approve = $remote_parent_signature->is_approve ?? 0;
        }
        $remote_parent_signature->save();
        Helper::sendWhatsAppMessage($remote_parent_signature->cell_number,$remote_parent_signature->link);
        // --------------------------------------------------- Report -----------------------------------------------------------
        if($remote_parent_signature->is_approve == 1){
            $appointment = Appointment::where('patient_id',$remote_parent_signature->patient_id)->orderby('id','desc')->first();
            $report = new Report();
            $report->patient_id = $remote_parent_signature->patient_id;
            if($remote_parent_signature->type == 'old_boy'){
                $template = Template::where('id','20')->first();
                $report->template_id = $template->id;
                if($remote_parent_signature->patient_parent_type == 'mother'){
                    $html = str_replace(':mother_sign:', '<img data-sign-type="mother" class="sign-class" src="' . $remote_parent_signature->signature . '" />', $template->html);
                    $report->html = Helper::fillReport($appointment->id, $html);
                }
                if($remote_parent_signature->patient_parent_type == 'father'){
                    $html = str_replace(':father_sign:', '<img data-sign-type="father" class="sign-class" src="' . $remote_parent_signature->signature . '" />', $template->html);
                    $report->html = Helper::fillReport($appointment->id, $html);
                }
            }
            if($remote_parent_signature->type == 'new_born'){
                $template = Template::where('id','34')->first();
                $report->template_id = $template->id;
                if($remote_parent_signature->patient_parent_type == 'mother'){
                    $html = str_replace(':mother_sign:', '<img data-sign-type="mother" class="sign-class" src="' . $remote_parent_signature->signature . '" />', $template->html);
                    $report->html = Helper::fillReport($appointment->id, $html);
                }
                if($remote_parent_signature->patient_parent_type == 'father'){
                    $html = str_replace(':father_sign:', '<img data-sign-type="father" class="sign-class" src="' . $remote_parent_signature->signature . '" />', $template->html);
                    $report->html = Helper::fillReport($appointment->id, $html);
                }
            }
            $report->save();
        }
        return redirect()->route('remote-parent-signature.edit', ['id' => $remote_parent_signature->id]);
    }

    public function generateUniqueKey()
    {
        $unique_key = Str::random(10);
        return $unique_key;
    }

    public function signature($unique_key){
        $remote_parent_signature = RemoteParentSignature::where([
            'unique_key' => $unique_key,
        ])->first();

        if ($remote_parent_signature == null) {
            abort(404);
        }

        if ($remote_parent_signature->is_submit != '0') {
            return view('error', ['title' => "Link Used", 'code' => 'Sorry!', 'message' => 'This link has already been used.', 'iframe' => '<iframe src="https://embed.lottiefiles.com/animation/75406" style="width:100%;height:80vh;"></iframe>']);
        }
        $title = 'Remote Parent Signature';
        $mode = 'Remote Parent Signature';

        return view('remote-parent-signature.signature', [
            'title' => $title,
            'mode' => $mode,
            'remote_parent_signature' => $remote_parent_signature
        ]);
    }
    public function postsignature(Request $request){
        $rules = [
            'parent_sign' => ['required'],
        ];
        $id = intval($request->id) ?? 0;
        $remote_parent_signature = RemoteParentSignature::findOrFail($id);
        $remote_parent_signature->signature = $request->parent_sign;
        $remote_parent_signature->is_submit = 1;
        $remote_parent_signature->save();
        return view('error', ['title' => "Thank You!", 'code' => 'Thank You!', 'message' => 'Thank you for submitting your Information.', 'iframe' => '<iframe src="https://embed.lottiefiles.com/animation/74878" style="width:100%;height:80vh;"></iframe>']);
    }

}
