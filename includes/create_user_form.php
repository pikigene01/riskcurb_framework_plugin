<?php

$html = "";
$html .= " 
<div class='plugin_wrapper'>
<form method='POST'>
<label for='name'>Username</label><br>
<input type='text' id='username' name='name'><br>
<label for='password'>password:</label><br>
<input type='password' id='password' name='password'><br>
<label for='password'>repeat password:</label><br>
<input type='password' id='repeat_password' name='repeat_password'><br><br/>
<input class='btn btn-success' name='create_user' type='submit' value='create user'>
</form>
</div>
";

 $html .= "";
 echo $html;

?>