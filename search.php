<?php
// Maria Apostolopoulou 1117202100016
// search.php

session_start(); // ξεκινάμε session
if (!isset($_SESSION['username'])) header("Location: login.html"); // αν δεν έχει γίνει login

// παίρνουμε στοιχεία από session
$user  = $_SESSION['username'];
$db    = $_SESSION['database'];
$table = $_SESSION['table'];

// πιθανά πεδία για κάθε πίνακα
$fields = [
    "Authors" => ["id", "name", "surname", "nationality", "dateofbirth"],
    "Books"   => ["id", "title", "author_id", "publisher", "year"]
];
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>αναζήτηση</title></head>
<body>
<h3>αναζήτηση στον πίνακα <?php echo $table; ?></h3>

<!-- φόρμα επιλογής πεδίου και όρου -->
<form method="POST">
    πεδίο:
    <select name="col"><?php foreach ($fields[$table] as $f) echo "<option>$f</option>"; ?></select><br><br>
    όρος:
    <input type="text" name="val"><br><br>
    <input type="submit" value="αναζήτηση">
</form>

<?php
// αν έγινε υποβολή της φόρμας
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // σύνδεση στη βάση με το όνομα χρήστη
    $conn = new mysqli("localhost", $user, "1234", $db);
    if ($conn->connect_error) die("σφάλμα");

    // παίρνουμε το πεδίο και τον όρο αναζήτησης
    $col = $_POST['col'];
    $val = "%" . $_POST['val'] . "%"; // προσθήκη % για LIKE

    // προετοιμασία και εκτέλεση ερωτήματος
    $stmt = $conn->prepare("SELECT * FROM $table WHERE $col LIKE ?");
    $stmt->bind_param("s", $val);
    $stmt->execute();
    $res = $stmt->get_result();

    // εμφάνιση αποτελεσμάτων σε πίνακα
    echo "<table border=1>";
    while ($r = $res->fetch_assoc()) {
        echo "<tr>";
        foreach ($r as $v) echo "<td>$v</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>

<!-- κουμπιά για νέα ενέργεια ή αποσύνδεση -->
<br><br>
<form action="menu.php"><input type="submit" value="νέα ενέργεια"></form>
<form action="logout.php"><input type="submit" value="logout"></form>
</body>
</html>
