<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
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
            return route('settings.medicines.edit', ['id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }
}
