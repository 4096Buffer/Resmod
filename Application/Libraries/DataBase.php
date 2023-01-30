<?php 

namespace Code\Libraries;

/**
 * Library that works as helper for any database thingss
 */

class DataBase extends \Code\Core\BaseController {
    private $config;
    private $connect;

    public function __construct() {
      parent::__construct();

      $this->config = $this->config_->Get('DataBase');
      $this->connect = mysqli_connect(
        $this->config["Host"],
        $this->config["User"],
        $this->config["Password"],
        $this->config["DataBase"]
      );

      if($this->ErrorCode()) {
        die($this->Error()); 
      }

    }
    
    private function refValues($arr){
      if (strnatcmp(phpversion(),'5.3') >= 0) {
        $refs = [];
        
        foreach($arr as $key => $value) {
          $refs[$key] = &$arr[$key];
        }

        return $refs;
      }

      return $arr;
    }


    public function setupBind($stmt, array $bind) {
        $types = '';
        
        foreach ($bind as $val) {
          switch (gettype($val)) {
            case 'integer': $types .= 'i'; break;
            case 'double': $types .= 'd'; break;
            default: $types .= 's';
          }
        }

        array_unshift($bind, $types);
        call_user_func_array([ $stmt, 'bind_param' ], $this->refValues($bind));

     }



    
    public function DoQuery(string $sql, array $bind = null) {
      if (!($stmt = $this->connect->prepare($sql))) {
        return null;
      }

      if ($bind) {
        $this->setupBind($stmt, $bind);
      }


      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();

      return $result;
    }
    
    public function GetFirstRow($sql, $bind) {
        $result = $this->DoQuery($sql, $bind);
        
        if(!$this->Exists($result)) {
            return null;
        } 
        
        $row = $result->fetch_assoc();
        return $row;
    }
    public function Error() {
      return $this->connect->error;
    }

    public function ErrorCode() {
      return $this->connect->errno;
    }

    public function Exists($result) {
      
      if($result->num_rows == 0) {
        return false;
      } else {
        return true;
      }

    }

    public function FetchRows($result) {
      $fetch = array();

      while($row = $result->fetch_assoc()){
          $fetch[] = $row;
      }

      return $fetch;
    }

    public function GetMysqlServerInfo() {
      return mysql_get_server_info();
    }
}


?>