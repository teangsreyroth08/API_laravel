<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionDetail extends Model
{
    protected $fillable = ['prescription_id', 'medicine_id', 'dosage', 'frequency', 'duration', 'notes'];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Inventory::class)->where('type_id',1);
    }

}
