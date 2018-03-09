function calcular(){
	var mesActual 			= document.getElementById("mesActual").innerHTML;
	var mesAnterior 		= document.getElementById("mesAnterior").innerHTML;
	/*var diasActual 			= prompt("Ingrese los días del mes actual a calcular:");
	var diasAnterior		= prompt("Ingrese los días del mes anterior a calcular:");
	var diasConteo 			= prompt("Ingrese los días que han pasado (días hábiles):");*/
	var diasActual 			= document.getElementById("diasActual").value;
	var diasAnterior		= document.getElementById("diasAnterior").value;
	var diasConteo 			= document.getElementById("diasConteo").value;

	//sacamos los dias faltantes para el cierre
	var diasFaltantes		= diasActual - diasConteo;

	//Venta por dia del mes actual y del anterior
	var ventaDiaCalActual 	= mesActual/diasConteo;
	var ventaDiaCalAnterior = mesAnterior/diasAnterior;

	//sacamos el pronostico mensuale del vendedor
	var pronosticoMensual	= ventaDiaCalActual * diasActual;

	//sacamos la proyección al cierre
	var proyeccionCierre 	= pronosticoMensual - mesAnterior;

	//sacamos la venta por dia para igualar del vendedor
	var ventaDiaIgualar		= (mesAnterior - mesActual)/diasFaltantes;


	if (diasActual > 0 && diasAnterior > 0 && diasConteo > 0) {
        document.getElementById("ventaPorDiaActual").innerHTML = "<p>Venta por día mes actual <br /><b style='font-size:2em;'>$ "+currency(ventaDiaCalActual)+"</b></p>";
        document.getElementById("ventaPorDiaAnterior").innerHTML = "<p>Venta por día mes anterior <br /><b style='font-size:2em;'>$ "+currency(ventaDiaCalAnterior)+"</b></p>";
        document.getElementById("pronosticoMensual").innerHTML = "<p>Pronostico Mensual <br /><b style='font-size:2em;'>$ "+currency(pronosticoMensual)+"</b></p>";
        if(proyeccionCierre < 0){
        	proyeccionCierre = proyeccionCierre * -1;
        	document.getElementById("proyeccionCierre").innerHTML = "<p>Proyección al cierre <br /><b style='font-size:2em;color:red;'>(-)$ "+currency(proyeccionCierre)+"</b></p>";
        } else {
        	document.getElementById("proyeccionCierre").innerHTML = "<p>Proyección al cierre <br /><b style='font-size:2em;color:blue;'>(+)$ "+currency(proyeccionCierre)+"</b></p>";
        }
        if(ventaDiaIgualar < 0){
        	document.getElementById("ventaPorDiaIgualar").innerHTML = "<p>Venta por día para igualar <br /><b style='font-size:2em; color: red;'>$ "+currency(ventaDiaIgualar)+"</b></p>";
        } else {
        	document.getElementById("ventaPorDiaIgualar").innerHTML = "<p>Venta por día para igualar <br /><b style='font-size:2em; color: blue;'>$ "+currency(ventaDiaIgualar)+"</b></p>";
        }
    } else {
    	alert("Favor de rellenar todos los campos para calcular correctamente.");
    }
}

function currency(value, decimals, separators) {
	decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
	separators = separators || [',', "'", '.'];
	var number = (parseFloat(value) || 0).toFixed(decimals);
	if (number.length <= (4 + decimals))
			return number.replace('.', separators[separators.length - 1]);
	var parts = number.split(/[-.]/);
	value = parts[parts.length > 1 ? parts.length - 2 : 0];
	var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
			separators[separators.length - 1] + parts[parts.length - 1] : '');
	var start = value.length - 6;
	var idx = 0;
	while (start > -3) {
			result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
					+ separators[idx] + result;
			idx = (++idx) % 2;
			start -= 3;
	}
	return (parts.length == 3 ? '-' : '') + result;
}