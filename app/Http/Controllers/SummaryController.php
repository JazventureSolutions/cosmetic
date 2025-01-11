<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    private function get_query(Request $request)
    {
        $patients = Patient::select(['patients.*', 'appointments.followup_date', 'appointments.start_time', 'appointments.status', 'appointments.followup_status', 'appointments.date'])
            ->leftJoin('appointments', 'patients.id', '=', 'appointments.patient_id')
            ->where('approved', "1")
            ->has('appointments', '>', 0)
            ->with('appointments');

        if ($request->has('appointment_type') && $request->appointment_type != '') {
            $patients = $patients->where('appointments.appointment_type', $request->appointment_type);
        }

        if ($request->has('branch_id') && $request->branch_id != '') {
            $patients = $patients->where('patients.branch_id', $request->branch_id);
        }

        $patients = $patients
            ->orderBy('appointments.date', 'desc')
            ->orderBy('appointments.start_time');

        return $patients;
    }

    public function index(Request $request)
    {
        try {
            $title = 'Summary';
            $appointment_patients = $this->get_query($request);
            $pre_assesment_patients = $this->get_query($request);

            if (!($request->has('start_date') && $request->start_date != '')) {
                $request->request->add(['start_date' => Carbon::now()->toDateString()]);
            }

            if (!($request->has('end_date') && $request->end_date != '')) {
                $request->request->add(['end_date' => Carbon::now()->toDateString()]);
            }

            $appointment_patients = $appointment_patients
                ->where(function ($query) use ($request) {
                    $query->where('appointments.date', '>=', $request->start_date)
                        ->where('appointments.date', '<=', $request->end_date);
                })
                ->orWhere(function ($query) use ($request) {
                    $query->where('appointments.pre_assessment_date', '>=', $request->start_date)
                        ->where('appointments.pre_assessment_date', '<=', $request->end_date);
                });

            // $appointment_patients = $appointment_patients
            //     ->where('appointments.date', '>=', $request->start_date);
            // $pre_assesment_patients = $pre_assesment_patients
            //     ->where('appointments.pre_assessment_date', '>=', $request->start_date);

            // $appointment_patients = $appointment_patients
            //     ->where('appointments.date', '<=', $request->end_date);
            // $pre_assesment_patients = $pre_assesment_patients
            //     ->where('appointments.pre_assessment_date', '<=', $request->end_date);

            $branches = Helper::getCompanyBranchesForSelect(1);

            return view('patients.summary', [
                'title' => $title,
                'form_data' => $request->all(),
                'branches' => $branches,
                'appointment_patients' => $appointment_patients->get()
                    ->groupBy('date')
                    ->sortByDesc('date'),
                // 'pre_assesment_patients' => $pre_assesment_patients->get()
                //     ->groupBy('pre_assessment_date')
                //     ->sortByDesc('pre_assessment_date'),
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
