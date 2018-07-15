<?php

namespace Escuela;

use Illuminate\Database\Eloquent\Model;

class Anio extends Model
{
    protected $table = 'anios';

    protected $primaryKey = 'idanio';

    public $timestamps = false;

    protected $fillable = [
        'valor'
        
    ];

    protected $guarded = [
    ];
}
