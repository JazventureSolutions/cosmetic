<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Templates';

        $patient_types = Helper::getPatientTypesForSelect();
        $report_types = Helper::getReportTypesForSelect();

        if ($request->ajax()) {

            $patient_type = $request->patient_type ?? 'All';
            $report_type = $request->report_type ?? 'All';

            $query = Template::select([
                'templates.id',
                'templates.name',
                'templates.patient_type',
                'templates.is_report',
                'templates.html',
            ])
                ->where('is_active', 1);

            if (in_array($patient_type, array_keys($patient_types)) && $patient_type != 'All') {
                $query = $query->where('templates.patient_type', $patient_type);
            }

            if (in_array($report_type, array_keys($report_types)) && $report_type != 'All') {
                $query = $query->where('templates.is_report', $report_type);
            }

            return app('datatables')->of($query)
                ->addColumn('is_report', function ($template) {
                    return $template->is_report ? 'Report' : 'Email';
                })
                ->addColumn('actions', function ($template) {
                    $html =
                        '<a href="' . $template->edit_route . '" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit details">
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
                        <a href="' . $template->preview_route . '" target="_blank" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit details">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M3,12 C3,12 5.45454545,6 12,6 C16.9090909,6 21,12 21,12 C21,12 16.9090909,18 12,18 C5.45454545,18 3,12 3,12 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                        <path d="M12,15 C10.3431458,15 9,13.6568542 9,12 C9,10.3431458 10.3431458,9 12,9 C13.6568542,9 15,10.3431458 15,12 C15,13.6568542 13.6568542,15 12,15 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg>
                            </span>
                        </a>';

                    return $html;
                })
                ->filterColumn('patient_type', function ($query, $keyword) {
                    $query
                        ->where('templates.html', 'LIKE', '%' . $keyword . '%');
                })
                ->rawColumns(['actions'])
                ->make();
        }

        return view('settings.templates.list', [
            'title' => $title,
            'patient_types' => $patient_types,
            'report_types' => $report_types
        ]);
    }

    public function edit($id = 0)
    {
        $title = 'Template';
        $template = new Template();
        $template->id = 0;

        if (Route::currentRouteName() == 'settings.templates.edit') {
            $template = Template::findOrFail($id);
        }

        return view('settings.templates.edit', [
            'title' => $title,
            'template' => $template
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'id' => ['required', 'numeric'],
            'html' => ['required'],
        ];

        $request->validate($rules);

        $isEdit = is_numeric($request->id) && $request->id > 0;
        $template = new Template();

        if ($isEdit) {
            $template = Template::findOrFail($request->id);
        }

        $data = [
            'html' => $request->html,
        ];

        if ($template->id > 0) {
            $template->update($data);
        } else {
            $template = Template::create($data);
        }

        return redirect()->route('settings.templates');
    }

    public function preview(Request $request, $id)
    {
        $title = 'Template';

        $template = Template::findOrFail($id);
        $patient = null;
        $appointment = null;
        // if ($request->has('patient_id')) {
        $patient = Patient::findOrFail($request->patient_id ?? 12);
        $appointment = Appointment::where('patient_id', $patient->id)->first();
        // }

        $patient = $appointment->patient;

        $html = Helper::fillReportDummy($appointment->id, $template->html);

        // if (!$template->is_report) {
        //     $pdf = App::make('dompdf.wrapper');
        //     $pdf->loadHTML($html);
        //     return $pdf->stream();
        // }

        // Branch
        $branch_id = Auth::user()->branch_id;
        $branch = Branch::find($branch_id);

        if ($branch == null) {
            abort(404);
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('settings.templates.preview', [
            'html' => $html,
            'has_footer' => $template->has_footer,
            'address' => $branch->address ?? '',
            'tel' => $branch->tel ?? ''
        ])->render());

        return $pdf->stream();
    }

    public function samplePdf(Request $request)
    {
        $html = view('settings.templates.sample')->render();

        $variables_data = [
            ":header:" => '<table style="margin: 0 auto;"><tbody><tr><td><img src="data:image/gif;base64,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" class="img-fluid" style="width: 85px;"></td><td><table><tbody><tr><td><span style="font-size: 30px; font-weight: 600; text-align: center;">Circumcision Clinic</span></td></tr><tr><td><span style="font-size: 18px;">Anwar Khan <span style="font-size: 12px;">MBBS,FRCS (Edin.)</span></span></td></tr></tbody></table></td></tr></tbody></table>',
        ];

        foreach ($variables_data as $variable => $variable_data) {
            $html = str_replace($variable, $variables_data[$variable] ?? '', $html);
        }

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->stream();
    }
}
