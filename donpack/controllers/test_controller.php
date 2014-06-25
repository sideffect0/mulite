<?php
class testController{
 public function check(){
  import_model("test");
  $test_ob = new Test();
  $test_ob->save(array(
   "name"=>"hello"
  ));
 }
}
?>
