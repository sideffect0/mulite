<?php
require_once("pathconf.php");
require_once(CONFIGDIR."model.php");
require_once(INCLUDEDIR."error.php");

/*
  Conventions to  follow ..
  find -> function to get array of rows from database
  get -> function to get Single row
*/

class Model{
  protected $__conn;
  protected $__db_flag;
  protected $__table;
  protected $id; 
  private $__varcache;
  private $__updatecache;
  private $__ispk_ai;

/*
  public LIMIT;  'll update soon
  public OFFSET; 
*/ 
 public function __construct(){
  $this->__updatecache = array();
  $this->__conn = mysql_connect(DB_HOST.":".DB_PORT,DB_USER,DB_PSWD);
  $this->__table = strtolower(get_called_class());  //assumes the table name same as the model name
  if(!($this->__conn)){
     $this->modelError("Error in connecting..");
  }
  $this->__db_flag = mysql_select_db(DB_NAME,$this->__conn);
  if(!($this->__db_flag)){
    $this->modelError("Error on selecting db..");
  }
 }
 public function insert_query($fields){
  return "insert into ".$this->__table." (".$fields.")"." values ";
 }
 public function update_query($field,$value){
  return "update ".$this->__table." set $field='$value'";
 }
 public function select_query($fields){
  return "select $fields from ".$this->__table." ";
 }

 public function delete_query(){
  return "delete from ".$this->__table." ";
 }
 public function modelError($error){
  $called = get_called_class();
  $error = PHP_EOL."Error From Model  : '$called' ".PHP_EOL."Error was : $error".PHP_EOL;
  if(MODEL_DEBUG){
   die($error);
  }else{
   error_log($error);
   raiseHTTP500(); 
  }
 }
 public function __destruct(){
  mysql_close($this->__conn);
 }

 public function __fieldify($field_array){
  $field_string = implode("`,`",$field_array);
  $field_string = "`".$field_string."`";
  return $field_string;
 }
 public function __fields(){
         if(isset($this->__varcache)){
           return $this->__varcache;
         }
         $fields = get_object_vars($this);
         unset($fields['__varcache']);
         unset($fields['__updatecache']);
         unset($fields['__conn']);
         unset($fields['__db_flag']);
         unset($fields['__table']);
	 unset($fields['__ispk_ai']);
         $pk_default = $this->__primarykey();
         if($pk_default != "id"){
          unset($fields['id']);
         }
         $fields = array_keys($fields);
         $this->__varcache = $fields; //$this->__fieldify($fields)
         return $this->__varcache;
  }

  public function __isfield($field_name){
   $fields = $this->__fields();
   if(in_array($field_name,$fields)){
    return TRUE;
   }
   return FALSE;
  }

  public function __call($fun, $args) {
            $fun_ex = explode("By",$fun);
            $operation = $fun_ex[0];

            $field = (isset($fun_ex[1]))?strtolower($fun_ex[1]):$this->modelError("Undefined Function '$fun'");
            $dbfields = $this->__fields();
            if(!(in_array($field,$dbfields))){
             $this->modelError("Undefined Function '$fun'");
            }

            $value = (isset($args[0]))?$args[0]:$this->modelError("No Values Given Function '$fun'");
            $return_fields = (isset($args[1]))?$args[1]:"";

            switch(strtolower($operation)){
             case "get":
                return $this->__getByExact($field,$value,$return_fields);
                break;
             case "find":
                return $this->__findByExact($field,$value,$return_fields);
                break;
             case "findlike":
                return $this->__findByLike($field,$value,$return_fields);
                break;
             default:
                $this->modelError("Call to undefined function $operation"); 
            }
   }
   
     public function __set($var,$value){
         if(property_exists($this,$var)){
          $this->$var = mysql_real_escape_string($value);
          $this->__track($var);
          return $this;
         }
     }
     public function __get($var){
         if(property_exists($this,$var)){
           return $this->$var;
         }
     }

     public function __primarykey(){
         return "id";
     }

     public function __ispk_auto_increment($field){
	if(isset($__ispk_ai)){
	 return $__ispk_ai;
        }else{
	 $__ispk_ai = FALSE;
	 $query = "show columns from ".$this->__table;
	 $result = __dbquery($query);
	 while($row = mysql_fetch_assoc($result)){
	  if($row["Extra"] == "auto_increment"){
	   $__auto_incr = $row["Field"];
	  }
	  if($row["Key"] == "PRI"){
           $__pri_f = $row["Field"];
	  }
	 }
 	 if((isset($__pri_f)) && (isset($__auto_incr))){
	  if($__auto_incr == $__pri_f){
	   $__ispk_ai = TRUE;
	  }
	 }
         return $__ispk_ai;
        }
     }

     public function __track($var){
         if(in_array($var,$this->__updatecache)){
          return;
         }
         array_push($this->__updatecache,$var);
      }

      public function __insert($func){
         // Get Fields
         $field_array = $this->__updatecache;
         if(empty($field_array)){
          $this->modelError("Empty Fields");
         }
         $fields = $this->__fieldify($field_array);
         // Get Values
         $values = "";
         foreach($field_array as $field){
          $value = $this->$field;
          $values = $values."'$value',";
         }
         $values = rtrim($values,",");
         $query = $this->insert_query($fields)."(".$values.")";
         $result = $this->__dbquery($query);
         if(!$result){
          $this->modelError("Error Occured on $func Call");
         }
         //Get Primary Key
         $pk = $this->__primarykey();
         $this->$pk = $this->__dbinsertid();

         //Clear update cache
           $this->__updatecache = array();
      }
      public function __update($func){
         $pk = $this->__primarykey();
         $pk_value = $this->$pk;
         $field_array = $this->__updatecache;
         if(empty($field_array)){
          $this->modelError("Empty Fields");
         }
        /* * * BULK UPDATE * * *
         $fields = implode("`,`",$field_array);
         $fields = "`".$fields."`";
       */
         // Update All
         foreach($field_array as $field){
          $value = $this->$field;
          $query = $this->update_query($field,$value)." where `$pk` = '$pk_value' ";
          $result = $this->__dbquery($query);
          if(!$result){
           $this->modelError("Error Occured on $func Call");
          }
         }
       }
        
       public function __dbquery($query){
         $result = mysql_query($query);
         return $result;
       }

       public function __dbinsertid(){
         return mysql_insert_id();
       }
        
       public function __getByExact($field,$value,$return_fields=""){

          if((!empty($return_fields)) && is_array($return_fields)){
             $pk = $this->__primarykey();
             if(in_array($pk,$return_fields)){
               $fields = $return_fields;
             }else{
              array_push($return_fields,$pk);
              $fields = $return_fields;
             }
              
          }else{
             $fields = $this->__fields();
          }
          $fieldified = $this->__fieldify($fields);
          $query = $this->select_query($fieldified)." where `$field` = '$value'";
          $result = $this->__dbquery($query);
          if(!$result){
           $this->modelError("Error on finding");
          } 
          $row = mysql_fetch_assoc($result);
          mysql_free_result($result);
          if(empty($row)){
           return NULL;
          }
          foreach($fields as $field){
           $this->$field = $row[$field];
          }
          return $this;
       }
       public function __findByExact($field,$value,$return_fields=""){

          if((!empty($return_fields)) && is_array($return_fields)){
             $pk = $this->__primary_key();
             if(in_array($pk,$return_fields)){
               $fields = $return_fields;
             }else{
              array_push($return_fields,$pk);
              $fields = $return_fields;
             }
          }else{
             $fields = $this->__fields();
          }
          $fieldified = $this->__fieldify($fields);
          $query = $this->select_query($fieldified)." where `$field` = '$value'";

          $result = $this->__dbquery($query);
          if(!$result){
           $this->modelError("Error on finding");
          }
          $rarray = array();
          while($row = mysql_fetch_assoc($result)){
            array_push($rarray,$row);
          }
          mysql_free_result($result); 
          if(empty($row)){
           return $rarray; // 'll be replaced with Exceptions
          }
          return $rarray;
       }

       public function __findByLike($field,$value,$return_fields=""){
            if((!empty($return_fields)) && is_array($return_fields)){
             $pk = $this->__primary_key();
             if(in_array($pk,$return_fields)){
               $fields = $return_fields;
             }else{
              array_push($return_fields,$pk);
              $fields = $return_fields;
             }
          }else{
             $fields = $this->__fields();
          }
          $fieldified = $this->__fieldify($fields);
          $query = $this->select_query($fieldified)." where `$field` like  '%$value%'";
          $result = $this->__dbquery($query);
          if(!$result){
           $this->modelError("Error on finding");
          }
          $rarray = array();
          while($row = mysql_fetch_assoc($result)){
            array_push($rarray,$row);
          }
          mysql_free_result($result); 
          if(empty($row)){
           return $rarray; // 'll be replaced with Exceptions
          }
          return $rarray;
       }

      //@user functions no underscores :P

      public function save($field_as_array){
	 $__pk = $this->__primarykey();
	 if( __ispk_auto_increment($__pk)){
	  throw new Exception("Use Function save_insert for insert | save_update for update");exit;
	 }
         if(is_array($field_as_array)){
	  $fkeys = array_keys($field_as_array);
          foreach($fkeys as $fkey)
          {
	    $value = $field_as_array[$fkey];
 	    $this->__set($fkey,$value);
          }
         }
         if(isset($this->$__pk)){
             $this->__update("save");
         }else{
             $this->__insert("save");
         }
      }

      public function save_insert($field_as_array){
	if(is_array($field_as_array)){
	  $fkeys = array_keys($field_as_array);
          foreach($fkeys as $fkey)
          {
	    $value = $field_as_array[$fkey];
 	    $this->__set($fkey,$value);
          }
        }
	$this->__insert("save_insert");
      }

      public function save_update($field_as_array){
	if(is_array($field_as_array)){
	  $fkeys = array_keys($field_as_array);
          foreach($fkeys as $fkey)
          {
	    $value = $field_as_array[$fkey];
 	    $this->__set($fkey,$value);
          }
        }
        $this->__update("save_update");
      }

      public function delete(){
       $pk = $this->__primarykey();
       if(isset($this->$pk)){
         $pk_value = $this->$pk;
         $query = $this->delete_query()." where `$pk` = '$pk_value'";
         $result = $this->__dbquery($query);
         if(!$result){
           $this->modelError("Error Occured on Delete");
         }
       }
      }

      public function delete_where($field,$value){
       $field_flag = ((!empty($field)) && is_array($field));
       $value_flag = ((!empty($field)) && is_array($field));
       $must_delete = ($field_flag && $value_flag);
       if($must_delete){
         $field_field = $field;
         $field_value = $value;
         $query = $this->delete_query()." where `$field_field` = '$field_value'";
         $result = $this->__dbquery($query);
         if(!$result){
           $this->modelError("Error Occured on Delete");
         }
       }
      }

      public function query($query_string){
	return $this->__dbquery($query_string);
      }

      public function findAll($return_fields=""){
       if((!empty($return_fields)) && is_array($return_fields)){
             $fields = $return_fields;
             $fieldified = $this->__fieldify($return_fields);
       }else{
             $fields = $this->__fields();
             $fieldified = $this->__fieldify($fields);
       }
       
       $query = $this->select_query($fieldified);
       $result = $this->__dbquery($query);
       if(!$result){
           $this->modelError("Error Occured on Delete");
       }
       $rarray = array();
       while($row = mysql_fetch_assoc($result)){
            array_push($rarray,$row);
       } 
       mysql_free_result($result); 
       if(empty($row)){
           return $rarray; // 'll be replaced with Exceptions
       }
       return $rarray;   
      }

    
     public function filter_and($field_values){
         $fields = array_keys($field_values);
         $table_fields = $this->__fieldify($this->__fields());
         $field_query = $this->select_query($table_fields)." where ";
         foreach($fields as $field){
            $field_query .= " ".$field."='".$field_values[$field]."' and"; 
         }
         $field_query = rtrim($field_query,"and");
         
         $result = $this->__dbquery($field_query);
         if(!$result){
           $this->modelError("Error Occured on AND filter");
         }
         $rarray = array();
         while($row = mysql_fetch_assoc($result)){
            array_push($rarray,$row);
        } 
        mysql_free_result($result); 
        if(empty($row)){
           return $rarray; // 'll be replaced with Exceptions
        }
        return $rarray;  
     }
        //JSON Serialize & DeSerialize
      public function as_json($return_fields=''){
	if(is_array($return_fields)){
	 $raw_datas = $return_fields;
	}else{
	 $raw_datas  = $this->__fields();
	}
        $json_data = array();
        foreach($raw_datas as $raw_data){
          $json_data[$raw_data] = $this->$raw_data;
	}
        return json_encode($json_data);
      }
    
      public function dump_json($return_fields=''){
       header("Content-type: application/json");
	   if(is_array($return_fields)){
	    $raw_datas = $return_fields;
	   }else{
	    $raw_datas = '';
	   }
          $json = $this->as_json($raw_datas);
          echo $json;
      }
    
      public function load_json($json_string){
          $json_as_array = json_decode($json_string,true);
          $json_as_array_keys = array_keys($json_as_array);
          foreach($json_as_array_keys as $jsonelement){
           if($this->__isfield($jsonelement)){
	    $this->$jsonelement = $json_as_array[$jsonelement];
	   }
          }
      }


}

?>
