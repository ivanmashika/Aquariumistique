<?php
#Выход из профиля
session_start();
session_destroy();
header('Location: profile.php?msg=logged_out');
exit;
?>  