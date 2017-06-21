<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $arraycfg = parse_ini_file("cfg/techinventory.ini");
        $couchserver = $arraycfg["couchserver"];
        $bucketName = $arraycfg["bucketName"];
        $passbucket = $arraycfg["passbucket"];
        
        
        $entidad = "rencesvinto";

        // Connect to Couchbase Server
        $cluster = new CouchbaseCluster($couchserver);
        $bucket = $cluster->openBucket($bucketName,$passbucket);
       
        
        $count = $bucket->counter('c_'.$entidad, 1, array('initial' => 1));
        $acont = array();
        $acont['icont'] = $count->value;
        $acont['scont'] = str_pad($count->value, 3, '0', STR_PAD_LEFT);;
        
       //// $bucket->counter('c_'.$entidad, -1);  // Reducir contador.
        echo 'Current counter value is ' . $acont['scont']  . "\n";
        
        echo "Storing ".$entidad."_".$acont['scont']."\n";
        $result = $bucket->upsert($entidad.'_'.$acont['scont'], array(
            "docid" => $acont['icont'],
            "entidad" =>$entidad,
            "email" => $entidad."@techmahindra.com",
            "interests" => array("Queens")
        ));
        $result = $bucket->get($entidad.'_'.$acont['scont']);
        var_dump($result->value);
        
//        // Store a document
//        echo "Storing u:king_arthur\n";
//        $result = $bucket->upsert('u:king_arthur', array(
//            "email" => "kingarthur@couchbase.com",
//            "interests" => array("African Swallows")
//        ));
//
//        // Retrieve a document
//        echo "\n Getting back u:king_arthur\n";
//        $result = $bucket->get("u:king_arthur");
//        var_dump($result->value);
//
//        // Replace a document
//        echo "Replacing u:king_arthur\n";
//        $doc = $result->value;
//        array_push($doc->interests, 'PHP 7');
//        $bucket->replace("u:king_arthur", $doc);
//        var_dump($result);
//
//        echo "\n Creating primary index\n";
//        // Before issuing a N1QL Query, ensure that there is
//        // is actually a primary index.
//        try {
//            // Do not override default name, fail if it is exists already, and wait for completion
//            $bucket->manager()->createN1qlPrimaryIndex('', false, false);
//            echo "Primary index has been created\n";
//        } catch (CouchbaseException $e) {
//            printf("Couldn't create index. Maybe it already exists? (code: %d)\n", $e->getCode());
//        }
//
//        // Query with parameters
//        $query = CouchbaseN1qlQuery::fromString("SELECT * FROM `$bucketName` WHERE \$p IN interests");
//        $query->namedParams(array("p" => "African Swallows"));
//        echo "\n Parameterized query:\n";
//        var_dump($query);
//        $rows = $bucket->query($query);
//        echo "\n Results:\n";
//        var_dump($rows);
        ?>
    </body>
</html>
