<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Branch;
use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
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
            return route('patients.appointments.edit', ['appointment_id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getDeleteRouteAttribute()
    {
        try {
            return route('patients.appointments.delete', ['appointment_id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getReportsRouteAttribute()
    {
        try {
            return route('patients.appointments.reports', ['appointment_id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getDateFormattedAttribute()
    {
        try {
            return Carbon::parse($this->date)->englishDayOfWeek . ' ' . Carbon::parse($this->date)->toFormattedDateString();
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getStartTimeFormattedAttribute()
    {
        try {
            return Carbon::parse($this->start_time)->format('g:i A');
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getEndTimeFormattedAttribute()
    {
        try {
            return Carbon::parse($this->end_time)->format('g:i A');
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getStatusTextAttribute()
    {
        try {
            $status = Helper::getAppointmentStatus($this->status);
            return $status['name'];
        } catch (\Throwable $th) {
            return 'error';
        }
    }

    public function getStatusColorAttribute()
    {
        try {

            $status = Helper::getAppointmentStatus($this->status);

            return [
                'bg_color' => $status['bg_color'],
                'text_color' => $status['text_color']
            ];
        } catch (\Exception $e) {
            return [
                'bg_color' => '#EEEEEE',
                'text_color' => '#333333'
            ];
        }
    }

    public function audit()
    {
        return $this->hasone(Audit::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getFeesRemainingAttribute()
    {
        try {
            return number_format(($this->fees ?? 0) - ($this->fees_paid ?? 0), 2);
        } catch (\Exception $e) {
            return '0.00';
        }
    }

    public function getAvailableAttribute()
    {
        return Carbon::now()->diffInMilliseconds($this->date . 'T' . $this->end_time, false) > 0;
    }

    public function getOptionsHtmlAttribute()
    {
        return view('patients.appointments.options', ['appointment' => $this])->render();
    }

    public function additional_reports()
    {
        return $this->hasMany(AdditionalReport::class);
    }
}
