<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientInquiry extends Model
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
            return route('patient-register.edit', ['id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getLinkAttribute()
    {
        try {
            return route('patient-register.link', ['unique_key' => $this->unique_key]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
