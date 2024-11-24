<?php

$db=new SQLite3("./database.db",SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE,"");

$query = file_get_contents("database_template.sql");

$db->exec($query);