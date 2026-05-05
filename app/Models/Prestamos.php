<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamos extends Model
{
    use HasFactory;

    public function Herramienta()
    {
        return $this->belongsTo(Herramienta::class,'Herramienta_id');
    }
}
