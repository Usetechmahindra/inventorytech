<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start(); 
// Control de parametros intro
$vuser = addslashes(strip_tags($_GET["usuario"]));
$vpass = addslashes(strip_tags($_GET["pass"]));

if (!empty($vuser))
{
    $_POST['bvalida']=1;
    $_POST['user'] =$vuser;
    $_POST['password']=$vpass;
}

 // Declarar la usuario. Se necesitan importar la clase padre e hija
require('class/cparent.php');
require('class/cuser.php');
$cuser = new cuser("cuser");
$cuser->readcfg();
if(isset($_POST['bvalida']))
{
    $ilogin = $cuser->login();
    if ($ilogin > 0){
        header("Location: f_techinventory.php");
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login TechInventory</title>
        <link rel="stylesheet" type="text/css" href="css/techinventory.css">
        <style>
        body{
            overflow: hidden;
            background:url(upload/images/techmahindra-security.jpg);
            background-size: cover;
        }
        div#contlogin{
            margin: 200px;
            height: 200px;
            background-color: #e31732;
            opacity: 0.8;
            filter: alpha(opacity=80); /* For IE8 and earlier */
            box-shadow: 2px 5px 8px;
            border-collapse: separate;
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
        echo '<img src = "upload/images/Tech_Mahindra_logo.png" alt = "techmahindra-white"/>';
        echo '<div id="contlogin">';
        echo '<form name="flogin" method="post">';
        
        echo '<table border = "0" style="width:100%; height:100%;">';
        echo '<tbody>';
        echo '<tr>';
        echo '<td></td>';
        echo '<td><p style="color: white; font-weight: bold">Inicio de sesión<p></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td style="text-align: right"><p style="color: white;">Nombre de usuario</p></td>';
        echo '<td><input type="text" name="user" id="user" required="required" value="'.$_POST['user'].'"/></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td style="text-align: right"><p style="color: white;">Password</p></td>';
        echo '<td><input type="password" name="password" id="password" required="required" value="'.$_POST['password'].'"/></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td></td>';
        echo '<td><input type="submit" class="boton" name="bvalida" id="bvalida" value="Iniciar sesión"/></td>';
        
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        // Control pulsado.
        echo $_SESSION['textsesion'];
        echo '</form>';
        echo '</div>';
    ?>
    </body>
</html>
