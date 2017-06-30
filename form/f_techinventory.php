<?php
session_start(); 

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>WEB TechInventory</title>
        <link rel="stylesheet" type="text/css" href="../css/techinventory.css">
        <style>
        body{
            background:url(../upload/images/techmahindra-security.jpg);
            background-size: cover;
/*            overflow: hidden;*/
        }
        div#contenedor{
            <?php
                echo 'height:'.$_SESSION['cheight'].';';
            ?>
        }
        div#menuleft{
            <?php
                $sizemenu = (int)$_SESSION['cheight']-15;
                echo 'height:'.$sizemenu.';';
            ?> 
        }
        th, td {
            padding: 5px;
        }
        .boton {
            background-color: #758697;
            border: 1px solid #e7e7e7;
            color: white;
            padding: 5px 31px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 4px 2px;
            cursor: pointer;
        }
        .boton:hover {background-color: #3e8e41}
        </style>
    </head>
    <body>
    <?php 
    // El body dentro de php
        echo '<img src = "../upload/images/Tech_Mahindra_logo.png" style="opacity: 0.5; filter: alpha(opacity=50);" alt = "techmahindra-white"/>';
        echo '<div id="contenedor">';
        echo '<div id="menuleft">';
        echo '</div>';
        echo '</div>';
    ?>
    </body>
</html>