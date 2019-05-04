<?php

$conn = null;

function get_conn()
{
  global $conn;
  $dbhost = '127.0.0.1';
  $dbuser = 'root';
  $dbpass = '';
  $dbname = 'test';
  if($conn == null){
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
  }
  if (!$conn) {
    die('Could not connect: ');
  }
  return $conn;
}

function get_result($query)
{
  $conn = get_conn();
  $result = mysqli_query($conn, $query);
  if (!$result) {
    die('query failed' . mysqli_error($conn));
  }
  return $result;
}

function get_row($result)
{
  return mysqli_fetch_row($result);
}

function get_array($result) {
  return mysqli_fetch_array($result, MYSQLI_ASSOC);
}

function get_inserted_id(){
  return mysqli_insert_id(get_conn());
}
