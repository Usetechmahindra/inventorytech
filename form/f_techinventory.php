<?php
session_start(); 

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>WEB TechInventory</title>
        <link rel="stylesheet" type="text/css" href="../css/techinventory.css">
        <link rel="stylesheet" href="../css/jquery-ui_tech.css">
        <script src="../java/jquery.js"></script>
        <script src="../java/jquery-ui.js"></script>
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
        div#dcuerpo{
            <?php
                echo 'height:'.$_SESSION['cheight'].';'; 
            ?>
            overflow-y:auto;
        }
        div#menuleft{
            <?php
                $sizemenu = (int)$_SESSION['cheight']-15;
                echo 'height:'.$sizemenu.';';
            ?> 
        }
        </style>
    </head>
    <body>
    <?php 
    // El body dentro de php
        echo '<img src = "../upload/images/i_inventario.png" width = "75" alt = "i_inventario"/>';
        echo '<img src = "../upload/images/Tech_Mahindra_logo.png" style="opacity: 0.5; filter: alpha(opacity=50);" alt = "techmahindra-white"/>';
        echo '<div id="contenedor">';
            // DIV de MENU
            echo '<div id="menuleft">';
                include 'f_menuleft.php';
            echo '</div>';
            // Div cuerpo
            echo '<div id="dcuerpo">';
                include 'f_techbody.php';
            echo '</div>';
        echo '</div>';
    ?>
    </body>
</html>