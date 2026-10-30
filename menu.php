<?php
// Maria Apostolopoulou 1117202100016
// menu.php

session_start();

// έλεγχος αν έχει γίνει login
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// παίρνουμε στοιχεία από session
$user = $_SESSION['username'];
$db   = $_SESSION['database'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Μενού</title>
</head>
<body>
    <h2>καλώς ήρθες <?php echo $user; ?></h2>
    <p>έχεις συνδεθεί στη βάση: <strong><?php echo $db; ?></strong></p>

    <!-- φόρμα για επιλογή δράσης -->
    <form action="action.php" method="POST">
        <!-- κρυφά πεδία για να μεταφέρουμε στοιχεία -->
        <input type="hidden" name="username" value="<?php echo $user; ?>">
        <input type="hidden" name="database" value="<?php echo $db; ?>">

        <!-- επιλέγει δράση -->
        <label>επίλεξε ενέργεια:</label><br>
        <select name="action">
            <option value="search">αναζήτηση</option>
            <option value="insert">εισαγωγή</option>
        </select><br><br>

        <!-- επιλέγει πίνακα -->
        <label>επίλεξε πίνακα:</label><br>
        <select name="table">
            <option value="Authors">Authors</option>
            <option value="Books">Books</option>
        </select><br><br>

        <input type="submit" value="προχώρα">
    </form>

    <br>
    <!-- κουμπί logout -->
    <form action="logout.php" method="POST">
        <input type="submit" value="αποσύνδεση">
    </form>
</body>
</html>
