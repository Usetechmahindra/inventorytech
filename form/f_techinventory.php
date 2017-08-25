<?php
session_start();
require('../class/cparent.php');
require('../class/cgroup.php');
require('../class/cuser.php');
require('../class/citem.php');
require('../class/caudit.php');
require('../class/centity.php');
// Control cookies
$gentity = $_COOKIE['centity'];
if ($gentity <> $_SESSION['$gentity']) {
    // Borrar id actual.
    $_SESSION['$gentity'] = $gentity;
    unset($_SESSION['idact']);
} 
$gform = $_COOKIE['cform'].'.php';
// Crea clase user para control de sesión y tiempo
$cguardian = new cuser("usuario"); 
// Control timeout de sesion o entidad no permitidad
if ($cguardian->CheckLogin() < 0) {
    // Enviar a login
    header("Location: ../index.php");
} 
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
                // Al finalizar el post desactivar variable
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
            // Recoge el id del grid y lo muestra en edición.
            function fclick(pid) {
//                alert(pid);
                document.cookie = "cid="+pid;
                document.cookie = "ctabname=Edición";
		//alert(funtion);
                //window.location="../form/f_techinventory.php";
                document.myform.submit();
            }
            // Función para obtener una cookie determinada
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
            $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '<Ant',
                nextText: 'Sig>',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd-mm-yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
                };
                $.datepicker.setDefaults($.datepicker.regional['es']);
                //Meter punteros a los diferentes fecha
                $(function () {
                    $(".cdate").datepicker({
                    changeMonth: true,
                    changeYear: true
                    });
                });
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