<?php 
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "Email already exists!";
        exit;
    }

    $q = $pdo->prepare("INSERT INTO users (name,email,password,created_at) VALUES (?,?,?,NOW())");
    $q->execute([$name, $email, $pass]);

    echo "Registration successful. <a href='login.php'>Login</a>";
}
?>

<form method="post">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button>Register</button>
</form>
