<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="h1">Lector de códigos de barras</h1>
        <div class="form-group">
            <input class="form-control" type="text" name="lector" id="lector" placeholder="Escannear códgo de barras" onChange="validar_enter(event);" >
        </div>
    </div>
    <script>
        function validar_enter(oEvent){
            console.log(oEvent);
        }
    </script>
</body>
</html>