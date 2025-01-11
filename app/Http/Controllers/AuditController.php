<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Audit;
use App\Traits\AppointmentTrait;
use Carbon\Carbon;

class AuditController extends Controller
{
    use AppointmentTrait;

    public function index(Request $request)
    {
        $title = 'Audit';

        if ($request->ajax()) {

            // $request->request->add([
            //     'start_date' => $request->has('start_date') ? Carbon::parse($request->start_date)->toDateString() : null,
            //     'end_date' => $request->has('end_date') ? Carbon::parse($request->end_date)->toDateString() : null,
            // ]);

            // $year = $request->has('year') ? $request->input('year') : "2021";

            // $request->request->add([
            //     'start_date' => $request->has('start_date')
            //         ? Carbon::parse($request->start_date)->toDateString()
            //         : $year . "-01-01",
            //     'end_date' => $request->has('end_date')
            //         ? Carbon::parse($request->end_date)->toDateString()
            //         : $year . "-12-31",
            // ]);

            $appointments = $this->fetchAppointments($request);
            $appointments->getQuery()->orders = null;
            $appointments = $appointments->with('audit')
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc');

            return app('datatables')->of($appointments)
                ->addColumn('id', function ($appointment) {
                    return $appointment->patient->id;
                })
                ->addColumn('year', function ($appointment) {
                    return Carbon::parse($appointment->date)->year;
                })
                ->addColumn('month', function ($appointment) {
                    return Carbon::parse($appointment->date)->monthName;
                })
                ->addColumn('date', function ($appointment) {
                    return Carbon::parse($appointment->date)->day;
                })
                ->addColumn('name', function ($appointment) {
                    return $appointment->patient->name ?? '-';
                })
                ->addColumn('date_of_birth', function ($appointment) {
                    return Carbon::parse($appointment->patient->date_of_birth)->format('d-m-Y');
                })
                ->addColumn('upto_6_12', function ($appointment) {
                    // UP TO 06/12
                    $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                    return $age_in_months < 6 ? "Yes" : "";
                })
                ->addColumn('for_6_18_12', function ($appointment) {
                    // 06-18/12
                    $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                    return $age_in_months >= 6 && $age_in_months < 18 ? "Yes" : "";
                })
                ->addColumn('for_18_03yrs', function ($appointment) {
                    // 18-03yrs
                    $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                    return $age_in_months >= 18 && $age_in_months < 36 ? "Yes" : "";
                })
                ->addColumn('for_03_06yrs', function ($appointment) {
                    // 03-06yrs
                    $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                    return $age_in_months >= 36 && $age_in_months < 72 ? "Yes" : "";
                })
                ->addColumn('for_06_10yrs', function ($appointment) {
                    // 06-10yrs
                    $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                    return $age_in_months >= 72 && $age_in_months < 120 ? "Yes" : "";
                })
                ->addColumn('for_10_12yrs', function ($appointment) {
                    // 10-12yrs
                    $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                    return $age_in_months >= 120 && $age_in_months < 144 ? "Yes" : "";
                })
                ->addColumn('over_12yrs', function ($appointment) {
                    // Over 12yrs
                    $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                    return $age_in_months >= 144 ? "Yes" : "";
                })
                ->addColumn('hypospediasis', function ($appointment) {
                    return $appointment->audit->hypospediasis ?? '';
                })
                ->addColumn('any_other', function ($appointment) {
                    return $appointment->audit->any_other ?? '';
                })
                ->addColumn('peripubic_fat', function ($appointment) {
                    return $appointment->audit->peripubic_fat ?? '';
                })
                ->addColumn('circumplast', function ($appointment) {
                    return $appointment->audit->circumplast ?? '';
                })
                ->addColumn('plastibell', function ($appointment) {
                    return $appointment->audit->plastibell ?? '';
                })
                ->addColumn('resection', function ($appointment) {
                    return $appointment->audit->resection ?? '';
                })
                ->addColumn('bleeding', function ($appointment) {
                    return $appointment->audit->bleeding ?? '';
                })
                ->addColumn('retained_plastibell', function ($appointment) {
                    return $appointment->audit->retained_plastibell ?? '';
                })
                ->addColumn('adhesion', function ($appointment) {
                    return $appointment->audit->adhesion ?? '';
                })
                ->addColumn('division_of_adhesion', function ($appointment) {
                    return $appointment->audit->division_of_adhesion ?? '';
                })
                ->addColumn('residual_skin', function ($appointment) {
                    return $appointment->audit->residual_skin ?? '';
                })
                ->addColumn('revisions_follow_ups', function ($appointment) {
                    return $appointment->audit->revisions_follow_ups ?? '';
                })
                ->addColumn('followup', function ($appointment) {
                    return $appointment->audit->followup ?? '';
                })
                ->addColumn('dna', function ($appointment) {
                    return $appointment->audit->dna ?? '';
                })
                ->addColumn('not_done', function ($appointment) {
                    return $appointment->audit->not_done ?? '';
                })
                ->addColumn('consultation_only', function ($appointment) {
                    return $appointment->audit->consultation_only ?? '';
                })
                ->addColumn('not_required', function ($appointment) {
                    return $appointment->audit->not_required ?? '';
                })
                ->addColumn('hypopaedias', function ($appointment) {
                    return $appointment->audit->hypopaedias ?? '';
                })
                ->addColumn('torsion', function ($appointment) {
                    return $appointment->audit->torsion ?? '';
                })
                ->addColumn('fat', function ($appointment) {
                    return $appointment->audit->fat ?? '';
                })
                ->addColumn('hydrocoele', function ($appointment) {
                    return $appointment->audit->hydrocoele ?? '';
                })
                ->addColumn('comments', function ($appointment) {
                    return $appointment->audit->comments ?? '';
                })
                ->addColumn('oral', function ($appointment) {
                    return $appointment->audit->oral ?? '';
                })
                ->addColumn('topical', function ($appointment) {
                    return $appointment->audit->topical ?? '';
                })
                ->addColumn('infection', function ($appointment) {
                    return $appointment->audit->infection ?? '';
                })
                ->addColumn('curve', function ($appointment) {
                    return $appointment->audit->curve ?? '';
                })
                ->addColumn('soft_adhesion', function ($appointment) {
                    return $appointment->audit->soft_adhesion ?? '';
                })
                ->addColumn('chordee', function ($appointment) {
                    return $appointment->audit->chordee ?? '';
                })
                ->addColumn('without_father', function ($appointment) {
                    return $appointment->audit->without_father ?? '';
                })
                ->addColumn('frenuloplasty', function ($appointment) {
                    return $appointment->audit->frenuloplasty ?? '';
                })
                ->addColumn('webbed_penis', function ($appointment) {
                    return $appointment->audit->webbed_penis ?? '';
                })
                ->addColumn('redundant_inner_skin', function ($appointment) {
                    return $appointment->audit->redundant_inner_skin ?? '';
                })
                ->addColumn('secondary_phimosis', function ($appointment) {
                    return $appointment->audit->secondary_phimosis ?? '';
                })
                ->addColumn('actions', function ($appointment) {
                    $html = '
                        <a href="' . route('patients.appointments.audit', ['appointment_id' => $appointment->id]) . '" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit">
                            Edit
                        </a>';

                    return $html;
                })
                ->rawColumns(['actions'])
                ->filterColumn('id', function ($appointment, $keyword) {
                    $appointment->whereHas('patient', function ($patient) use ($keyword) {
                        $patient
                            ->where('id', 'LIKE', '%' . $keyword . '%');
                    });
                })
                ->make();
        }

        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('patients.appointments.audit.index', [
            'title' => $title,
            'branches' => $branches,
        ]);
    }

    public function getAuditData(Request $request) {
        $title = 'Audit';

        // $request->request->add([
        //     'start_date' => $request->has('start_date') ? Carbon::parse($request->start_date)->toDateString() : null,
        //     'end_date' => $request->has('end_date') ? Carbon::parse($request->end_date)->toDateString() : null,
        // ]);

        // $year = $request->has('year') ? $request->input('year') : "2021";

        // $request->request->add([
        //     'start_date' => $request->has('start_date')
        //         ? Carbon::parse($request->start_date)->toDateString()
        //         : $year . "-01-01",
        //     'end_date' => $request->has('end_date')
        //         ? Carbon::parse($request->end_date)->toDateString()
        //         : $year . "-12-31",
        // ]);

        $appointments = $this->fetchAppointmentsforExport($request);

        $appointments->getQuery()->orders = null;
        $appointments = $appointments->with('audit')
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc');

        // dd($appointments);
        return app('datatables')->of($appointments)
            ->addColumn('id', function ($appointment) {
                return $appointment->patient->id;
            })
            ->addColumn('year', function ($appointment) {
                return Carbon::parse($appointment->date)->year;
            })
            ->addColumn('month', function ($appointment) {
                return Carbon::parse($appointment->date)->monthName;
            })
            ->addColumn('date', function ($appointment) {
                return Carbon::parse($appointment->date)->day;
            })
            ->addColumn('name', function ($appointment) {
                return $appointment->patient->name ?? '-';
            })
            ->addColumn('date_of_birth', function ($appointment) {
                return Carbon::parse($appointment->patient->date_of_birth)->format('d-m-Y');
            })
            ->addColumn('upto_6_12', function ($appointment) {
                // UP TO 06/12
                $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                return $age_in_months < 6 ? "Yes" : "";
            })
            ->addColumn('for_6_18_12', function ($appointment) {
                // 06-18/12
                $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                return $age_in_months >= 6 && $age_in_months < 18 ? "Yes" : "";
            })
            ->addColumn('for_18_03yrs', function ($appointment) {
                // 18-03yrs
                $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                return $age_in_months >= 18 && $age_in_months < 36 ? "Yes" : "";
            })
            ->addColumn('for_03_06yrs', function ($appointment) {
                // 03-06yrs
                $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                return $age_in_months >= 36 && $age_in_months < 72 ? "Yes" : "";
            })
            ->addColumn('for_06_10yrs', function ($appointment) {
                // 06-10yrs
                $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                return $age_in_months >= 72 && $age_in_months < 120 ? "Yes" : "";
            })
            ->addColumn('for_10_12yrs', function ($appointment) {
                // 10-12yrs
                $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                return $age_in_months >= 120 && $age_in_months < 144 ? "Yes" : "";
            })
            ->addColumn('over_12yrs', function ($appointment) {
                // Over 12yrs
                $age_in_months = Carbon::parse($appointment->date)->diffInMonths($appointment->patient->date_of_birth);
                return $age_in_months >= 144 ? "Yes" : "";
            })
            ->addColumn('hypospediasis', function ($appointment) {
                return $appointment->audit->hypospediasis ?? '';
            })
            ->addColumn('any_other', function ($appointment) {
                return $appointment->audit->any_other ?? '';
            })
            ->addColumn('peripubic_fat', function ($appointment) {
                return $appointment->audit->peripubic_fat ?? '';
            })
            ->addColumn('circumplast', function ($appointment) {
                return $appointment->audit->circumplast ?? '';
            })
            ->addColumn('plastibell', function ($appointment) {
                return $appointment->audit->plastibell ?? '';
            })
            ->addColumn('resection', function ($appointment) {
                return $appointment->audit->resection ?? '';
            })
            ->addColumn('bleeding', function ($appointment) {
                return $appointment->audit->bleeding ?? '';
            })
            ->addColumn('retained_plastibell', function ($appointment) {
                return $appointment->audit->retained_plastibell ?? '';
            })
            ->addColumn('adhesion', function ($appointment) {
                return $appointment->audit->adhesion ?? '';
            })
            ->addColumn('division_of_adhesion', function ($appointment) {
                return $appointment->audit->division_of_adhesion ?? '';
            })
            ->addColumn('residual_skin', function ($appointment) {
                return $appointment->audit->residual_skin ?? '';
            })
            ->addColumn('revisions_follow_ups', function ($appointment) {
                return $appointment->audit->revisions_follow_ups ?? '';
            })
            ->addColumn('followup', function ($appointment) {
                return $appointment->audit->followup ?? '';
            })
            ->addColumn('dna', function ($appointment) {
                return $appointment->audit->dna ?? '';
            })
            ->addColumn('not_done', function ($appointment) {
                return $appointment->audit->not_done ?? '';
            })
            ->addColumn('consultation_only', function ($appointment) {
                return $appointment->audit->consultation_only ?? '';
            })
            ->addColumn('not_required', function ($appointment) {
                return $appointment->audit->not_required ?? '';
            })
            ->addColumn('hypopaedias', function ($appointment) {
                return $appointment->audit->hypopaedias ?? '';
            })
            ->addColumn('torsion', function ($appointment) {
                return $appointment->audit->torsion ?? '';
            })
            ->addColumn('fat', function ($appointment) {
                return $appointment->audit->fat ?? '';
            })
            ->addColumn('hydrocoele', function ($appointment) {
                return $appointment->audit->hydrocoele ?? '';
            })
            ->addColumn('comments', function ($appointment) {
                return $appointment->audit->comments ?? '';
            })
            ->addColumn('oral', function ($appointment) {
                return $appointment->audit->oral ?? '';
            })
            ->addColumn('topical', function ($appointment) {
                return $appointment->audit->topical ?? '';
            })
            ->addColumn('infection', function ($appointment) {
                return $appointment->audit->infection ?? '';
            })
            ->addColumn('curve', function ($appointment) {
                return $appointment->audit->curve ?? '';
            })
            ->addColumn('soft_adhesion', function ($appointment) {
                return $appointment->audit->soft_adhesion ?? '';
            })
            ->addColumn('chordee', function ($appointment) {
                return $appointment->audit->chordee ?? '';
            })
            ->addColumn('without_father', function ($appointment) {
                return $appointment->audit->without_father ?? '';
            })
            ->addColumn('frenuloplasty', function ($appointment) {
                return $appointment->audit->frenuloplasty ?? '';
            })
            ->addColumn('webbed_penis', function ($appointment) {
                return $appointment->audit->webbed_penis ?? '';
            })
            ->addColumn('redundant_inner_skin', function ($appointment) {
                return $appointment->audit->redundant_inner_skin ?? '';
            })
            ->addColumn('secondary_phimosis', function ($appointment) {
                return $appointment->audit->secondary_phimosis ?? '';
            })
            ->addColumn('actions', function ($appointment) {
                $html = '
                    <a href="' . route('patients.appointments.audit', ['appointment_id' => $appointment->id]) . '" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit">
                        Edit
                    </a>';

                return $html;
            })
            ->rawColumns(['actions'])
            ->filterColumn('id', function ($appointment, $keyword) {
                $appointment->whereHas('patient', function ($patient) use ($keyword) {
                    $patient
                        ->where('id', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->make();
    }

    public function audit($appointment_id = 0)
    {
        $title = 'Appointment Audit';
        $appointment = Appointment::findOrFail($appointment_id);
        $patient = $appointment->patient;
        $audit = $appointment->audit;

        if ($audit == null) {
            $audit = new Audit();
        }

        return view('patients.appointments.audit.edit', [
            'title' => $title,
            'patient' => $patient,
            'audit' => $audit,
            'appointment' => $appointment,
        ]);
    }

    public function auditSave(Request $request, $appointment_id = 0)
    {
        $appointment = Appointment::findOrFail($appointment_id);
        $patient = $appointment->patient;
        $audit = $appointment->audit;

        if ($audit == null) {
            $audit = new Audit();
        }

        $data = [
            "patient_id" => $patient->id,
            "appointment_id" => $appointment->id,
            "hypospediasis" => $request->hypospediasis ?? "",
            "any_other" => $request->any_other ?? "",
            "peripubic_fat" => $request->peripubic_fat ?? "",
            "circumplast" => $request->circumplast ?? "",
            "plastibell" => $request->plastibell ?? "",
            "resection" => $request->resection ?? "",
            "bleeding" => $request->bleeding ?? "",
            "retained_plastibell" => $request->retained_plastibell ?? "",
            "adhesion" => $request->adhesion ?? "",
            "division_of_adhesion" => $request->division_of_adhesion ?? "",
            "residual_skin" => $request->residual_skin ?? "",
            "revisions_follow_ups" => $request->revisions_follow_ups ?? "",
            "followup" => $request->followup ?? "",
            "dna" => $request->dna ?? "",
            "not_done" => $request->not_done ?? "",
            'consultation_only' => $request->consultation_only ?? "",
            'not_required' => $request->not_required ?? "",
            "hypopaedias" => $request->hypopaedias ?? "",
            "torsion" => $request->torsion ?? "",
            "fat" => $request->fat ?? "",
            "hydrocoele" => $request->hydrocoele ?? "",
            "comments" => $request->comments ?? "",
            "oral" => $request->oral ?? "",
            "topical" => $request->topical ?? "",
            "infection" => $request->infection ?? "",
            "curve" => $request->curve ?? "",
            "soft_adhesion" => $request->soft_adhesion ?? "",
            "chordee" => $request->chordee ?? "",
            "without_father" => $request->without_father ?? "",
            "frenuloplasty" => $request->frenuloplasty ?? "",
            "webbed_penis" => $request->webbed_penis ?? "",
            "redundant_inner_skin" => $request->redundant_inner_skin ?? "",
            "secondary_phimosis" => $request->secondary_phimosis ?? "",
        ];

        if ($audit->id > 0) {
            $audit->update($data);
        } else {
            $audit = Audit::create($data);
        }

        return redirect()->route('patients.appointments.audit', [
            'appointment_id' => $appointment->id
        ]);
    }
}
