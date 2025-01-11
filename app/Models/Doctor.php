<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
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
            return route('settings.doctors.edit', ['id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getAddAppointmentRouteAttribute()
    {
        try {
            return route('patients.appointments.add', ['doctor_id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
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

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
