<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirmPassword"];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "User already exists. Please login.";
        } else {
            $stmt->close();
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $name, $email, $hashedPassword);
            
            if ($stmt->execute()) {
                $success = "Account created successfully! You can now <a href='login.php' style='color:#00c6ff;'>Login</a>.";
            } else {
                $error = "Signup failed: " . $stmt->error;
            }
        }
        if(isset($stmt)) $stmt->close();
    }
}

include 'header.php';
?>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="glass-panel p-5 w-100" style="max-width: 500px;">
        <h2 class="text-center mb-4" style="color: #00c6ff; text-transform: uppercase; letter-spacing: 2px;">Sign Up</h2>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="background: rgba(220,53,69,0.2); color: #ff6b6b; border: 1px solid #dc3545;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success" style="background: rgba(40,167,69,0.2); color: #28a745; border: 1px solid #28a745;"><?php echo $success; ?></div>
        <?php else: ?>
            <form action="signup.php" method="POST">
                <div class="mb-3">
                    <label class="form-label" style="color: #00c6ff;">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe" />
                </div>
                <div class="mb-3">
                    <label class="form-label" style="color: #00c6ff;">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="john@example.com" />
                </div>
                <div class="mb-3">
                    <label class="form-label" style="color: #00c6ff;">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Create a password" />
                </div>
                <div class="mb-4">
                    <label class="form-label" style="color: #00c6ff;">Confirm Password</label>
                    <input type="password" name="confirmPassword" class="form-control" required placeholder="Confirm your password" />
                </div>
                <button type="submit" class="btn btn-custom w-100 py-2 fs-5">Create Account</button>
            </form>
        <?php endif; ?>
        
        <p class="mt-4 text-center">Already have an account? <a href="login.php" style="color: #00c6ff; font-weight: bold; text-decoration: none;">Login here</a></p>
    </div>
</div>

<?php include 'footer.php'; ?>
