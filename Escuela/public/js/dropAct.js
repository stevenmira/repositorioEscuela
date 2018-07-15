
$("#trimes").change(event => {
	$.get(`/actividades/${event.target.value}`, function(res, sta){
		$("#activi").empty();
		res.forEach(element => {
			$("#activi").append(`<option value=${element.id_actividad}> ${element.nombre} </option>`);
		});
	});
});