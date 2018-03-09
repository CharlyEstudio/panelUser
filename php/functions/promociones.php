<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Ingresar PROMOTRUPER</title>
	<link rel="stylesheet" href="../../css/bootstrap-3-3-7.min.css">
	<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		function pasarDato() {
			var codigo = document.getElementById("codigos").value;
			console.log(codigo);
			
			$('#codIngresados').val(function(){
				$(this).after("<p id='" + codigo + "'>" + codigo + "</p>")
			});

			$('#codigos').val('');
/*			document.getElementById("codigos").focus();*/
		}
	</script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h4>Alta de Codigos Truper</h4>
				<form>
					<div class="form-group">
						<label for="exampleInputPassword1">Código</label>
						<input type="number" class="form-control" id="codigos" placeholder="Ingresar código(s)" onblur="pasarDato()">Puedes ingresar varios código de la misma promoción
						<div id="boton"></div>
					</div>
					<div class="form-group" id="codIngresados">
						<!-- <label for="exampleSelect2">Códigos Ingresados</label>
						<select multiple class="form-control" id="codIngresados">
						</select> -->
					</div>
					<div class="form-group">
						<label for="exampleSelect1">Example select</label>
						<select class="form-control" id="exampleSelect1">
							<option>1</option>
							<option>2</option>
							<option>3</option>
							<option>4</option>
							<option>5</option>
						</select>
					</div>
					<div class="form-group">
						<label for="exampleTextarea">Example textarea</label>
						<textarea class="form-control" id="cajaTexto" rows="3"></textarea>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>