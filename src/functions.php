<?php
session_start();
/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
     return rand(100000, 999999);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code): bool {
  $subject = "Your Verification Code";
  $message = "<p>Your verification code is: <strong>$code</strong></p>";
  $headers = "From: no-reply@example.com\r\n";
  $headers .= "Content-type: text/html; charset=UTF-8\r\n";

  return mail($email, $subject, $message, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
  $file = __DIR__ . '/registered_emails.txt';
  return file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX);
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
  $file = __DIR__ . '/registered_emails.txt';
  if (!file_exists($file)) return false;

  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));
  return file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL, LOCK_EX);
  
}

function verifyCode($email, $code):bool {
  return isset($_SESSION['codes'][$email]) && $_SESSION['codes'][$email] === $code;
}


/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
  $max = 2800;
  $randomId = rand(1, $max);
  $url = "https://xkcd.com/$randomId/info.0.json";

  $json = file_get_contents($url);
  if ($json === false) return "<p>Could not fetch XKCD comic.</p>";

  $data = json_decode($json, true);
  $img = htmlspecialchars($data['img'] ?? '');
  return "<h2>XKCD Comic</h2>
          <img src=\"$img\" alt=\"XKCD Comic\">
          <p><a href=\"http://localhost/rtcamp-assignment/xkcd-ankita-m501/src/unsubscribe.php\" id=\"unsubscribe-button\">Unsubscribe</a></p>";
}

/**
 * Send the formatted XKCD updates to registered emails.
 */
function sendXKCDUpdatesToSubscribers(): void {
 $file = __DIR__ . '/registered_emails.txt';
  if (!file_exists($file)) return;

  $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $subject = "Your XKCD Comic";
  $message = fetchAndFormatXKCDData();
  $headers = "From: no-reply@example.com\r\n";
  $headers .= "Content-type: text/html; charset=UTF-8\r\n";

  foreach ($emails as $email) {
      mail(trim($email), $subject, $message, $headers);
  }
}


  function sendUnsubscribeEmail(string $email, string $code): bool {
  $subject = "Confirm Un-subscription";
  $message = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
  $headers = "From: no-reply@example.com\r\n";
  $headers .= "Content-type: text/html; charset=UTF-8\r\n";

  return mail($email, $subject, $message, $headers);
}
