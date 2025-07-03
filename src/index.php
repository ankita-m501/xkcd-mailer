<?php
    require_once 'functions.php';
    $message = "";
    $messageType= "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $code = generateVerificationCode();
        $_SESSION['codes'][$email] = $code;

        sendVerificationEmail($email, $code);
        $message = "Verification code sent to $email. Please check your email.";
        $messageType = "success";


    } elseif (isset($_POST['verification_code']) && isset($_POST['verify_email'])) {
        $email = trim($_POST['verify_email']);
        $code = trim($_POST['verification_code']);

        if (verifyCode($email, $code)) {
            registerEmail($email);
            $message= "Email $email has been verified and registered!";
            $messageType = "success";

        } else {
            $message = "Invalid code!";
            $messageType = "error";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XKCD Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php if ($message): ?>
        <p class="<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    
    <form method="POST">
        <h3>Register</h3>
        <h5>Enter email</h5>
        <input type="email" name="email" required>
        <button id="submit-email">Submit</button>
    </form>

    <form method="POST">
        <input type="hidden" name="verify_email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <input type="text" name="verification_code" maxlength="6" placeholder="Enter verification code" required>
        <button id="submit-verification">Verify</button>
    </form> 
</body>
</html>

