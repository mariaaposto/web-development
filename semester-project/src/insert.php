<?php
// Maria Apostolopoulou 1117202100016
// insert.php

session_start(); // ξεκινάμε session
if (!isset($_SESSION['username'])) header("Location: login.html"); // αν δεν υπάρχει login, επιστροφή

// παίρνουμε από session τον χρήστη, τη βάση και τον πίνακα
$user  = $_SESSION['username'];
$db    = $_SESSION['database'];
$table = $_SESSION['table'];
?>

<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>εισαγωγή</title></head>
<body>
<h3>εισαγωγή στον πίνακα <?php echo $table; ?></h3>

<!-- φόρμα εισαγωγής -->
<form method="POST">
<?php if ($table == "Authors"): ?>
    <!-- πεδία για τον πίνακα Authors -->
    όνομα:<input name="name"><br>
    επίθετο:<input name="surname"><br>
    εθνικότητα:<input name="nationality"><br>
    ημερομηνία:<input type="date" name="dateofbirth"><br>
<?php else: ?>
    <!-- πεδία για τον πίνακα Books -->
    τίτλος:<input name="title"><br>
    id συγγραφέα:<input name="author_id"><br>
    εκδότης:<input name="publisher"><br>
    έτος:<input name="year"><br>
<?php endif; ?>
    <br><input type="submit" value="εισαγωγή">
</form>

<?php
// αν υποβλήθηκε η φόρμα
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // σύνδεση στη βάση με στοιχεία από session
    $conn = new mysqli("localhost", $user, "1234", $db);
    if ($conn->connect_error) die("σφάλμα");

    // προετοιμασία ερωτήματος για Authors
    if ($table == "Authors") {
        $stmt = $conn->prepare("INSERT INTO Authors (name, surname, nationality, dateofbirth) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $_POST['name'], $_POST['surname'], $_POST['nationality'], $_POST['dateofbirth']);
    } else {
        // προετοιμασία ερωτήματος για Books
        $stmt = $conn->prepare("INSERT INTO Books (title, author_id, publisher, year) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sisi", $_POST['title'], $_POST['author_id'], $_POST['publisher'], $_POST['year']);
    }

    // εκτέλεση και μήνυμα
    echo $stmt->execute() ? "επιτυχία" : "λάθος";
}
?>

<!-- κουμπιά για νέα ενέργεια ή αποσύνδεση -->
<br><br>
<form action="menu.php"><input type="submit" value="νέα ενέργεια"></form>
<form action="logout.php"><input type="submit" value="logout"></form>
</body>
</html>
