<?php
// Maria Apostolopoulou 1117202100016
// login.php

session_start();

// σύνδεση στη βάση με root
$login_conn = new mysqli("localhost", "root", "", "web_development_login");
if ($login_conn->connect_error) die("σφάλμα σύνδεσης");

// παίρνουμε τα στοιχεία
$user = $_POST['username'];
$pass = $_POST['password'];
$db   = $_POST['database'];

// αν λείπουν στοιχεία
if (!$user || !$pass) die("συμπλήρωσε όλα τα πεδία");

// ψάχνουμε τον χρήστη (prepared statement)
$q = $login_conn->prepare("SELECT * FROM Users WHERE username=?");
$q->bind_param("s", $user);
$q->execute();
$res = $q->get_result();

if (!$res->num_rows) die("ο χρήστης δεν υπάρχει");

$row = $res->fetch_assoc();
$t = time(); // timestamp

// έλεγχος για μπλοκάρισμα (3 λάθος προσπάθειες -> 30 δευτερόλεπτα)
if ($row['attempts'] >= 3 && $t - $row['last_attempt'] < 30)
    die("μπλοκαρισμένος για 30 δευτερόλεπτα");

// αν σωστός κωδικός
if (password_verify($pass, $row['password'])) {
    $login_conn->query("UPDATE Users SET attempts=0, last_attempt=0 WHERE username='$user'");
    $_SESSION['username'] = $user;
    $_SESSION['database'] = $db;
    header("Location: menu.php");
    exit();
}

// αν λάθος κωδικός
$login_conn->query("UPDATE Users SET attempts=attempts+1, last_attempt=$t WHERE username='$user'");
echo "λάθος κωδικός";
?>
