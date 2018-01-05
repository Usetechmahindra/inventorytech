<html>
    <head>
        <style>
            th {
              background-color: <?php echo $_SESSION['color']; ?>;
              color: white;
            }
        </style>
        <!Funciones post>
        <!--Importante añadir la función java-->
        <script src="../class/fusioncharts/fusioncharts.js"></script>
        <?php
        // Crear clase de para llamada a funciones genericas
        // Control POST
        include("../class/fusioncharts/fusioncharts.php");
        $cmon = new cmonitor("item_monitor");
        
        if($_POST['idform'] == 'ffinditem') {
            $stexto = $cmon->createchart();
        }
//        if($_POST['idform'] == 'importexcel') {
//            // Controlar Nuevo o busqueda
//            if(!empty($_FILES["fileToUpload"]["tmp_name"])) {
//               $rows = $cimport->newexcel($gentity);
//               if ($rows <> 0) {
//                // Creación de detalles de excel
//                    $citemexcel->intemexcelnew($rows[0]->id, $rows[0]->pkname);
//               }
//            }
//        }
           
        ?>
        <SCRIPT LANGUAGE="JavaScript">

        </SCRIPT>
    </head>
    <body>
        <form name="ffinditem" id="ffind" method="post">
           <!--Parametro oculto que identificar el form.--> 
           <input type="hidden" name="idform" value="ffinditem">
            <div id="dfind">
                <?php
                    $cmon->labelinput('monitor1',$_POST['monitor1'],'Monitor 1','monitor',30,false,false,false);
                    //$cmon->labelinput('monitor2',"",'Monitor 2','monitor',30,false,false,false);
                    $cmon->labelinput('ddfile',$_POST['ddfile'],'Desde','date',10,false,false,false);
                    $cmon->labelinput('hdfile',$_POST['hdfile'],'Hasta','date',10,false,false,false);
                    echo ' Multiseries: ';
                    echo '<input type="radio" name="grafica" value="area" checked> Area ';
                    echo '<input type="radio" name="grafica" value="column"> Columnas ';
                    echo ' / Una serie: ';
                    echo '<input type="radio" name="grafica" value="pie3d"> Pie '; //No soporta multiseries
                    echo '<input type="radio" name="grafica" value="doughnut2d"> Doughnut ';
                    echo ' <input type="submit" class="bfind" name="fbutton" id="fbutton" value=""/> ';
                ?>    
            </div>
        </form>
        <div id="dgraf"><!-- Fusion Charts will render here--></div> 
        
        </div>
        <div id="dgridhistorico">
            
        </div>
        <div id="pie">
            <?php
                echo '<hr style="color:'.$_SESSION['color'].';" />';
                echo '<p style="color:'.$_SESSION['color'].';">'.$stexto."</p>";
            ?>
        </div>
    </body>
</html>

