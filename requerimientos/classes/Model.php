<?php

include "conexion.php";
class model extends conexion{
  protected function conecta()
  {
    // $sql = $select;
  //   echo($sql);
    $conn = $this->connect();
    // $query = $conn->query($sql);
    // $conn->close();
    return $conn;
  }

    protected function getAllTable($select)
    {
      $sql = $select;
      // echo($sql);
      $conn = $this->connect();
      $query = $conn->query($sql);
      $conn->close();
      return $query;
    }

    protected function setTable($sql)
    {
      $conn = $this->connect();
      if($conn->connect_errno){
          return array("Estado"=>false,"Comentario" => $conn->connect_errno);
      }
      $query = $conn -> query($sql);
      $conn->close();
      return array("Estado" =>true,"Comentario" => $query);
    }

    protected function getRow($sql)
    {
      $conn = $this->connect();
      $query = $conn -> query($sql);
      // $conn->close();
      $row=[];
      if ($conn->affected_rows==1) {
         $row=$query->fetch_array(MYSQLI_ASSOC);
       }
      $conn->close();
      // print_r($row);
      return $row;
    }
    protected function deleteRow($sql)
    {
      $conn = $this->connect();
      $query = $conn -> query($sql);
      $conn->close();
      // var_dump($query);
      return $query;
    }

    protected function updateTable($query)
    {
      $conn = $this->connect();
      $query = $conn -> query($query);
      if($conn->affected_rows){
        $res = 1;
      }else{
        $res = 0;
      }
      $conn->close();
      return $res;
    }
    protected function getAllTableP($query,$tablaname)
    {
      $conn = $this->connect();
      $sth = $conn->prepare($query);
      $sth ->bind_param('s',$tablaname);
      if (!$sth->execute()) throw new Exception();
      $query = $conn -> query($query);
      $conn->close();
      return $query;
    }
    
    protected function putValuesTable($table,$values,$index)
    {
  
      // echo "<br>";
  
      // echo "dentro de putvaluetable";
  
      // echo "<br>";
  
      $sql="INSERT INTO ".$table." (".$index.") VALUES (".$values.")";
      // print_r($sql);
      // print_r($sql);
      // die;
      // print_r($sql);
      $conn = $this->connect();
  
      $query = $conn -> query($sql);
  
      // if ($conn->affected_rows==1) {
  
      //    $result=true;
  
      // }else{
  
      //     $result=false;
  
      // }
  
      // $conn->close();
  
      // return $result;
  
      if ($conn->affected_rows==1) {
  
         $result=true;
  
      }else{
  
          if ($conn->errno==1062) {
  
            $resulM = str_replace("Duplicate entry", "Error al grabar ya existe ",$conn->error);
  
            $pos      = strripos($resulM, "for key");
  
            if ($pos === false) {
  
                $result=array("success"=>false,"message"=>$conn->error);
  
            } else {
  
                $message = substr($resulM, 0,$pos);
  
                $result=array("success"=>false,"message"=>$message);
  
            }
  
          }else{
  
            $result=array("success"=>false,"message"=>$conn->error);
  
          }
  
      }
  
      $conn->close();
  
      return $result;
  
    }
    
    protected function updateTableIndex($table,$Indexvalues,$index,$value)

    {
  
      $sql="UPDATE `".$table."` SET ".$Indexvalues." WHERE `".$index."`='".$value."'";
  
      // echo "<br>";
  
      // echo "<br>";
  
      $conn = $this->connect();
  
      $query = $conn -> query($sql);
  
      if ($conn->affected_rows==1) {
  
         $result=true;
  
      }else{
  
          $result=false;
  
      }
  
      $conn->close();
  
      return $result;
  
    }
}
?>