<?php

namespace Escuela\Http\Requests;

use Escuela\Http\Requests\Request;

class NotaCiclo1Trim1FormRequest extends Request
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
            'items1.*'=> 'Integer|Min:0|Max:10',
            'items2.*'=> 'Integer|Min:0|Max:10',
            'items3.*'=> 'Integer|Min:0|Max:10'

            
        ];
    }


}
