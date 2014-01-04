<?
  require_once("pathconf.php");
  require_once(CONFIGDIR."helper.php");
  /*
     Use This Class For Sessions Got it ?? ;)
  */
  class Session{
    public function __construct(){
      session_name(SESSION_NAME);
      session_start();
    }

    // use Session object to set/get values

    public function getValue($field){
     return $_SESSION[$field];
    }
    public function setValue($field,$value){
     $_SESSION[$field] = $value;
    }
    public function destroy(){
     session_destroy();
    }
  }
?>
