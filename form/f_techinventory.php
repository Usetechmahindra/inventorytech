<?php
session_start();
require('../class/cparent.php');
require('../class/cgroup.php');
require('../class/cuser.php');
// Función del parametro pasado por el get
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>WEB TechInventory</title>
        <link rel="stylesheet" type="text/css" href="../css/techinventory.css">
        <link rel="stylesheet" href="../css/jquery-ui_tech.css">
        <script src="../java/jquery.js"></script>
        <script src="../java/jquery-ui.js"></script>
        <script>
            // Script de control de tabs de HTML,CSS y JAVA
            function openTab(evt, tabName) {
                // Declare all variables
                var i, tabcontent, tablinks;

                // Get all elements with class="tabcontent" and hide them
                tabcontent = document.getElementsByClassName("tabbodycontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Get all elements with class="tablinks" and remove the class "active"
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                
                // Show the current tab, and add an "active" class to the button that opened the tab
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
                // Guardar tab activa
                document.cookie = "ctabname="+tabName;
            }
            // Control de active menu
            function openbody(pentity,pform) {
                document.cookie = "cactivemenu="+$("#dinmenu").accordion( "option", "active" );
                document.cookie = "centity="+pentity;
                document.cookie = "cform="+pform;
                window.location="../form/f_techinventory.php";
    //            var activemenu = getCookie("cactivemenu");
    //            alert("Active index: " + activemenu);
                //window.location="http://www.cristalab.com";

            }
            function getCookie(cname) {
                var name = cname + "=";
                var decodedCookie = decodeURIComponent(document.cookie);
                var ca = decodedCookie.split(';');
                for(var i = 0; i <ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
        </script>
        <style>
        body{
            background:url(../upload/images/techmahindra-security.jpg);
            background-size: cover;
/*            overflow: hidden;*/
        }
        </style>
    </head>
    <body>
    <?php
    // Control cookies
    $gentity = $_COOKIE['centity'];
    $gform = $_COOKIE['cform'].'.php';
    // El body dentro de php
        echo '<div id="headbody">';
            echo '<img src = "../upload/images/i_inventario.png" width = "75" alt = "i_inventario"/>';
            echo '<img src = "../upload/images/Tech_Mahindra_logo.png" style="opacity: 0.5; filter: alpha(opacity=50);" alt = "techmahindra-white"/>';
        echo '</div>';
        echo '<div id="contenedor">';
            echo '<div id="detbody">';
            // DIV de MENU
                echo '<div id="menuleft">';
                    include 'f_menuleft.php';
                echo '</div>';
                // Div cuerpo
                echo '<div id="dcuerpo">';
                    //include $gform;
                    include $gform;
                echo '</div>';
            echo '</div>';
        echo '</div>';
    ?>
    </body>
</html>