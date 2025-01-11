<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\AdditionalReport;
use App\Models\Appointment;
use App\Models\Audit;
use App\Models\Branch;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Template;
// use Barryvdh\DomPDF\Facade as PDF;
// use niklasravnsborg\LaravelPdf\Facades\Pdf as PPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function reports(Request $request, $appointment_id = 0)
    {
        $appointment = Appointment::findOrFail($appointment_id);
        $patient = $appointment->patient;
        $title = $patient->name . '\'s Reports';

        // $reports = Template::whereIn('patient_type', [$patient->type, 'any'])
        $reports = Template::isReport()->active()->orderBy('name')->get();

        $patient_reports = Report::where('patient_id', $patient->id)
            ->whereIn('template_id', $reports->pluck('id') ?? [])
            ->get();

        $patient_types = Helper::getPatientTypesForSelect();

        return view('patients.appointments.reports.list', [
            'title' => $title,
            'appointment' => $appointment,
            'patient' => $patient,
            'reports' => $reports,
            'patient_reports' => $patient_reports,
            'patient_types' => $patient_types,
        ]);
    }

    public function getPrintReport(Request $request)
    {
        $patient_id = 21;
        $template_id = 15;

        $report = Report::where([
            'patient_id' => $patient_id,
            'template_id' => $template_id,
        ])->first();

        $root_html = '<img src="' . $report->sign_html . '" style="position:absolute;top:0;left:0;right:0;bottom:0;z-index:2;">';
        $root_html .= $report->html;

        return view('empty', ['root_html' => $root_html]);
    }

    public function printReport(Request $request)
    {
        $patient_id = $request->patient_id;
        $template_id = $request->template_id;

        $template = Template::find($template_id);

        // $sign_html = $request->sign_html_content ?? '';
        // $sign_html = rawurldecode($sign_html);

        $html = $request->html_content ?? '';
        $html = rawurldecode($html);
        // $html = Helper::fillReportWithDates($html);
        // $html = str_replace('section', 'div', $html);

        // $filename = 'report-' . uniqid() . '.pdf';

        // $report_path = config('constants.storage_paths.reports', '');
        // $report_path = str_replace('{patient_id}', $patient_id, $report_path);
        // $report_path = str_replace('{filename}', $filename, $report_path);

        // $root_html = '';
        // $root_html .= '<style>';
        // $root_html .= 'img {display: -dompdf-image !important;}';
        // $root_html .= '</style>';
        // $root_html .= '<div style="display:block;width:720px;position:relative;margin:100px auto;">';
        // $root_html .= '<img src="' . $sign_html . '" style="position:absolute;top:0;left:0;right:0;bottom:0;z-index:2;">';
        // $root_html .= $html;
        // $root_html .= '</div>';

        // $pdf = PDF::loadHTML($root_html);
        // Storage::disk('public_root')
        //     ->put($report_path, $pdf->output());

        // $pdf = PPDF::loadView('empty', ['root_html' => $root_html]);
        // return $pdf->stream('document.pdf');

        $report = Report::updateOrCreate([
            'patient_id' => $patient_id,
            'template_id' => $template_id,
        ], [
            'html' => $html,
            // 'sign_html' => $sign_html,
            // 'filename' => $filename,
        ]);

        return response()->json([
            'status' => 'success',
            'print_url' => route('report-preview', ['report_id' => $report->id]),
            // 'print_html' => view('settings.templates.preview', [
            //     'html' => $html,
            //     'has_footer' => $template->has_footer
            // ])->render()
        ]);
    }

    public function preview(Request $request)
    {
        if (!$request->has('report_id')) {
            abort(404);
        }

        // Report
        $report_id = $request->report_id;
        $report = Report::find($report_id);

        if ($report == null) {
            abort(404);
        }

        // Template
        $template_id = $report->template_id;
        $template = Template::find($template_id);

        if ($template == null) {
            abort(404);
        }

        // Patient
        $patient_id = $report->patient_id;
        $patient = Patient::find($patient_id);

        if ($patient == null) {
            abort(404);
        }

        // Branch
        $branch_id = $patient->branch_id;
        $branch = Branch::find($branch_id);

        if ($branch == null) {
            abort(404);
        }

        // return view('settings.templates.preview', [
        //     'html' => $report->html,
        //     'has_footer' => $template->has_footer
        // ])->render();

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML(view('settings.templates.preview', [
            'html' => $report->html,
            'has_footer' => $template->has_footer,
            'address' => $branch->address ?? '',
            'tel' => $branch->tel ?? ''
        ])->render());

        return $pdf->stream();
    }

    public function unsaveReport(Request $request)
    {
        $patient_id = $request->patient_id;
        $template_id = $request->template_id;

        Report::where([
            'patient_id' => $patient_id,
            'template_id' => $template_id
        ])->delete();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success'
            ]);
        }

        return back();
    }

    public function saveReport(Request $request)
    {
        $patient_id = $request->patient_id;
        $template_id = $request->template_id;

        $patient = Patient::find($request->patient_id);

        // $sign_html = $request->sign_html_content ?? '';
        // $sign_html = rawurldecode($sign_html);

        $html = $request->html_content ?? '';
        $html = rawurldecode($html);
        // $html = Helper::fillReportWithDates($html);
        // $html = str_replace('section', 'div', $html);

        // $filename = 'report-' . uniqid() . '.pdf';

        // $report_path = config('constants.storage_paths.reports', '');
        // $report_path = str_replace('{patient_id}', $patient_id, $report_path);
        // $report_path = str_replace('{filename}', $filename, $report_path);

        Report::updateOrCreate([
            'patient_id' => $patient_id,
            'template_id' => $template_id
        ], [
            'html' => $html,
            // 'sign_html' => $sign_html,
            // 'filename' => $filename,
        ]);

        // $followup_report_ids = config('constants.followup_report_ids', []);

        if (in_array($template_id, [89, 92, 93, 94, 95, 91, 100, 101, 102, 103, 90, 96, 97, 98, 99])) {
            $patient->latest_appointment->update([
                'followup_status' => "attended",
            ]);

            Audit::updateOrCreate([
                "patient_id" => $patient->id,
                "appointment_id" => $patient->latest_appointment->id,
            ], [
                'followup' => 'Yes'
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
            ]);
        }

        return back();
    }

    public function addReport(Request $request)
    {
        $appointment = Appointment::findOrFail($request->id);

        if ($appointment == null && !$request->hasFile('file')) {
            abort(404);
        }

        $patient = $appointment->patient;

        $filename = Str::slug($request->name) . '-' . uniqid()
            . '.' . $request->file('file')->getClientOriginalExtension();

        $report_path = config('constants.storage_paths.reports', '');
        $report_path = str_replace('{patient_id}', $patient->id, $report_path);
        $report_path = str_replace('{filename}', $filename, $report_path);

        Storage::disk('reports')->putFileAs(str_replace('/' . $filename, '', $report_path), $request->file, $filename);

        AdditionalReport::create([
            'name' => $request->name,
            'filename' => $filename,
            'patient_id' => $patient->id,
            'appointment_id' => $request->id,
        ]);

        return redirect()->back();
    }

    public function delReport(Request $request)
    {
        $report = AdditionalReport::where([
            'id' => $request->report_id,
            'appointment_id' => $request->appointment_id
        ])
            ->first();

        if ($report == null) {
            abort(404);
        }

        $report_path = config('constants.storage_paths.reports', '');
        $report_path = str_replace('{patient_id}', $report->patient_id, $report_path);
        $report_path = str_replace('{filename}', $report->filename, $report_path);

        Storage::disk('reports')->delete($report_path);

        $report->delete();

        return redirect()->back();
    }
}
