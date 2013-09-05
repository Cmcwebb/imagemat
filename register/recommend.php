<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Recommend to a friend');
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php
bodyHeader();

$email   = getpost('email');
$message = getpost('message');

if (isset($email)) {
  $remote = $_SERVER['REMOTE_ADDR'];

  require_once($dir . '/../include/users.php');

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '
<br><div class=error>Email "' , htmlspecialchars($email) , '" appears invalid</div>';
  } else {
    $body = '';
    if (isset($_SESSION['imageMAT_firstname']) || isset($_SESSION['imagemat_lastname'])) {
      if (isset($_SESSION['imageMAT_firstname'])) {
        $body = $_SESSION['imageMAT_firstname'] . ' ';
      }
      if (isset($_SESSION['imageMAT_lastname'])) {
        $body .= $_SESSION['imageMAT_lastname'] . ' ';
      }
    } else {
      $body = 'Somebody ';
    }
    if (isset($_SESSION['imageMAT_user_id'])) {
      $body .= '(' . $_SESSION['imageMAT_user_id'];
      if (isset($_SESSION['imageMAT_moniker'])) {
        if ($_SESSION['imageMAT_user_id'] != $_SESSION['imageMAT_moniker']) {
          $body .= '|' . $_SESSION['imageMAT_moniker'];
      } }
      $body .= ') ';
    }
    if (isset($_SESSION['imageMAT_email'])) {
      $body .= 'having email ' . $_SESSION['imageMAT_email'] . ' ';
    }

    $body .= 'recommends this annotation tool:

http://mat.uwaterloo.ca/imagemat/register/register.php.

They suggest you check out this website.

' . $message;

    $ret = mail($email, 'Have you seen this new annotation tool', $body,
emailHeader());
    if (!$ret) {
      echo '
<br><div class=error>Sorry but we were unable to send the email.' , contact(), '</div>';
      goto done;
    }
    echo '
<p>
Thank you for recommending us to your friend.';
    goto done;
} }

?>
<h3>Email Recommendation</h3>
<p>
Please provide the email address you would like to recommend this tool to. An optional message may also be added.
<p>
<form action="recommend.php" method="post">
<table>
<tr><td align=right>Email:</td>
<td>
<input type=text id=email name=email size=50 maxlength=255 value="<?php echo htmlspecialchars($email); ?>" />
</td>
</tr>
<tr>
<td align=right>Optional<br>Message:</td>
<td>

<textarea name="message" cols="80" rows="10"><?php echo htmlspecialchars($message); ?></textarea>
</td>
</td>
<tr>
<td>
</td>
<td>
<input type=submit name=send value=Send /><input type=reset />
</td>
</tr>
</table>
</form>

<?php
done:
bodyFooter();
?>
</body>
</html>

