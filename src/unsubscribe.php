<?php
require_once 'functions.php';

$message="";
$messageType ="";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        $code = generateVerificationCode();
        $_SESSION['codes'][$email] = $code;

        sendUnsubscribeEmail($email, $code);
        $message= "Unsubscription code sent to $email.";
        $messageType= "success";

    } elseif (isset($_POST['verification_code']) && isset($_POST['verify_email'])) {
        $email = trim($_POST['verify_email']);
        $code = trim($_POST['verification_code']);

        if (verifyCode($email, $code)) {
            unsubscribeEmail($email);
            $message= "You have been unsubscribed.";
            $messageType= "success";
        } else {
            $message= "Invalid code!";
            $messageType= "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XKCD Unsubscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php if ($message): ?>
        <p class="<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <h3>Unsubscribe</h3>
        <h5>Enter email</h5>
        <input type="email" name="unsubscribe_email" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <form method="POST">
        <input type="hidden" name="verify_email" value="<?php echo isset($_POST['unsubscribe_email']) ? htmlspecialchars($_POST['unsubscribe_email']) : ''; ?>">
        <input type="text" name="verification_code" maxlength="6" placeholder="Enter verification code" required>
        <button id="submit-verification">Verify</button>
    </form> 
</body>
</html>


