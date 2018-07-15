<?php

namespace Escuela\Http\Requests;

use Escuela\Http\Requests\Request;

class Matricula2FormRequest extends Request
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

            //Matricula
            'fechamatricula'=>'required',
            'fechareal'=>'required',
            'fotografia'=>'mimes:jpeg,bmp,png,jpg',
            'cePrevio'=>'required|max:100',

            //PartidaNacimiento
            'partida'=>'required|min:1|max:9|',
            'folio'=>'required|min:1|max:8|',
            'libro'=>'required|min:1|max:8|',

            //Estudiante
            'nie'=>'required|min:8|max:8',
            'nombre'=>'required|max:50',
            'apellido'=>'required|max:50',
            'fechadenacimiento'=>'required',
            'sexo'=>'required',
            'domicilio'=>'required|max:1020',
            'enfermedad'=>'max:4096',
            'zonahabitacion'=>'required',
            'autorizavacuna'=>'required',
            'discapacidad'=>'required',


            'vivecon'=>'max:200',
            'telefonoce'=>'min:8|max:8',
            'hizokinder'=>'required',
            #'fechaentrega'=>'required',

            //Madre
            'nombre2'=>'max:50',
            'apellido2'=>'max:50',
            'ocupacion'=>'max:50',
            'lugardetrabajo'=>'max:190',
            'dui'=>'min:9|max:9',            //Unique
            'telefono'=>'min:8|max:8',

            //Padre
            'nombre3'=>'max:50',
            'apellido3'=>'max:50',
            'ocupacion3'=>'max:50',
            'lugardetrabajo3'=>'max:190',
            'dui3'=>'min:9|max:9',            //Unique
            'telefono3'=>'min:8|max:8',

            //Contacto de Emergencia
            'nombre4'=>'required|max:50',
            'apellido4'=>'required|max:50',
            'telefono4'=>'required|min:8|max:8',



        ];
    }

    public function messages()
    {
        return [

            //Matricula
            'cePrevio.max' =>'El campo  -- Centro escolar del que precede -- debe contener 100 caracteres como máximo.',
            'cePrevio.required' =>'El campo -- Centro escolar del que precede -- es obligatorio.',

            'fechamatricula.required' =>'El campo -- Año a Matricular -- es obligatorio.',
            'fechareal.required' =>'El campo -- Fecha de Matricula -- es obligatorio.',

            //Partida de Nacimiento
            'partida.min' =>'El campo -- No. de Partida -- debe contener 1 dígito como mínimo.',
            'partida.max' =>'El campo -- No. de Partida -- debe contener 9 dígitos como máximo.',
            'partida.required' =>'El campo -- No. Partida de nacimiento -- es obligatorio.',

            'folio.min' =>'El campo -- No. de Folio -- debe contener 1 dígito como mínimo.',
            'folio.max' =>'El campo -- No. de Folio -- debe contener 8 dígitos como máximo.',
            'folio.required' =>'El campo -- No. de Folio de partida de nacimiento -- es obligatorio.',

            'libro.min' =>'El campo -- No. de Libro -- debe contener 1 dígito como mínimo.',
            'libro.max' =>'El campo -- No. de Libro -- debe contener 8 dígitos como máximo.',
            'libro.required' =>'El campo -- No. de Libro de partida de nacimiento -- es obligatorio.',

            // Estudiante
            'nie.min' =>'El campo -- NIE -- debe tener exactamente 8 dígitos, no menos.',
            'nie.max' =>'El campo -- NIE -- debe tener exactamente 8 dígitos, no más.',
            'nie.required' =>'El campo -- NIE -- es obligatorio.',
            

            'nombre.max' =>'El campo  -- Nombre del Estudiante -- debe contener 50 caracteres como máximo.',
            'nombre.required' =>'El campo -- Nombre del Estudiante -- es obligatorio.',

            'apellido.max' =>'El campo  -- Apellido del Estudiante -- debe contener 50 caracteres como máximo.',
            'apellido.required' =>'El campo -- Apellido del Estudiante -- es obligatorio.',

            'fechadenacimiento.required' =>'El campo -- Fecha de Nacimiento -- es obligatorio.',

            'sexo.required' =>'El campo -- Sexo -- es obligatorio.',

            'domicilio.max' =>'El campo -- Domicilio -- debe contener 1020 caracteres como máximo.',
            'domicilio.required' =>'El campo -- Domicilio -- es obligatorio.',

            'enfermedad.max' =>'El campo  -- Enfermedad -- debe contener 4096 caracteres como máximo.',

            'discapacidad.required' =>'El campo -- Tiene discapacidad -- es obligatorio.',

            'autorizavacuna.required' =>'El campo -- Autorización de vacuna -- es obligatorio.',

            'zonahabitacion.required' =>'El campo -- Área geográfica de residencia -- es obligatorio.',

            'vivecon.max' =>'El campo -- Vive Con -- debe contener 200 caracteres como máximo.',

            'hizokinder.required' =>'El campo -- ¿Hizo Kinder? -- es obligatorio.',
            
            'telefonoce.min' =>'El campo -- Teléfono del C.E -- debe tener exactamente 8 dígitos, no menos.',
            'telefonoce.max' =>'El campo -- Teléfono del C.E -- debe tener exactamente 8 dígitos, no más.', 


            //Madre

            'nombre2.max' =>'El campo  -- Nombres de la Madre -- debe contener 50 caracteres como máximo.',

            'apellido2.max' =>'El campo  -- Apellidos de la Madre -- debe contener 50 caracteres como máximo.',

            'ocupacion.max' =>'El campo  -- Ocupación de la Madre -- debe contener 50 caracteres como máximo.',

            'lugardetrabajo.max' =>'El campo -- lugar de Trabajo de la Madre -- debe contener 200 caracteres como máximo.',

            'dui.min' =>'El campo -- DUI de la Madre-- debe tener exactamente 9 dígitos, no menos.',
            'dui.max' =>'El campo -- DUI de la Madre -- debe tener exactamente 9 dígitos, no más.',

            'telefono.min' =>'El campo -- Teléfono de contacto de la Madre -- debe tener exactamente 8 dígitos, no menos.',
            'telefono.max' =>'El campo -- Teléfono de contacto de la Madre -- debe tener exactamente 8 dígitos, no más.', 


            //Padre

            'nombre3.max' =>'El campo  -- Nombres del Padre -- debe contener 50 caracteres como máximo.',

            'apellido3.max' =>'El campo  -- Apellidos del Padre -- debe contener 50 caracteres como máximo.',

            'ocupacion3.max' =>'El campo  -- Ocupación del Padre -- debe contener 50 caracteres como máximo.',

            'lugardetrabajo3.max' =>'El campo -- lugar de Trabajo del Padre -- debe contener 200 caracteres como máximo.',

            'dui3.min' =>'El campo -- DUI del Padre -- debe tener exactamente 9 dígitos, no menos.',
            'dui3.max' =>'El campo -- DUI del Padre -- debe tener exactamente 9 dígitos, no más.',

            'telefono3.min' =>'El campo -- Teléfono de contacto del Padre -- debe tener exactamente 8 dígitos, no menos.',
            'telefono3.max' =>'El campo -- Teléfono de contacto del Padre -- debe tener exactamente 8 dígitos, no más.',


            //Contacto de Emergencia

            'nombre4.max' =>'El campo  -- Nombres del Contacto de Emergencia -- debe contener 50 caracteres como máximo.',

            'apellido4.max' =>'El campo  -- Apellidos del Contacto de Emergencia -- debe contener 50 caracteres como máximo.',

            'telefono4.min' =>'El campo -- Teléfono de Contacto de Emergencia -- debe tener exactamente 8 dígitos, no menos.',
            'telefono4.max' =>'El campo -- Teléfono de Contacto de Emergencia -- debe tener exactamente 8 dígitos, no más.'    
            
        ];
    }

}
