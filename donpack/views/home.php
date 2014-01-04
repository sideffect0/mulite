<?php
?>
 <!doctype html>
 <html>
 <head>
  <style>
   *{
     margin:0;
     padding:0;
   }
   h1.melite{
    font-family:"Arial";
    height:70px;
    border:solid 1px; 
    border-color:#722ede;
    color:#222;
    padding-left:10px;
   }
   span.melite_can{
    font-family:cursive;
    cursor:pointer;
    -webkit-transition:color 1s;
   }
   
   span.melite_can:hover{
    color:#ff0000;
   }
 
   div.doit{
    width:532px;
    margin-left:auto;
    margin-right:auto;
    margin-top:50px;
    box-shadow:0px 0px 5px #000;
    border-radius:5px;
    padding-bottom:10px;
    text-align:center;
    font-family:"Arial";
    font-weight:bold;
   }
   input.text{
    width:500px;
    height:20px;
    padding:5px;
    outline:none;
    padding:10px;
    margin:5px;
    align:center;
    background-color:#000;
    color:#aaff77;
    font-family:cursive;
    border:solid 1px #000;
   }

   button.submit{
    padding:5px;
    width:100px;
    cursor:pointer;
    background-color : #fff;
    border:solid 1px #000;
   }

   button.submit:hover{
    background-color:#00ff00;
   }
  </style>
 </head>
 <body>
 <h1 class='melite'><span class='melite_can'>&mu;lite </span>: it works !</h1>
  <form method="POST" action="/submitname/">
    <div class="doit">
     FORM EXAMPLE
     <input type="text" class="text" name='data' placeholder='enter your name (max 10 chars :p)' autofocus/>
     <button class='submit' type='submit'>submit</button>
    </div>
  </form>
 </body>
 </html>
