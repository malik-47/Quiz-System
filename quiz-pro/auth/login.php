<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quiz Pro</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Login to Quiz Pro</h2>
        <?php
        session_start();
        include "../config/db.php";
        
        /* ONE-TIME DB MIGRATION: Run in MySQL/phpMyAdmin
        ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user';
        UPDATE users SET role = 'admin' WHERE email = 'admin@example.com'; -- if exists
        */

        if(isset($_POST['signup'])) {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            
            if (strlen($password) < 6) {
                echo '<p style="color: #e53e3e; margin-bottom: 20px;">Password must be at least 6 characters</p>';
            } else {
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    echo '<p style="color: #e53e3e; margin-bottom: 20px;">Email already registered</p>';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
                    if ($stmt->execute([$username, $email, $hashedPassword])) {
                        echo '<p style="color: #48bb78; margin-bottom: 20px;">Account created! You can now login.</p>';
                    } else {
                        echo '<p style="color: #e53e3e; margin-bottom: 20px;">Signup failed. Try again.</p>';
                    }
                }
            }
        }
        
        if(isset($_POST['login'])){
            $stmt=$pdo->prepare("SELECT * FROM users WHERE email=?");
            $stmt->execute([$_POST['email']]);
            $user=$stmt->fetch();

            if($user && password_verify($_POST['password'],$user['password'])){
                $_SESSION['user']=$user['id'];
                header("Location: ../user/dashboard.php");
                exit;
            } else {
                echo '<p style="color: #e53e3e; margin-bottom: 20px;">Invalid Login Credentials</p>';
            }
        }
        ?>
        
        <!-- Login Form -->
        <form id="loginForm" method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
        
        <!-- Signup Form -->
        <form id="signupForm" method="POST" style="display: none;">
            <div class="form-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password (min 6 chars)" required minlength="6">
            </div>
            <button type="submit" name="signup">Create Account</button>
        </form>
        <div style="margin-top: 30px;">
            <button onclick="toggleForm()" id="toggleBtn" style="background: none; color: #667eea; border: none; font-size: 1rem; cursor: pointer; text-decoration: underline;">No account? Sign up</button>
        </div>
    </div>
    
    <script>
        function toggleForm() {
            const loginForm = document.getElementById('loginForm');
            const signupForm = document.getElementById('signupForm');
            const toggleBtn = document.getElementById('toggleBtn');
            
            if (loginForm.style.display === 'none') {
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
                toggleBtn.textContent = 'No account? Sign up';
                document.querySelector('h2').textContent = 'Login to Quiz Pro';
            } else {
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
                toggleBtn.textContent = 'Have account? Login';
                document.querySelector('h2').textContent = 'Create New Account';
            }
        }
    </script>
</body>
</html>
