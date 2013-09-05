<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders.php');

echo HtmlHeader('Request change in Permissions') , '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

require_once($dir . '/../include/permissions.php');
require_once($dir . '/../include/groups.php');

$folder_id = get_folder_id();
if (!$folder_id) {
  goto close;
}
$getpath = getpath($folder_id);
if (!$getpath) {
  goto close;
}
$path   = $getpath['path'];
$folder_owner_id = $getpath['owner'];

echo '
<h3>Request permission change for folder ', htmlspecialchars($path), '</h3>';

if ($folder_id == 1) {
  echo '
<br>You may not request any specific access to the root folder';
  goto close;
}

$request  = getpost('request');
$contacts = getpost('contacts');

if (isset($request) && $request != '') {
  if (!isset($contacts)) {
    echo '
<font color=red>Please identify at least one contact</font>
<br>';
    goto retry;
  }
    
  $query = 
'select email
  from users
 where user_id = ' . DBstring($gUserid);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<br>You ', htmlspecialchars($gUserid), ' are not a user of ImageMAT!
<br>Please report this problem to ' . $gAdminEmail;
    goto close;
  }
  $requestor = $row['email'];
  # $header = emailHeader();
  $header =
'From: '     . $gEmailSender . '
Reply-To: ' . $requestor . '
X-Mailer: PHP/' . phpversion();

  $action   = getpost('action');
  $remote = $_SERVER['REMOTE_ADDR'];
  $email_sent = false;
  foreach($contacts as $contact_id => $value) {
    if ($contact_id != $folder_owner_id) {
      # Protect against fraud
      $member = resolve_member($contact_id, $folder_id, $folder_owner_id);
      if (!isset($member)) {
        goto close;
      }
      if (!$member) {
        echo '
<br>The user ', htmlspecialchars($contact_id), ' is not a member of ',
htmlspecialchars($path);
        continue;
      }
      if (!($member['folder_permissions'] & MANAGER)) {
        echo '
<br>The user ', htmlspecialchars($contact_id), ' is not a manager of ',
htmlspecialchars($path);
        continue;
    } }

    $query = 
'select email
  from users
 where user_id = ' . DBstring($contact_id);
    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    $row = DBfetch($ret);
    if (!$row) {
      echo '
<br>Contact ', htmlspecialchars($contact_id), ' is not a user of ImageMAT!
<br>Please report this problem to ' . $gAdminEmail;
      continue;
    }
    $email = $row['email'];
    $body = $action . '

The ImageMAT user ' . $gUserid . ' with e-mail address ' . $requestor . '
is contacting your from IP address ' . $remote . ' regarding
the ImageMAT folder ' . $path . ' that you ';
    if ($contact_id == $folder_owner_id) {
      $body .= 'own.';
    } else {
      $body .= 'administer';
    }
    $body .= '
== Request ==

' . $request;

    $ret = mail($email, 'ImageMAT request re: ' . $path, $body, $header);
    if (!$ret) {
      echo '
<br><font color=red>Sorry but we were unable to send email to ',
htmlspecialchars($contact_id), '</font>';
    } else {
      echo '
<br>Email sent to ', htmlspecialchars($contact_id);
      if ($contact_id == $folder_owner_id) {
        echo ' (Folder owner)';
      } else {
        echo ' (Folder manager)';
      }
      $email_sent = true;
  } }
  if ($email_sent) {
    goto close;
  }
  echo '
<br>No emails sent (please try again)';
}

retry:

$permit = permit_new_members($folder_id);
if (!isset($permit)) {
  goto close;
}
$action   = '';
$contacts = array();
switch ($permit) {
case NEW_USER_ASK_MANAGER:
case NEW_USER_ASK:
  $membership = resolve_membership($folder_id, $folder_owner_id);
  if (!isset($membership)) {
    goto close;
  }
  foreach ($membership as $member_id => $properties) {
    if ($properties['folder_permissions'] & MANAGER) {
      $contacts[$properties['user_id']] = null;
  } }
  if ($permit == NEW_USER_ASK_MANAGER) {
    if (count($contacts) != 0) {
      break;
    }
    echo '
<br>The owner ', htmlspecialchars($folder_owner_id), ' of ', htmlspecialchars($path), '
has specified that only managers may assist you but has not identified any
managers for this folder.  Therefore we are permitting you to contact the
owner directly.
<br>';
    $action = '
You ' . $folder_owner_id . 
' have specified that managers of this folder should address
the following concern, but you have not designated any managers yet.
So this email is instead being brought to your attention.
';
  }
case NEW_USER_ASK_OWNER:
  $contacts[$folder_owner_id] = null;
  break;
default:
  echo '
<br>Neither the creator or managers of this folder permit users emailing them
to request altered access to ', htmlspecialchars($path);
  goto close;
}

echo '
<br>If you wish to send an email to those who may grant you <i>',
htmlspecialchars($gUserid), '</i>
priviledged access to the folder <i>', htmlspecialchars($path), '</i> complete the following
request.
Explain why you need to become a group member or (if already one) have your
access permissions changed and
what level of access you think would be appropriate for you.
<p>
<form action="request.php" method="post">';
hidden('folder_id');
hidden('action');
echo '
<table>';
foreach ($contacts as $contact => $value) {
  echo '
<tr>
<td></td><td><input type=checkbox name=contacts', '[', $contact, '] /> Contact ',
htmlspecialchars($contact), '</td>
</tr>';
}
echo '
<tr>
<td align=right>Request:</td>
<td><textarea name=request wrap=virtual rows="20" cols=90>',
htmlspecialchars($request), '
</textarea>
</td>
</tr>
<tr>
<td><input type=submit name=send value="Send Email" /></td>
</tr>
</table>';

close:
DBclose();
done:
?>
</body>
</html>

