<?php
// Ονοματεπώνυμο: Μαρία Αποστολοπούλου
// Αριθμός μητρώου: 1117202100016

// έναρξη session για αποθήκευση πληροφοριών μεταξύ σελίδων
session_start();

// ελέγχουμε αν έχουν οριστεί οι προσπάθειες, αλλιώς τις αρχικοποιούμε σε 3
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 3;
}

// συνάρτηση που ελέγχει τον κωδικό ανάλογα με τη μέθοδο
function checkPassword($password, $email, $method) {
    switch ($method) {
        case 'double':  // ο κωδικός πρέπει να είναι το email διπλασιασμένο
            return $password === $email . $email;
        case 'reverse': // ο κωδικός πρέπει να είναι το email ανάποδα
            return $password === strrev($email);
        case 'array':   // ο κωδικός πρέπει να είναι ένας από μια λίστα έγκυρων
            $valid_passwords = ['pass123', '123456', 'admin'];
            return in_array($password, $valid_passwords);
        default:
            return false;
    }
}

// μεταβλητές εμφάνισης μηνύματος και flag για εμφάνιση φόρμας
$message   = '';
$remaining = $_SESSION['attempts'];
$show_form = true;

// αν στάλθηκε φόρμα με POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // αν ο χρήστης πάτησε "Start again", γίνεται reset
    if (isset($_POST['reset'])) {
        $_SESSION['attempts'] = 3;
        header("Location: login.php"); // ανακατεύθυνση για καθαρή φόρμα
        exit();
    }

    // παίρνουμε τα δεδομένα από τη φόρμα
    $email    = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $method   = $_POST["method"] ?? '';

    // έλεγχος αν έχουν συμπληρωθεί και τα δύο πεδία
    if (empty($email) || empty($password)) {
        $message = "Please give both username and password.";
    } elseif ($_SESSION['attempts'] > 0) {
        // έλεγχος αν ο κωδικός είναι σωστός
        if (checkPassword($password, $email, $method)) {
            $message = "Login credentials given. Authenticating...<br><br>" .
                       "<span style='font-size: 1.5em; font-weight: bold;'>Authentication is completed</span>";
            $show_form = false; // η φόρμα δεν χρειάζεται να φανεί
        } else {
            $_SESSION['attempts']--;
            $remaining = $_SESSION['attempts'];

            if ($remaining == 0) {
                // τελική αποτυχία (3 προσπάθειες)
                $message = "Login credentials given. Authenticating...<br>" .
                           "Invalid login credentials given for 3 times<br><br>" .
                           "Authentication is NOT correct";
                $show_form = false;
            } else {
                // αποτυχία με υπόλοιπες προσπάθειες
                $times_failed = 3 - $remaining;
                $message = "Login credentials given. Authenticating...<br>" .
                           "Invalid login credentials given for $times_failed times";
            }
        }
    }
}
?>

<!DOCTYPE html> <!-- δηλώνει ότι το αρχείο είναι σε μορφή HTML5 -->
<html lang="en"> <!-- καθορίζει τη γλώσσα της σελίδας -->
<head>
    <meta charset="UTF-8"> <!-- χαρακτήρες unicode -->
    <title>Assignment2</title> <!-- τίτλος σελίδας -->
</head>
<body> <!-- αρχή σώματος της σελίδας -->

<!-- αν υπάρχει κάποιο μήνυμα (success ή error), το εμφανίζουμε -->
<?php if (!empty($message)): ?>
    <?php
    $is_bold = $message === "Please give both username and password.";
    $output = $is_bold ? "<strong>$message</strong>" : $message;
    ?>
    <p><?= $output ?></p>
<?php endif; ?>

<!-- εμφάνιση του τίτλου Login πάνω αριστερά μόνο αν φαίνεται η φόρμα -->
<?php if ($show_form): ?>
    <div style="text-align: left; font-size: 26px; font-weight: bold; margin-bottom: 20px;">
        Login
    </div>
<?php endif; ?>

<!-- αν ο χρήστης έχει κάνει 3 αποτυχίες, εμφανίζουμε το κουμπί Start again -->
<?php if (!$show_form && $_SESSION['attempts'] == 0): ?>
    <form method="post">
        <button type="submit" name="reset">Start again</button>
    </form>

<!-- αν πέτυχε το login, δεν εμφανίζουμε τη φόρμα -->
<?php elseif (!$show_form): ?>
    <!-- no form needed if success -->

<!-- αλλιώς εμφανίζουμε τη φόρμα -->
<?php else: ?>
    <form method="post">
        <!-- πεδίο email -->
        <label for="email">Email address</label><br>
        <input type="email" name="email" id="email"><br><br>

        <!-- πεδίο password με εικονίδιο για αποκάλυψη -->
        <label for="password">Password</label><br>
        <div style="position: relative; width: fit-content;">
            <input type="password" name="password" id="password"
                   style="padding-right: 25px; height: 22px; box-sizing: border-box;">
            <img src="https://img.icons8.com/ios/15/hide.png"
                 alt="toggle visibility"
                 onclick="togglePassword()"
                 style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer; user-select: none;">
        </div><br>

        <!-- επιλογή μεθόδου ελέγχου κωδικού -->
        <label for="method">Password checking function</label><br>
        <select name="method" id="method">
            <option value="double">Double email</option>
            <option value="reverse">Reverse email</option>
            <option value="array">Password in array</option>
        </select><br><br>

        <!-- εμφάνιση υπόλοιπων προσπαθειών -->
        <label for="remaining">Login attempts remaining</label><br>
        <input type="text" id="remaining" value="<?= $_SESSION['attempts'] ?>" readonly><br><br>

        <!-- κουμπί υποβολής -->
        <button type="submit">Login</button>
    </form>
<?php endif; ?>

<!-- javascript που εναλλάσσει την εμφάνιση του password -->
<script>
function togglePassword() {
    const pw = document.getElementById("password");
    pw.type = pw.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
