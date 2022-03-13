<?php
function buildResponse($type, $msg){
  $obj = (object) [
    'type' => $type,
    'msg' => $msg
  ];
  
  return $obj;
}

function CheckPermission($conn){
  $userObj = $_POST['user'];

  if(false){
      throw new Exception("User lacks permissions to access this function");
  }
}

function insert($conn){
    
  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $data = $_POST['input']['data'];

  //read values from end then remove
  $val = end($data);
  $entry = key($data);
  unset($data[$entry]);

  $entriesSql = $entry;
  $valuesSql = "\"$val\"";

  foreach ($data as $entry => $val){
    $entriesSql = $entriesSql.", $entry";
    $valuesSql = $valuesSql.", \"$val\"";
  }

  $stmt = $conn->prepare("INSERT INTO $table ($entriesSql) VALUES ($valuesSql)");

  $stmt->execute();

  return buildResponse("success", "");
}

function update($conn){
    
  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $id = $_POST['input']['id'];
  $data = $_POST['input']['data'];

  //read values from front then remove
  $val = end($data);
  $entry = key($data);
  unset($data[$entry]);

  $setSql = "$entry=$val";
  
  foreach ($data as $entry => $val){
    $setSql = $entriesSql.", $entry=$val";
  }

  $stmt = $conn->prepare("UPDATE $table SET $setSql WHERE id=:id");
  $stmt->bindParam(':id', $id);

  $stmt->execute();

  return buildResponse("success", "");
}

function remove($conn){
    
  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $id = $_POST['input']['id'];

  $stmt = $conn->prepare("DELETE FROM $table WHERE id=:id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  return buildResponse("success", "");
}

function selectById($conn){
    
  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $id = $_POST['input']['id'];

  $stmt = $conn->prepare("SELECT * FROM $table WHERE id=:id LIMIT 1");
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  return $stmt->fetch();
}

function selectCount($conn){
    
  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];

  $stmt = $conn->prepare("SELECT * FROM $table");
  $stmt->execute();
  $count = $stmt->rowCount();

  return   $obj = (object) [
    'count' => $count,
  ];
}

function selectAll($conn){

  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $limit =  $_POST['input']['limit'];

  $stmt = $conn->prepare("SELECT * FROM $table LIMIT $limit");

  $stmt->execute();

  return $stmt->fetchAll();
}

function selectWhere($conn){

  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $conditions =  $_POST['input']['conditions'];
  $limit =  $_POST['input']['limit'];

  $val = end($conditions);
  $entry = key($conditions);
  unset($conditions[$entry]);

  $conditionSql = "$entry=$val";

  foreach ($conditions as $entry => $value){
    $setSql = $entriesSql." AND $entry=$value";
  }

  $stmt = $conn->prepare("SELECT * FROM $table WHERE $conditionSql LIMIT $limit");

  $stmt->execute();

  return $stmt->fetchAll();
}

function selectWhereActive($conn){

  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $conditions =  $_POST['input']['conditions'];
  $datetime = $_POST['input']['datetime'];
  $limit =  $_POST['input']['limit'];

  $val = end($conditions);
  $entry = key($conditions);
  unset($conditions[$entry]);

  $conditionSql = "$entry=$val";

  foreach ($conditions as $entry => $value){
    $setSql = $entriesSql." AND $entry=$value";
  }

  $stmt = $conn->prepare("SELECT * FROM $table WHERE $conditionSql AND end_datetime>:datetime LIMIT $limit");
  $stmt->bindParam(':datetime', $datetime);
  $stmt->execute();
  
  return $stmt->fetchAll();
}

function selectWhereLessThan($conn){

  CheckPermission($conn);
  $stmt = null;
  $table = $_POST['input']['table'];
  $conditions =  $_POST['input']['conditions'];
  $limit =  $_POST['input']['limit'];

  $val = end($conditions);
  $entry = key($conditions);
  unset($conditions[$entry]);

  $conditionSql = "$entry<$val";

  foreach ($conditions as $entry => $value){
    $setSql = $entriesSql." AND $entry<$value";
  }

  $stmt = $conn->prepare("SELECT * FROM $table WHERE $conditionSql LIMIT $limit");

  $stmt->execute();

  return $stmt->fetchAll();
}
?>