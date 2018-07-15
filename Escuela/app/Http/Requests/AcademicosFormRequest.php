<?php

namespace Escuela\Http\Requests;

use Escuela\Http\Requests\Request;

class AcademicosAcademicoFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'numero'=> 'required|Numeric',
            'nombre_academico'=>'required',
        ];
    }
}
