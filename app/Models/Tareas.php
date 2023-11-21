<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tareas extends Model
{
    use HasFactory;

        
    public function Labor()
    {
        return $this->belongsTo(Labor::class,'Labor_id');
    }

    public function Lote()
    {
        return $this->belongsTo(Lote::class,'Lote_id');
    }
}
