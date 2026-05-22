<?php
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_role"] = $role;

            if ($role === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}

include 'header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="glass-panel p-5 w-100" style="max-width: 450px;">
        <h2 class="text-center mb-4" style="color: #00c6ff; text-transform: uppercase; letter-spacing: 2px;">Login</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="background: rgba(220,53,69,0.2); color: #ff6b6b; border: 1px solid #dc3545;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label" style="color: #00c6ff;">Email</label>
                <input type="email" name="email" class="form-control py-2" required placeholder="Enter your email" />
            </div>
            <div class="mb-4">
                <label class="form-label" style="color: #00c6ff;">Password</label>
                <input type="password" name="password" class="form-control py-2" required placeholder="Enter your password" />
            </div>
            <button type="submit" class="btn btn-custom w-100 py-2 fs-5">Login</button>
        </form>
        <p class="mt-4 text-center">Don't have an account? <a href="signup.php" style="color: #00c6ff; font-weight: bold; text-decoration: none;">Sign up here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>
