<?php
/************************ Settings ************************/
//MySQL
$servername = 'mysql';
$username = 'project';
$password = 'project';
$dbname = 'project';

//MySQL tables
$sql_files = array(
  "employees.sql",
  "job_titles.sql",
  "locations.sql",
  "employee_location.sql"
);

//CSV Files
$import_files = array(
  "employees.csv" => "employees",
  "job_titles.csv" => "job_titles",
  "locations.csv" => "locations"
);

/************************ Connection ************************/
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/************************ Tables ************************/
//Create Tables
foreach ($sql_files as $sql_file) {
  mysqli_query($conn, file_get_contents($sql_file));
}

/************************ Import ************************/
// Import each file
foreach ($import_files as $file_name => $table_name) {

  // Check File Open
  if (($handle = fopen("./" . $file_name, "r")) !== FALSE) {
    // Get Columns
    if (($columns = fgetcsv($handle, 1000, ",")) === FALSE) continue;
    $column_count = count($columns);

    // Get Data
    while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      $num = count($data);

      //Insert Data
      if($num == $column_count) {
        $insert_sql = "INSERT INTO $table_name (" . implode(',', $columns) . ") VALUES ('" . implode("','", $data) . "')";
        mysqli_query($conn, $insert_sql);
      }
    }
  }
}


$conn->close();
?>
