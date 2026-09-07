<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ETIQUETAS AUTOMATIZADAS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }

        .post {
            background: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        h1 {
            margin-bottom: 20px;
        }

        #btnbaixar {
            width: 250px;
            height: 50px;
            font-size: 22px;
            border-radius: 5px;
            box-shadow: 1px 1px 5px;
            background-color: #3acb3a;
            color: white;
            cursor: pointer;
            transition-duration: 0.4s;

        }

        #btnbaixar:hover {
            background-color: #279927;
        }
    </style>
</head>

<body>

    <h1>ETIQUETAS AUTOMATIZADAS</h1>
    <hr>

    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="arquivos[]" id="arquivos" multiple>
        <br><br>
        <input type="submit" value="Baixar" id="btnbaixar">
    </form>

</body>

</html>