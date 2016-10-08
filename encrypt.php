<?php

function Encrypt($password, $data)
{
    $salt = substr(md5(mt_rand(), true), 8);

    $key = md5($password . $salt, true);
    $iv  = md5($key . $password . $salt, true);

    $ct = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);

    return base64_encode('Salted__' . $salt . $ct);
}

$config = parse_ini_file('config.php');

if($config['password'] == '')
{
  echo "Nothing provided to Encrypt";
}
else
{
$test1 = Encrypt($config['variable1'],$config['password']);
echo "Copy and paste the below Encrypted Password in the 'config' file as 'password2 = [your_Encrypted_String]'</br></br>";
echo $test1;
}

?>
