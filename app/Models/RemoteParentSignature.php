<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemoteParentSignature extends Model
{
    use HasFactory;
    public function getLinkAttribute()
    {
        try {
            return route('remote-parent-signature.link', ['unique_key' => $this->unique_key]);
        } catch (\Exception $e) {
            return '#';
        }
    }
    public function getEditRouteAttribute()
    {
        try {
            return route('remote-parent-signature.edit', ['id' => $this->id]);
        } catch (\Exception $e) {
            return '#';
        }
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
