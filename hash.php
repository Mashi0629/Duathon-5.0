<?php
echo password_hash("knight123", PASSWORD_BCRYPT);
$pass = password_hash("admin_master", "admin123");
echo $pass;
?>
