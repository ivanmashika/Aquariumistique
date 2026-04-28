<?php
session_start();
session_destroy();
header('Location: profile.php?msg=logged_out');
exit;
?>  