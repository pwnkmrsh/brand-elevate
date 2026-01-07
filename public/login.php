<?php include "../themes/include/header.php";
require "../config/google/google-config.php";

$googleLoginUrl = $client->createAuthUrl();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $email = $_POST['email'];
    $pass  = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($pass, $user['password'])) {
        echo "Invalid email or password!";
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $pdo->prepare("UPDATE users SET last_seen=NOW() WHERE id=?")->execute([$user['id']]);

    header("Location: dashboard.php");
    exit;
}
?>
<main class="main">

    <!-- Page Title -->
    <div class="page-title" data-aos="fade" style="background-image: url(<?php echo BASE_URL; ?>/assets/img/page-title-bg.webp);">
        <div class="container position-relative">
            <h1>Welcome!
            </h1>
            <p>Start using Brand Elevate for yourself or your team</p>
        </div>
    </div>

    <!-- Starter Section Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2 class="text-gradient">Join today.</h2>
            <a href="<?= $googleLoginUrl ?>">
                <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png">
            </a>
            <br>
            <br>
            <br>
            <br>
            OR
        </div><!-- End Section Title -->

        <div class="container">
            <div class="row justify-content-md-center">

                <div class="card col-lg-4">
                    <div class="card-body">
                        <h5 class="card-title">Login with username or email</h5>

                        <form>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1">
                            </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        <p class="card-text">Don’t have an account?
                            <a href="#">Sign Up</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </section><!-- /Starter Section Section -->



</main>

<?php include "../themes/include/footer.php"; ?>

</body>

</html>