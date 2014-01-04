<?php
require_once("pathconf.php"); 
require_once(CONFIGDIR."facebook.php");
require_once(HELPDIR."session.php");
require_once(INCLUDEDIR."facebook/facebook.php");
//Tune FB SDK for Phocom.
class FBTuner{
 protected $appid;
 protected $appsecret;
 protected $facebook;
 protected $session;
 //initialise
 public function __construct(){
  $this->session = new Session(); 
  $this->appid = FBAPPID;
  $this->appsecret = FBAPPSECRET;
  $this->facebook = new Facebook(array(
   'appId' => $this->appid,
   'secret' => $this->appsecret,
   'fileUpload' => true
  ));
 }

 //Refresh with new App Credentials
 public function refresh(){
  $this->facebook = new Facebook(array(
   'appId' => $this->appid,
   'secret' => $this->appsecret
  ));
 }

//returns the logined user id,0 if not logged in
 public function getUserID(){
  $user_id = $this->facebook->getUser();
  return $user_id;
 }

//get user profile details as an array
 public function getUserProfile(){
  try{
    $user_profile = $this->facebook->api('/me');
  }
  catch(FacebookApiException $exception){
   die("FB Token Error");
  }
 }

//get User Login Url with Given Permissions
 public function getLoginURL($permissions){
  return $this->facebook->getLoginUrl($permissions);
 }

//get User Logout Url / Logout From Application
 public function getLogoutURL(){
  return $this->facebook->getLogoutUrl($facebook->getAccessToken());
 }

 //creates new album and returns album id
 public function createAlbum(){
  $album_name = "nbcz_album_".md5(microtime()).rand(); //a unique albumname
  $privacy = array(
     'value'=>'SELF'
   );
  $album = array(
    'message'=>$album_name,
    'name'=>$album_name,
    'privacy'=>$privacy
  );
  $result = $this->facebook->api('/me/albums','post',$album);
  return $result['id'];
 }

//returns the album image count / Check for Exceeded Album images
 public function getAlbumImageCount($album_id){
  $result = $this->facebook->api('/'.$album_id.'/?fields=count','get');
  return $result['count'];
 }

 //upload the pic to the album and returns photoid
 public function uploadPhoto($album_id,$pic){
  $photo = array(
   'message'=>"nbcz_photo_".md5(microtime()).rand()
  ); 
//lazy...easy..
  $photo['image'] = '@'.realpath($pic);
  $result = $facebook->api('/'.$album_id.'/photos', 'post', $photo);
  return $result['id'];
 }

//get Direct Url to the pic by id
 public function getPhotoURLByID($photo_id){
  $result = $facebook->api('/'.$photo_id.'/?fields=source', 'get', $photo);
  return $result['source'];
 }
}
?>
