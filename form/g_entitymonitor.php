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
        $cmon->createchart();
        
//        if($_POST['idform'] == 'ffinditem') {
//            $rows = $cimport->findsexcel($gentity);
//        }
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
                    $cmon->labelinput('monitor',"",'Monitor','monitor',30,false,false,false);
                    $cmon->labelinput('ddfile',"",'Desde','date',10,false,false,false);
                    $cmon->labelinput('hdfile',"",'Hasta','date',10,false,false,false);
                    echo '<input type="radio" name="grafica" value="area" checked> Area ';
                    echo '<input type="radio" name="grafica" value="pie"> Pie ';
                    echo '<input type="radio" name="grafica" value="column"> Columnas ';
                    echo ' <input type="submit" class="bfind" name="fbutton" id="fbutton" value=""/> ';
                    echo '<hr style="color:'.$_SESSION['color'].';" />';
                    
                    
                ?>    
            </div>
        </form>
        <div id="dgraf"><!-- Fusion Charts will render here--></div> 
        
        </div>
        <div id="dgridhistorico">
            
        </div>
        <?php
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
        ?>
        </form>
    </body>
</html>

