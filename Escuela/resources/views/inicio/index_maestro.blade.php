@extends ('layouts.maestro')
	@section('contentMaestro')
	<style>

      .bgimg-1{
        background-image:url({{asset('images/logo.png')}});
      }

      .bgimg-1{
        width: 100%;
        height: 400px;
        min-height: 100%;
        position: relative;
        opacity: 0.90;
        background-attachment: relative;
        background-position: center;
        background-repeat: no-repeat;
        background-size: 300px 300px;
        background-color: #282E34;
      }
      p{
        letter-spacing: 3px;
        text-transform: uppercase;
        font: 12px "Lato", sans-serif;
        color: #fff;
      }

      /* Turn off parallax scrolling for tablets and phones */
      @media only screen and (max-device-width: 1024px) {
          .bgimg-1, .bgimg-2, .bgimg-3 {
              background-attachment: scroll;
          }
      }
</style>

<div id="page-wrapper" style="color:#ddd;background-color:#282E34;text-align:center;padding:50px 80px;text-align: justify;">

<p>! HOLA ¡</p>
    <p>Seas bienvenido usuario {{$usuarioactual->name}}, Navega entre las opciones que tenemos para tí . . .</p>

<div class="bgimg-1"> 

</div>

</div>
@endsection