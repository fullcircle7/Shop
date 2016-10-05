<?php

/* Check drivers installed.
fwrite(STDOUT, PDO::getAvailableDrivers());
*/

if (!file_exists(dirname(__FILE__) . '/mydb.sq3')) {
    fwrite(STDOUT, 'it did not exist, create it and map out the tables' . PHP_EOL);
    $db = new PDO('sqlite:' . dirname(__FILE__) . '/mydb.sq3');


    //create the database
    $db->exec("CREATE TABLE Dogs (Id INTEGER PRIMARY KEY, Breed TEXT, Name TEXT, Age INTEGER)");
    //insert some data...
    $db->exec("INSERT INTO Dogs (Breed, Name, Age) VALUES ('Labrador', 'Tank', 2);".
        "INSERT INTO Dogs (Breed, Name, Age) VALUES ('Husky', 'Glacier', 7); " .

        "INSERT INTO Dogs (Breed, Name, Age) VALUES ('Golden-Doodle', 'Ellie', 4);" .
        "INSERT INTO Dogs (Breed, Name, Age) VALUES ('Bee', 'Baby', 4);" .
        "INSERT INTO Dogs (Breed, Name, Age) VALUES ('Fly', 'Baeyyy', 4);" .
        "INSERT INTO Dogs (Breed, Name, Age) VALUES ('Hello', 'Me', 4);"

    );





} else {
    fwrite(STDOUT, 'it already exists, we do not need to create it again.' . PHP_EOL);
    //exit();
}

function firstTest()
{
    $db = new PDO('sqlite:mydb.sq3');

    $result = $db->query('SELECT * FROM Dogs');
    foreach ($result as $row) {
        fwrite(STDOUT, $row['Breed'] . PHP_EOL);
    }
}

firstTest();

function secondTest()
{

}


