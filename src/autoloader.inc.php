<?php

spl_autoload_register (function ($class) {

    $sources = array("$class.php",
                     "/Databases/$class.php",
                         "/Databases/Interface/$class.php",
                     "/Inventory/$class.php",
                     "/Suppliers/$class.php",
    );

    foreach ($sources as $source) {

        $source = dirname(__FILE__) . $source;

        if (file_exists($source)) { //checks each location for the class
            require_once $source;
            break; //ends the loop now the class has been found
        }
    }
});

