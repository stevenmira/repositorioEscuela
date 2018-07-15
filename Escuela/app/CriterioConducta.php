<?php

namespace Escuela;

use Illuminate\Database\Eloquent\Model;

class CriterioConducta extends Model
{
    protected $table = 'criterio_conducta';

    protected $primaryKey = 'id_criterioconducta';

    public $timestamps = false;

    protected $fillable = [
    	'nombre'
    ];

    protected $guarded = [
    ];
}
