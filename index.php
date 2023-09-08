<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration Form in HTML and CSS | Codehal</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    
    <?php
session_start();
include("koneksi.php");

// Mengecek apakah ada blokir login
if (isset($_SESSION["login_blocked"]) && $_SESSION["login_blocked"] > time()) {
    $remainingTime = $_SESSION["login_blocked"] - time();
    $error = "Anda telah mencapai batas maksimum percobaan login. Silakan coba lagi setelah " . gmdate("i:s", $remainingTime) . ".";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST['password']);

    // Query untuk memeriksa data login
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION["username"] = $username;
        header("location: dashboard.php");
        exit();
    } else {
        $_SESSION["login_attempts"] = ($_SESSION["login_attempts"] ?? 0) + 1;
        if ($_SESSION["login_attempts"] >= 3) {
            $_SESSION["login_blocked"] = time() + 1800; // Blokir login selama 30 menit (1800 detik)
            $error = "Anda telah mencapai batas maksimum percobaan login. Silakan coba lagi setelah 30 menit.";
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>
    <script src="script.js"></script>
</body>

</html>