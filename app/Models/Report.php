<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function getFileUrlAttribute()
    {
        $report_path = config('constants.storage_paths.reports', '');
        $report_path = str_replace('{patient_id}', $this->patient_id, $report_path);
        $report_path = str_replace('{filename}', $this->filename, $report_path);

        return url($report_path);
    }
}
