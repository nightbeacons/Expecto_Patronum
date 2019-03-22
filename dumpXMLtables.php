#!/usr/bin/php
<?php

// BEGIN CONGIFURATION

// Keeping this information here is not terribly secure
// For use in the real world, include these from a separate (secured) file
// or (even better) use .mulogin.cnf -- an encryped binary file
//  See https://dev.mysql.com/doc/refman/8.0/en/mysql-config-editor.html
//      https://opensourcedbms.com/dbms/passwordless-authentication-using-mysql_config_editor-with-mysql-5-6/

$dbserver = "YOUR_DB_HOST";
$dbuser="YOUR_DB_USER";
$dbpass="YOUR_DB_PASSWORD";
$dbname = "YOUR_DB";

// This is always created (empty) for you
$outDir = "/path/to/output/directory/";

// END CONFIGURATION

$db =  new mysqli($dbserver, $dbuser, $dbpass, $dbname);
  if (mysqli_connect_errno()){
  printf("Connection failed: %s\n", mysqli_connect_error());
  exit();
  }

$cmd = "/bin/rm -rf $outDir; mkdir $outDir";
$t=`$cmd`;

$results = $heading = "";

$alltables =  $db->query("SHOW TABLES");

while ($table = $alltables->fetch_array(MYSQLI_ASSOC))
{
   foreach ($table as $db => $tablename)
   {
        $outfile = $outDir . $tablename . ".xml";
        $cmd = "/usr/bin/mysqldump -u" . $dbuser . " -p" . $dbpass . " -h" . $dbserver . "  --xml --single-transaction --no-create-info  $dbname $tablename > $outfile";

// If you are using a .my.cnf file in your $HOME directory (recommended) you can eliminate the Warning messages by using this line:
//      $cmd = "/usr/bin/mysqldump  --xml --single-transaction --no-create-info  $dbname $tablename > $outfile";


$tmp=`$cmd`;
$results .= "$tmp\n" . date("r") . "\n\n==================================================\n\n";
   }
}

echo $results;

