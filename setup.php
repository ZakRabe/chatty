<?php
$db_user      = '';
$db_pass      = '';
$new_db       = '';
$new_db_user  = '';
$new_db_pass  = '';


// don't make assumptions that root is available. Eg/ installing on production
while($db_user == ''){
  print "Existing db username (eg. root): ";
  $handle = fopen ("php://stdin","r");
  $db_user = trim(fgets($handle));
  if($db_user == ''){
    print "Please type a username name\n";
  }
}

while($db_pass == ''){
  print "Existing db password (eg. saints22): ";
  $handle = fopen ("php://stdin","r");
  $db_pass = trim(fgets($handle));
  if($db_pass == ''){
    print "Please type a database name\n";
  }
}

// don't make the assumption that the db should be the same as the user
while($new_db == '') {
  print "This App's database name (eg. cms_test): ";
  $handle = fopen ("php://stdin","r");
  $new_db = trim(fgets($handle));
  if($new_db == ''){
    print "Please type a database name\n";
  }
}

while($new_db_user == '') {
  print "This App's username name (eg. cmsuser): ";
  $handle = fopen ("php://stdin","r");
  $new_db_user = trim(fgets($handle));
  if($new_db_user == ''){
    print "Please type a username name\n";
  }
}

while($new_db_pass == '') {
  print "This App's db password: ";
  $handle = fopen ("php://stdin","r");
  $new_db_pass = trim(fgets($handle));
  if($new_db_pass == ''){
    print "Please type a password\n";
  }
}


print "Update Config Files? (y/n) ";
$handle = fopen ("php://stdin","r");
$updateConfig = trim(fgets($handle));

if(strtolower($updateConfig) == 'y'){
  print "This App's name: ";
  $handle = fopen ("php://stdin","r");
  $app_name = trim(fgets($handle));
}

print "\n";

  /* ======
   * CONFIG
   * ======
   */
  $mysql = Array(
    'host' => 'localhost',
    'user' => $db_user,                 // admin user
    'pass' => $db_pass,                 // admin password
  );

  $setup = Array(
    'mysql'=>Array(
      'host' => 'localhost',            // access hostname/ip
      'user' => trim($new_db_user),     // cms username
      'pass' => trim($new_db_pass),     // http://passwdtools.com/
      'db'   => trim($new_db),          // database to create for setup
    ),
    // relative path
    'sql_path' => 'protected/data/schema.sql',
  );

  /* ==========
   * END CONFIG
   * ==========
   */

  $db = mysqli_connect(
    $mysql['host'],
    $mysql['user'], 
    $mysql['pass']
  );

  if ($db->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
  }

  try {
    print "Creating Database\n";
    $db->query(sprintf(
      "CREATE DATABASE `%s`",
      $db->real_escape_string($setup['mysql']['db'])
    ));

    print "Creating User\n";
    $db->query(sprintf(
        "CREATE USER '%s'@'%s' IDENTIFIED BY '%s'",
        $db->real_escape_string($setup['mysql']['user']), 
        $db->real_escape_string($setup['mysql']['host']), 
        $db->real_escape_string($setup['mysql']['pass'])
      )
    );

    print "Granting Privilages\n";
    $db->query(sprintf(
        "GRANT ALL PRIVILEGES ON `%s`.* TO '%s'@'%s'",
        $db->real_escape_string($setup['mysql']['db']), 
        $db->real_escape_string($setup['mysql']['user']), 
        $db->real_escape_string($setup['mysql']['host'])
      )
    );

    print "Installing CMS tables\n";
    $command = sprintf(
      'sudo mysql --host=%s --user=%s --password=%s --database=%s < %s',
        $mysql['host'],
        $setup['mysql']['user'],
        $setup['mysql']['pass'],
        $setup['mysql']['db'],
        dirname(__FILE__).'/'.$setup['sql_path']
    );
    shell_exec($command);

    if(strtolower($updateConfig) == 'y') {
      print "Updating staging config file\n";
      $staging_config = './protected/config/main-staging.php';
      $staging_config_content = file_get_contents($staging_config);

      if($app_name != ''){
        $staging_config_content = str_replace('{{app_name}}',$app_name,$staging_config_content);
      }
      $staging_config_content = str_replace('dbname=cms_test','dbname='.$new_db,$staging_config_content);
      $staging_config_content = str_replace('cms_tester',$new_db_user,$staging_config_content);
      $staging_config_content = str_replace('saints',$new_db_pass,$staging_config_content);

      file_put_contents($staging_config, $staging_config_content);



      print "Updating production config file\n";
      $production_config = './protected/config/main-production.php';
      $production_config_content = file_get_contents($production_config);

      if($app_name != ''){
        $production_config_content = str_replace('{{app_name}}',$app_name,$production_config_content);
      }
      $production_config_content = str_replace('{{database_name}}',$new_db,$production_config_content);
      $production_config_content = str_replace('{{database_user}}',$new_db_user,$production_config_content);
      $production_config_content = str_replace('{{database_password}}',$new_db_pass,$production_config_content);

      file_put_contents($production_config, $production_config_content);
    } 


  } catch(PDOException $ex) {
    $db->rollBack();
    echo $ex->getMessage();
  }

  print "Installed.\n";
  exit;