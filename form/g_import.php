<html>
    <head>
        <style>
            th {
              background-color: <?php echo $_SESSION['color']; ?>;
              color: white;
            }
        </style>
        <!Funciones post>
        <?php
        // Crear clase de para llamada a funciones genericas
        // Control POST
        $cimport = new centity("filesexcel");
        $citemexcel = new citem("item_excel");
        
        if($_POST['idform'] == 'ffindexcel') {
            $rows = $cimport->findsexcel($gentity);
        }
        if($_POST['idform'] == 'importexcel') {
            // Controlar Nuevo o busqueda
            if(!empty($_FILES["fileToUpload"]["tmp_name"])) {
               $rows = $cimport->newexcel($gentity);
               if ($rows <> 0) {
                // Creación de detalles de excel
                    $citemexcel->intemexcelnew($rows[0]->id, $rows[0]->pkname);
               }
            }
        }
        // Baja
        if (isset($_POST['bbaja'])) {
            // Control de baja
                $rows=$cimport->deleteexcel($_POST['id']);
        }
        // Edición detalles
        if (isset($_POST['beditdet'])) {
            $rows=$citemexcel->intemexcelupdate();
        }      
        ?>
        <SCRIPT LANGUAGE="JavaScript">
                function PopUpProc(pid) {
                    if (!confirm('¿Desea procesar el fichero?'))
                    {
                        return 0;
                    }
                    var iMyWidth;
                    var iMyHeight;
                    //half the screen width minus half the new window width (plus 5 pixel borders).
                    iMyWidth = (window.screen.width/2) - (75 + 10);
                    //half the screen height minus half the new window height (plus title and status bars).
                    iMyHeight = (window.screen.height/2) - (100 + 50);
                    //Open the window.
                    var win2 = window.open("pop_procexcel.php?id="+pid,"Progress POP UP","status=no,height=300,width=520,resizable=no,left=" + iMyWidth + ",top=" + iMyHeight + ",screenX=" + iMyWidth + ",screenY=" + iMyHeight + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
                    win2.focus();
                }
        </SCRIPT>
    </head>
    <body>
        <form name="ffindexcel" id="ffind" method="post">
           <!--Parametro oculto que identificar el form.--> 
           <input type="hidden" name="idform" value="ffindexcel">
            <div id="dfind">
                <?php
                    // Recorrer los parámetros dinámicos de la entidad
                    $cimport->labelinput('docid',"",'Id Fichero','number',5,false,false,false);
                    $cimport->labelinput('pkname',"",'Nombre fichero','text',30,false,false,false);
                    $cimport->labelinput('ddfile',"",'Desde','date',10,false,false,false);
                    $cimport->labelinput('hdfile',"",'Hasta','date',10,false,false,false);
                    $cimport->labelinput('bproc',null,'Procesado','checkbox',15,false,true,false);
                    echo '<hr style="color:'.$_SESSION['color'].';" />';
                ?>    
            </div>
        </form>
        <div id="dgrid">
        <?php
            echo '<form name="importexcel" id="impexcel" method="post" enctype="multipart/form-data">';
            echo '<input type="hidden" name="idform" value="importexcel">';
            echo '<input type="file" name="fileToUpload" id="fileToUpload">';
            echo '<input type="submit" class="boton" name="bnew" id="bnewi" value="Cargar Excel" onclick="return confirm(\'¿Cargar excel?\');"/>';
            echo '</form>';
            // Lanzar los detalles del grid (cabecera o detalles)
            $cimport->gridexcel($rows,$_POST['id']);
        ?>      
        </div>
        <?php
            echo '<hr style="color:'.$_SESSION['color'].';" />';
            echo '<p style="color:'.$_SESSION['color'].';">'.$_SESSION['textsesion']."</p>";
        ?>
        </form>
    </body>
</html>

