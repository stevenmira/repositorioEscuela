<?php

namespace Escuela;

use Illuminate\Database\Eloquent\Model;

class DetalleNota extends Model
{
    protected $table = 'detalle_nota';

    protected $primaryKey = 'id_detallenota';

    public $timestamps = false;

    protected $fillable = [
    	'id_detalleevaluacion',
    	'id_matricula',
    	'nota'
    ];

    protected $guarded = [
    ];
}