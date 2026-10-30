<?php
// Maria Apostolopoulou 1117202100016
// logout.php

session_start();
session_destroy(); // διαγραφή όλων των session
header("Location: login.html"); // επιστροφή στη φόρμα login
exit();
?>
