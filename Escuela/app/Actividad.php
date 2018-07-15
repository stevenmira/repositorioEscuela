<?php

namespace Escuela;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    //
    protected $table = 'actividad';

    protected $primaryKey = 'id_actividad';

    public $timestamps = false;

    protected $fillable = [
        'id_trimestre',
        'nombre',
    	'porcentaje'
    ];

    public static function actividades($id){
        return Actividad::where('id_trimestre','=',$id)->get();
    }

    protected $guarded = [
    ];
}
