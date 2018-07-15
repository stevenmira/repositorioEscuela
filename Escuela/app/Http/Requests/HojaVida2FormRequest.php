<?php

namespace Escuela\Http\Requests;

use Escuela\Http\Requests\Request;

class HojaVida2FormRequest extends Request
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
            //Hoja de Vida
            'cenombrado'=>'max:250',
            'codigoinstitucion'=>'min:5|max:5',
            'aniosservicio'=>'max:2',
            'cargo'=>'max:99',
            'funciones'=>'max:1020',
            'nacimientoextranjero'=>'max:45',
            #'extendido'

            //Maestro

            //Requeridos
            'nombre'=>'required|max:50',
            'apellido'=>'required|max:50',
            'fechanacimiento'=>'required',
            'sexo'=>'required',
            'direccion'=>'required|max:1020',
            'mdui'=>'required|min:9|max:9',
            'nit'=>'required|min:14|max:14',
            'nip'=>'required|min:7|max:7',

            //opcionales
            'afp'=>'min:0|max:15',
            'inpep'=>'min:0|max:8',
            'fotografia'=>'mimes:jpeg,bmp,png,jpg',
            'telefono'=>'min:8|max:8'

        ];
    }

    public function messages()
    {
        return [

             //*******************************   Campos No Obligatorio *******************************//
            //*********************************     Hoja de Vida       ******************************//

            'cenombrado.max' =>'El campo  -- Centro Escolar donde fue nombrado -- debe contener 250 caracteres como máximo.',

            'codigoinstitucion.min' =>'El campo -- Código -- debe tener exactamente 5 dígitos, no menos.',
            'codigoinstitucion.max' =>'El campo -- Código -- debe tener exactamente 5 dígitos, no más.',

            'aniosservicio.max' =>'El campo -- Años de servicio -- debe contener 2 dígitos como máximo.',

            'cargo.max' =>'El campo -- Cargo que desempeña -- debe contener 99 caracteres como máximo.',

            'funciones.max' =>'El campo -- Funciones a realizar -- debe contener 1020 caracteres como máximo.',

            'nacimientoextranjero.max' =>'El campo -- Otro -- debe contener 45 caracteres como máximo.',



            //********************************* Campos Obligatorios *********************************//
           //*********************************       Maestro       *********************************//

            'nombre.max' =>'El campo  -- Nombre -- debe contener 50 caracteres como máximo.',
            'nombre.required' =>'El campo -- Nombre -- es obligatorio.',

            'apellido.max' =>'El campo  -- Apellido -- debe contener 50 caracteres como máximo.',
            'apellido.required' =>'El campo -- Apellido -- es obligatorio.',

            'fechanacimiento.required' =>'El campo -- Fecha de Nacimiento -- es obligatorio.',
            'sexo.required' =>'El campo -- Sexo -- es obligatorio.',

            'direccion.max' =>'El campo -- Dirección -- debe contener 1020 caracteres como máximo.',
            'direccion.required' =>'El campo -- Dirección -- es obligatorio.',

            'mdui.min' =>'El campo -- DUI -- debe tener exactamente 9 dígitos, no menos.',
            'mdui.max' =>'El campo -- DUI -- debe tener exactamente 9 dígitos, no más.',
            'mdui.required' =>'El campo -- DUI -- es obligatorio.',
            
            'nit.min' =>'El campo -- NIT -- debe tener exactamente 14 dígitos, no menos.',
            'nit.max' =>'El campo -- NIT -- debe tener exactamente 14 dígitos, no más.',
            'nit.required' =>'El campo -- NIT -- es obligatorio.',

            'nip.min' =>'El campo -- NIP -- debe tener exactamente 7 dígitos, no menos.',
            'nip.max' =>'El campo -- NIP -- debe tener exactamente 7 dígitos, no más.',
            'nip.required' =>'El campo -- NIP -- es obligatorio.',

            'afp.max' =>'El campo -- AFP -- debe contener 15 dígitos como máximo.',

            'inpep.max' =>'El campo -- INPEP -- debe contener 8 dígitos como máximo.',

            'telefono.min' =>'El campo -- Teléfono -- debe tener exactamente 8 dígitos, no menos.',
            'telefono.max' =>'El campo -- Teléfono -- debe tener exactamente 8 dígitos, no más.'            
            
            
        ];
    }
}
