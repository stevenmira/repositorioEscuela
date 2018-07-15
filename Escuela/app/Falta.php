<?php

namespace Escuela;

use Illuminate\Database\Eloquent\Model;

class Falta extends Model
{
    protected $table = 'falta';
    protected $primarykey = 'id_falta';
   
    public $timestamps = false;
   
    protected $fillable= [
        'fecha_falta',
    	'detalle_falta',
    	'id_matricula',
    	'permiso'
    ];
   
    protected $guarded = [
    ];
}
