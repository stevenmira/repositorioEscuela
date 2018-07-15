<?php

namespace Escuela\Http\Requests;

use Escuela\Http\Requests\Request;

class DetalleAcademicoFormRequest extends Request
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
             'id_trimestre'=>'required',
             'id_matricula'=>'required',
            'criterio'=>'required',
        ];
    }
}
