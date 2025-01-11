<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Feedback;
use App\Traits\AppointmentTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    use AppointmentTrait;

    public function index(Request $request)
    {
        $title = 'Feedback';

        if ($request->ajax()) {

            $request->request->add([
                'start_date' => $request->has('start_date') ? Carbon::parse($request->start_date)->toDateString() : null,
                'end_date' => $request->has('end_date') ? Carbon::parse($request->end_date)->toDateString() : null,
            ]);

            $appointments = $this->fetchAppointments($request);
            $appointments->getQuery()->orders = null;

            // if ($request->has('date') && $request->date != '') {
            //     $appointments = $appointments->where(function ($query) use ($request) {
            //         $query->where('appointments.date', $request->date);
            //     });
            // }

            // $appointments = $appointments->whereHas('feedback', function ($feedback) {
            //     $feedback
            //         ->whereIn('environment_hygiene_clinic',             ["Very Dissatisfied", "Dissatisfied"])
            //         ->orWhereIn('attitude_staff',                       ["Very Dissatisfied", "Dissatisfied"])
            //         ->orWhereIn('pre_op_counselling_consultation',      ["Very Dissatisfied", "Dissatisfied"])
            //         ->orWhereIn('doctors_conduct_expertise',            ["Very Dissatisfied", "Dissatisfied"])
            //         ->orWhereIn('post_operative_care_advice',           ["Very Dissatisfied", "Dissatisfied"])
            //         ->orWhereIn('outcome_result_procedure',             ["Very Dissatisfied", "Dissatisfied"])
            //         ->orWhereIn('efficacy_post_operative_follow',       ["Very Dissatisfied", "Dissatisfied"]);
            // });

            $appointments = $appointments
                ->has('patient')
                ->with('feedback');
                // ->orderBy('date', 'desc')
                // ->orderBy('start_time', 'desc');

            return app('datatables')->of($appointments)
                ->addColumn('id', function ($appointment) {
                    return $appointment->patient->id;
                })
                ->addColumn('name', function ($appointment) {
                    return "<a href=" . $appointment->patient->edit_route . " target='_blank'>" . $appointment->patient->name . "</a>";
                })
                ->addColumn('remarks', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->remarks : null;
                    return $value;
                })
                ->addColumn('environment_hygiene_clinic', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->environment_hygiene_clinic : null;
                    if (in_array($value, ['Dissatisfied', 'Very Dissatisfied'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('attitude_staff', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->attitude_staff : null;
                    if (in_array($value, ['Dissatisfied', 'Very Dissatisfied'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('pre_op_counselling_consultation', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->pre_op_counselling_consultation : null;
                    if (in_array($value, ['Dissatisfied', 'Very Dissatisfied'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('doctors_conduct_expertise', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->doctors_conduct_expertise : null;
                    if (in_array($value, ['Dissatisfied', 'Very Dissatisfied'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('post_operative_care_advice', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->post_operative_care_advice : null;
                    if (in_array($value, ['Dissatisfied', 'Very Dissatisfied'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('outcome_result_procedure', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->outcome_result_procedure : null;
                    if (in_array($value, ['Dissatisfied', 'Very Dissatisfied'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('efficacy_post_operative_follow', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->efficacy_post_operative_follow : null;
                    if (in_array($value, ['Dissatisfied', 'Very Dissatisfied'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('recommend', function ($appointment) {
                    $value = $appointment->feedback ? $appointment->feedback->recommend : null;
                    if (in_array($value, ['No'])) {
                        return "<span class='badge badge-pill badge-danger'>" . $value . "</span>";
                    }
                    return "<span class='badge badge-pill badge-success'>" . $value . "</span>";
                })
                ->addColumn('actions', function ($appointment) {
                    $html = '
                        <a href="' . route('patients.appointments.feedback', ['appointment_id' => $appointment->id]) . '" class="btn btn-sm btn-primary mr-2" title="Feedback">
                            Feedback
                        </a>';

                    return $html;
                })
                ->rawColumns([
                    'name',
                    'actions',
                    'environment_hygiene_clinic',
                    'attitude_staff',
                    'pre_op_counselling_consultation',
                    'doctors_conduct_expertise',
                    'post_operative_care_advice',
                    'outcome_result_procedure',
                    'efficacy_post_operative_follow',
                    'recommend'
                ])
                ->orderColumn('remarks', function ($query, $order) {
                    $query->join('feedback', 'appointments.id', 'feedback.appointment_id');
                    $query->orderBy('remarks', $order);
                })
                ->filterColumn('id', function ($query, $keyword) {
                    $query->whereHas('patient', function ($patient) use ($keyword) {
                        $patient
                            ->where('id', 'LIKE', '%' . $keyword . '%');
                    });
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereHas('patient', function ($patient) use ($keyword) {
                        $patient
                            ->where('name', 'LIKE', '%' . $keyword . '%');
                    });
                })
                // ->filterColumn('environment_hygiene_clinic', function ($query, $keyword) {
                //     $query->whereHas('feedback', function ($feedback) use ($keyword) {
                //         $feedback
                //             ->where('environment_hygiene_clinic', 'LIKE', '%' . $keyword . '%');
                //     });
                // })
                // ->filterColumn('attitude_staff', function ($query, $keyword) {
                //     $query->whereHas('feedback', function ($feedback) use ($keyword) {
                //         $feedback
                //             ->where('attitude_staff', 'LIKE', '%' . $keyword . '%');
                //     });
                // })
                // ->filterColumn('pre_op_counselling_consultation', function ($query, $keyword) {
                //     $query->whereHas('feedback', function ($feedback) use ($keyword) {
                //         $feedback
                //             ->where('pre_op_counselling_consultation', 'LIKE', '%' . $keyword . '%');
                //     });
                // })
                // ->filterColumn('doctors_conduct_expertise', function ($query, $keyword) {
                //     $query->whereHas('feedback', function ($feedback) use ($keyword) {
                //         $feedback
                //             ->where('doctors_conduct_expertise', 'LIKE', '%' . $keyword . '%');
                //     });
                // })
                // ->filterColumn('post_operative_care_advice', function ($query, $keyword) {
                //     $query->whereHas('feedback', function ($feedback) use ($keyword) {
                //         $feedback
                //             ->where('post_operative_care_advice', 'LIKE', '%' . $keyword . '%');
                //     });
                // })
                // ->filterColumn('outcome_result_procedure', function ($query, $keyword) {
                //     $query->whereHas('feedback', function ($feedback) use ($keyword) {
                //         $feedback
                //             ->where('outcome_result_procedure', 'LIKE', '%' . $keyword . '%');
                //     });
                // })
                // ->filterColumn('efficacy_post_operative_follow', function ($query, $keyword) {
                //     $query->whereHas('feedback', function ($feedback) use ($keyword) {
                //         $feedback
                //             ->where('efficacy_post_operative_follow', 'LIKE', '%' . $keyword . '%');
                //     });
                // })
                ->filterColumn('actions', function ($appointment) {
                    $html = '
                        <a href="' . route('patients.appointments.feedback', ['appointment_id' => $appointment->id]) . '" class="btn btn-sm btn-primary mr-2" title="Feedback">
                            Feedback
                        </a>';

                    return $html;
                })
                ->make();
        }

        $data = [];
        $data['environment_hygiene_clinic'] = Feedback::select(["environment_hygiene_clinic", DB::raw('COUNT(1) AS count')])->groupBy("environment_hygiene_clinic")->get();
        $data['attitude_staff'] = Feedback::select(["attitude_staff", DB::raw('COUNT(1) AS count')])->groupBy("attitude_staff")->get();
        $data['pre_op_counselling_consultation'] = Feedback::select(["pre_op_counselling_consultation", DB::raw('COUNT(1) AS count')])->groupBy("pre_op_counselling_consultation")->get();
        $data['doctors_conduct_expertise'] = Feedback::select(["doctors_conduct_expertise", DB::raw('COUNT(1) AS count')])->groupBy("doctors_conduct_expertise")->get();
        $data['post_operative_care_advice'] = Feedback::select(["post_operative_care_advice", DB::raw('COUNT(1) AS count')])->groupBy("post_operative_care_advice")->get();
        $data['outcome_result_procedure'] = Feedback::select(["outcome_result_procedure", DB::raw('COUNT(1) AS count')])->groupBy("outcome_result_procedure")->get();
        $data['efficacy_post_operative_follow'] = Feedback::select(["efficacy_post_operative_follow", DB::raw('COUNT(1) AS count')])->groupBy("efficacy_post_operative_follow")->get();
        $data['recommend'] = Feedback::select(["recommend", DB::raw('COUNT(1) AS count')])->groupBy("recommend")->get();

        $branches = Helper::getCompanyBranchesForSelect(1);

        return view('patients.appointments.feedback.index', [
            'title' => $title,
            'branches' => $branches,
            'data' => $data,
        ]);
    }

    public function feedbackSummary(Request $request)
    {

        $names = [
            "environment_hygiene_clinic" => "Environment / Hygiene of the clinic",
            "attitude_staff" => "Attitude of the staff",
            "pre_op_counselling_consultation" => "Pre-op Counselling and consultation",
            "doctors_conduct_expertise" => "Doctor’s conduct and expertise",
            "post_operative_care_advice" => "Post-operative after care advice",
            "outcome_result_procedure" => "Outcome / result of the procedure",
            "efficacy_post_operative_follow" => "Efficacy of the post-operative follow up",
            "recommend" => "Would you recommend this service to your family and friends?",
        ];

        $request->request->add([
            'start_date' => $request->has('start_date') ? Carbon::parse($request->start_date)->toDateString() : null,
            'end_date' => $request->has('end_date') ? Carbon::parse($request->end_date)->toDateString() : null,
        ]);

        $data = [];

        $data['environment_hygiene_clinic'] = $this->fetchAppointments($request);
        $data['environment_hygiene_clinic']->getQuery()->orders = null;
        $data['environment_hygiene_clinic'] = $data['environment_hygiene_clinic']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['attitude_staff'] = $this->fetchAppointments($request);
        $data['attitude_staff']->getQuery()->orders = null;
        $data['attitude_staff'] = $data['attitude_staff']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['pre_op_counselling_consultation'] = $this->fetchAppointments($request);
        $data['pre_op_counselling_consultation']->getQuery()->orders = null;
        $data['pre_op_counselling_consultation'] = $data['pre_op_counselling_consultation']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['doctors_conduct_expertise'] = $this->fetchAppointments($request);
        $data['doctors_conduct_expertise']->getQuery()->orders = null;
        $data['doctors_conduct_expertise'] = $data['doctors_conduct_expertise']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['post_operative_care_advice'] = $this->fetchAppointments($request);
        $data['post_operative_care_advice']->getQuery()->orders = null;
        $data['post_operative_care_advice'] = $data['post_operative_care_advice']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['outcome_result_procedure'] = $this->fetchAppointments($request);
        $data['outcome_result_procedure']->getQuery()->orders = null;
        $data['outcome_result_procedure'] = $data['outcome_result_procedure']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['efficacy_post_operative_follow'] = $this->fetchAppointments($request);
        $data['efficacy_post_operative_follow']->getQuery()->orders = null;
        $data['efficacy_post_operative_follow'] = $data['efficacy_post_operative_follow']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['recommend'] = $this->fetchAppointments($request);
        $data['recommend']->getQuery()->orders = null;
        $data['recommend'] = $data['recommend']
            ->join('feedback', 'appointments.id', 'feedback.appointment_id')
            ->has('patient');

        $data['environment_hygiene_clinic'] = $data['environment_hygiene_clinic']->select(["environment_hygiene_clinic", DB::raw('COUNT(1) AS count')])
            ->groupBy("environment_hygiene_clinic")
            ->get()
            ->groupBy("environment_hygiene_clinic")
            ->transform(function ($d) {
                return $d[0]['count'];
            });
        $data['attitude_staff'] = $data['attitude_staff']->select(["attitude_staff", DB::raw('COUNT(1) AS count')])
            ->groupBy("attitude_staff")
            ->get()
            ->groupBy("attitude_staff")
            ->transform(function ($d) {
                return $d[0]['count'];
            });
        $data['pre_op_counselling_consultation'] = $data['pre_op_counselling_consultation']->select(["pre_op_counselling_consultation", DB::raw('COUNT(1) AS count')])
            ->groupBy("pre_op_counselling_consultation")
            ->get()
            ->groupBy("pre_op_counselling_consultation")
            ->transform(function ($d) {
                return $d[0]['count'];
            });
        $data['doctors_conduct_expertise'] = $data['doctors_conduct_expertise']->select(["doctors_conduct_expertise", DB::raw('COUNT(1) AS count')])
            ->groupBy("doctors_conduct_expertise")
            ->get()
            ->groupBy("doctors_conduct_expertise")
            ->transform(function ($d) {
                return $d[0]['count'];
            });
        $data['post_operative_care_advice'] = $data['post_operative_care_advice']->select(["post_operative_care_advice", DB::raw('COUNT(1) AS count')])
            ->groupBy("post_operative_care_advice")
            ->get()
            ->groupBy("post_operative_care_advice")
            ->transform(function ($d) {
                return $d[0]['count'];
            });
        $data['outcome_result_procedure'] = $data['outcome_result_procedure']->select(["outcome_result_procedure", DB::raw('COUNT(1) AS count')])
            ->groupBy("outcome_result_procedure")
            ->get()
            ->groupBy("outcome_result_procedure")
            ->transform(function ($d) {
                return $d[0]['count'];
            });
        $data['efficacy_post_operative_follow'] = $data['efficacy_post_operative_follow']->select(["efficacy_post_operative_follow", DB::raw('COUNT(1) AS count')])
            ->groupBy("efficacy_post_operative_follow")
            ->get()
            ->groupBy("efficacy_post_operative_follow")
            ->transform(function ($d) {
                return $d[0]['count'];
            });
        $data['recommend'] = $data['recommend']->select(["recommend", DB::raw('COUNT(1) AS count')])
            ->groupBy("recommend")
            ->get()
            ->groupBy("recommend")
            ->transform(function ($d) {
                return $d[0]['count'];
            });

        $dataa = [];
        foreach (["environment_hygiene_clinic", "attitude_staff", "pre_op_counselling_consultation", "doctors_conduct_expertise", "post_operative_care_advice", "outcome_result_procedure", "efficacy_post_operative_follow", "recommend"] as $value) {
            $datab = (object)[];
            $datab->name = $names[$value];
            foreach (["Very Satisfied", "Satisfied", "Dissatisfied", "Very Dissatisfied", "Yes", "No"] as $value1) {
                $value2 = $value1;
                if ($value == "recommend" && $value1 == "Yes") {
                    $value2 = "Very Satisfied";
                }
                if ($value == "recommend" && $value1 == "No") {
                    $value2 = "Satisfied";
                }
                $datab->{$value2} = ($data[$value][$value1] ?? '-') . ($value2 == $value1 ? "" : (" " . $value1));
            }
            $dataa[] = $datab;
        }

        return app('datatables')->of($dataa)
            ->make();
    }

    public function feedback(Request $request, $appointment_id = 0)
    {
        $title = 'Feedback';
        $appointment = Appointment::findOrFail($appointment_id);
        $patient = $appointment->patient;
        $feedback = $appointment->feedback;

        if ($feedback == null) {
            $feedback = new Feedback();
        }

        return view('patients.appointments.feedback.edit', [
            'title' => $title,
            'patient' => $patient,
            'feedback' => $feedback,
            'appointment' => $appointment,
        ]);
    }

    public function feedbackSave(Request $request)
    {
        $title = 'Feedback';
        $appointment = Appointment::findOrFail($request->appointment_id);
        $patient = $appointment->patient;
        $feedback = $appointment->feedback;

        if ($feedback == null) {
            $feedback = new Feedback();
        }

        $data = [
            "patient_id" => $patient->id,
            "appointment_id" => $appointment->id,
            "environment_hygiene_clinic" => $request->input('feedback.environment_hygiene_clinic'),
            "attitude_staff" => $request->input('feedback.attitude_staff'),
            "pre_op_counselling_consultation" => $request->input('feedback.pre_op_counselling_consultation'),
            "doctors_conduct_expertise" => $request->input('feedback.doctors_conduct_expertise'),
            "post_operative_care_advice" => $request->input('feedback.post_operative_care_advice'),
            "outcome_result_procedure" => $request->input('feedback.outcome_result_procedure'),
            "efficacy_post_operative_follow" => $request->input('feedback.efficacy_post_operative_follow'),
            "recommend" => $request->input('feedback.recommend'),
            "remarks" => $request->input('remarks'),
        ];

        if ($feedback->id > 0) {
            $feedback->update($data);
        } else {
            $feedback = Feedback::create($data);
        }

        return redirect()->route('patients.appointments.feedback', [
            'appointment_id' => $appointment->id
        ])->with('success','Feedback Has Been Submitted Successfully...!!!!');


    }
}
