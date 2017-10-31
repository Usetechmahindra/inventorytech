<?php
/**
 * Description of cmonitor
 *
 * @author USEWEENERS
 */

class cmonitor extends cparent{
    
    // La función genera con los datos post la gráfica deseada
    public function createchart()
    {
        try {
            // Create the chart - Column 2D Chart with data given in constructor parameter 
            // Syntax for the constructor - new FusionCharts("type of chart", "unique chart id", "width of chart", "height of chart", "div id to render the chart", "type of data", "actual data")
            
            $columnChart = new FusionCharts("column2d", "ex1", "100%", "55%", "dgraf", "json", '{  
                            "chart":{  
                              "caption":"Harry\'s SuperMart",
                              "subCaption":"Top 5 stores in last month by revenue",
                              "numberPrefix":"$",
                              "theme":"ocean"
                            },
                            "data":[  
                              {  
                                 "label":"Bakersfield Central",
                                 "value":"880000"
                              },
                              {  
                                 "label":"Garden Groove harbour",
                                 "value":"730000"
                              },
                              {  
                                 "label":"Los Angeles Topanga",
                                 "value":"590000"
                              },
                              {  
                                 "label":"Compton-Rancho Dom",
                                 "value":"520000"
                              },
                              {  
                                 "label":"Daly City Serramonte",
                                 "value":"330000"
                              }
                            ]
                        }');
            // Render the chart
            $columnChart->render();
        } catch (Exception $ex) {
            $_SESSION['textsesion']='Error en función createchart: '.$ex->getMessage();
            $this->error();
            return -1;
        }
    }
}
