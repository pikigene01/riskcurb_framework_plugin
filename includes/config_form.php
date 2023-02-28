<?php
 $html = "";
 $html .= " 

 <form method='POST'>
 <label for='host'>Host</label><br>
 <input type='text' id='host' name='host' value='localhost'><br>
 <label for='user'>User:</label><br>
 <input type='user' id='user' value='root' name='user'/><br>
 <label for='password'>password:</label><br>
 <input type='text' id='password' name='password' value='password'/><br><br/>
 <input class='btn btn-success' name='save_database_config' type='submit' value='Save Credentials'>
</form>";

  $html .= "";
  echo $html;

  if(isset($_POST['save_database_config'])){

    $databasename = $_POST['name'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $host = $_POST['host'];

    $con = mysqli_connect($host,$user,$password);
    if($con){
    $xml = new DOMDocument('1.0');
    $xml->formatOutput = true;

    $config = $xml->createElement('config');
    $xml->appendChild($config);

    $host = $xml->createElement('host',$host);
    $config->appendChild($host);
    $user = $xml->createElement('user',$user);
    $config->appendChild($user);
    $password = $xml->createElement('password',$password);
    $config->appendChild($password);
    $db_name = $xml->createElement('dbname', $databasename);
    // $db_name->setAttribute("gene", 'jfsjfvs');
    $config->appendChild($db_name);

   echo "<xmp>".$xml->saveHTML()."</xmp>";
   $config_name = "config";
   $filename = __DIR__.'/'. $config_name . ".xml";
   
   if(
 $xml->save($filename)){
  echo "<script>window.location = window.location;</script>";
 }else{
    echo "failed to save file";
    die("Error unable to create a file");
 }
}else{//else for checking database connection
    echo "Error connecting to connect to your database please insert correct credentials";
}

 }
?>