<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Template extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $table = 'templates';

    public function getEditRouteAttribute()
    {
        try {
            return route('settings.templates.edit', ['id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function getPreviewRouteAttribute()
    {
        try {
            return route('settings.templates.preview', ['id' => $this->id, 'patient_id' => 1]);
        } catch (\Exception $e) {
            return '#';
        }
    }

    public function scopeIsReport($query)
    {
        return $query->where('is_report', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function getPdfStreamAttribute()
    {
        $root_html = $this->html;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($root_html);
        return $pdf->stream();
    }
}
