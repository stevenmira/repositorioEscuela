<?php

namespace Escuela;

use Illuminate\Database\Eloquent\Model;

class AspectoConducta extends Model
{
    protected $table = 'aspecto_conducta';

    protected $primaryKey = 'id_aspectoconducta';

    public $timestamps = false;

    protected $fillable = [
    	'nombre'
    ];

    protected $guarded = [
    ];
}
