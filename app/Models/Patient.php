<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function getEditRouteAttribute()
    {
        try {
            return route('patients.edit', ['id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getApproveRouteAttribute()
    {
        try {
            return route('unapproved-patients.approve');
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getAddAppointmentRouteAttribute()
    {
        try {
            return route('patients.appointments.add', ['patient_id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getLatestAppointmentAttribute()
    {
        try {
            $appoinment = $this->appointments()->latest()->first();
            return $appoinment;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getLatestAppointmentRouteAttribute()
    {
        try {
            $appoinment = $this->appointments()->latest()->first();
            if ($appoinment) {
                return route('patients.appointments.reports', ['id' => $this->id, 'appointment_id' => $appoinment->id]);
            }
            return '#';
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getLatestAppointmentEditRouteAttribute()
    {
        try {
            $appoinment = $this->appointments()->latest()->first();
            if ($appoinment) {
                return route('patients.appointments.edit', ['id' => $this->id, 'appointment_id' => $appoinment->id]);
            }
            return '#';
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getDateOfBirthFormattedAttribute()
    {
        try {
            return Carbon::parse($this->date_of_birth)->toFormattedDateString();
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function inquiry()
    {
        return $this->hasOne(PatientInquiry::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function audit()
    {
        return $this->hasOne(Audit::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function additional_reports()
    {
        return $this->hasMany(AdditionalReport::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function getAddressAttribute()
    {
        try {
            $address = [];
            !in_array($this->house_no, [null, '']) ? $address[] = $this->house_no : null;
            !in_array($this->street, [null, '']) ? $address[] = $this->street : null;
            !in_array($this->city, [null, '']) ? $address[] = $this->city : null;
            !in_array($this->post_code, [null, '']) ? $address[] = $this->post_code : null;
            return implode(', ', $address);
        } catch (\Exception $e) {
            return '';
        }
    }

    public function getSimilarPatientsAttribute()
    {
        $patients = Patient::where('id', '!=', $this->id)
            ->where(function ($query) {
                if (!in_array($this->name, [null, ''])) {
                    $query->orWhere('name', $this->name);
                }

                if (!in_array($this->cell_number, [null, ''])) {
                    $query->orWhere('cell_number', $this->cell_number);
                }

                if (!in_array($this->phone, [null, ''])) {
                    $query->orWhere('phone', $this->phone);
                }

                if (!in_array($this->email, [null, ''])) {
                    $query->orWhere('email', $this->email);
                }
            });

        return $patients;
    }
}
