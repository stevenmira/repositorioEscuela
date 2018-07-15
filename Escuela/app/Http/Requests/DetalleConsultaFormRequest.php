<?php

namespace Escuela\Http\Requests;

use Escuela\Http\Requests\Request;

class DetalleConsultaFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
   
            'id_criterioconducta'=>'required',
              'id_aspectoconducta'=>'required'
        ];
    }
}
