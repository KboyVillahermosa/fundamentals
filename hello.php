<?php
require 'config.php';

// Check if user is already logged in
if (!empty($_SESSION["id"])) {
    header("Location: index.php");
    exit(); // Terminate script execution after redirection
}

// Initialize errors array
$errors = [];

// Check if form is submitted
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $confirmpassword = password_hash($_POST["confirmpassword"], PASSWORD_DEFAULT);

    // Check for username or email duplication
    $duplicate = mysqli_query($conn, "SELECT * FROM login_data WHERE username = '$username' OR email = '$email'");
    if (mysqli_num_rows($duplicate) > 0) {
        $errors[] = "Username or Email Has Already Been Taken";
    } else {

       // Check if passwords match
       if ($_POST["password"] === $_POST["confirmpassword"]) {
        // $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Remove password hashing
        $password = $_POST["password"]; // Store plain text password
        $query = "INSERT INTO login_data VALUES('', '$name', '$username', '$email', '$password')";
        mysqli_query($conn, $query);
        $_SESSION['registration_success'] = true;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit(); 
    } else {
        $errors[] = "Password Does Not Match!";
    }
    

    }
}
// Check if registration success session is set and unset it
$registration_success = isset($_SESSION['registration_success']) && $_SESSION['registration_success'];
unset($_SESSION['registration_success']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Registration</title>
    <link rel="shortcut icon" href="images/logow.png" type="image/x-icon">
    <link rel="stylesheet" href="style/registrationn.css">
</head>

<body>
    <div class="container">
        <div class="image-form">
            <img src="images/re.avif" alt="">
        </div>
        <div class="forms">
            <form class="form" action="" method="post" autocomplete="off">
                <h2 class="title">Registration</h2>
                <?php if (!empty($errors)) : ?>
                    <div class="error-message">
                        <?php foreach ($errors as $error) : ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <label for="name">Nickname</label>
                <input type="text" name="name" id="name" required value="" class="input-field"><br>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required value="" class="input-field"><br>
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required value="" class="input-field"><br>
                <div class="input-container">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required value="" class="input-field">
                    <label class="show-password-label" for="togglePassword">
                        <input type="checkbox" id="togglePassword">
                        Show Password
                    </label>
                </div>
                <div class="input-container">
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" name="confirmpassword" id="confirmpassword" required value="" class="input-field">
                    <label class="show-password-label" for="toggleConfirmPassword">
                        <input type="checkbox" id="toggleConfirmPassword">
                        Show Password
                    </label>
                </div>
                <button type="submit" name="submit" class="register">Register</button>
                <p class="signin">Already have an account? <a href="login.php">Log-In</a></p>
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordCheckbox = document.getElementById('togglePassword');
        const confirmPasswordInput = document.getElementById('confirmpassword');
        const toggleConfirmPasswordCheckbox = document.getElementById('toggleConfirmPassword');

        togglePasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });

        toggleConfirmPasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                confirmPasswordInput.type = 'text';
            } else {
                confirmPasswordInput.type = 'password';
            }
        });
        document.querySelector('.close-button').addEventListener('click', function() {
    document.getElementById('myModal').style.display = 'none';
});

<?php if ($registration_success) : ?>
    document.getElementById('myModal').style.display = 'block';
<?php endif; ?>

    </script>
</body>

</html>
