<?php

echo password_hash("knight123", PASSWORD_BCRYPT) . "\n";

$pass = password_hash("admin_master", PASSWORD_BCRYPT);
echo $pass;

?>
