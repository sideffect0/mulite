<?php
 require_once("pathconf.php");
 require_once(INCLUDEDIR."forms.php");
 require_once(HELPDIR."forms.CharField.php");
 class SubmitForm extends Form{
  public $data;   //field name
  public function __construct($request){
   parent::__construct($request);
   $this->data = new CharField($max_length=10);
  }
 }
?>
