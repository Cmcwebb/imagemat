<?php

const CAPTCHA = '../../tools/securimage-3.2RC2';

require_once($dir . '/' . CAPTCHA . '/securimage.php');

function emit_captcha($rowspan)
{
?>
<td rowspan=<?php echo $rowspan; ?> valign=middle>
<table>
<tr>
<td rowspan=2>
<img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="<?php echo CAPTCHA;?>/securimage_show.php?sid=<?php echo md5(uniqid()) ?>" alt="CAPTCHA Image" align="left" />
</td>
<td>
<a align=top tabindex="-1" style="border-style: none;" href="#" title="Refresh Image" onclick="document.getElementById('siimage').src = '<?php echo CAPTCHA;?>/securimage_show.php?sid=' + Math.random(); this.blur(); return false"><img src="<?php echo CAPTCHA;?>/images/refresh.png" alt="Reload Image" height="32" width="32" onclick="this.blur()" align="bottom" border="0" /></a>
</td>
</tr>
<tr>
<td>
<object type="application/x-shockwave-flash" data="<?php echo CAPTCHA;?>/securimage_play.swf?bgcol=#ffffff&amp;icon_file=<?php echo CAPTCHA;?>/images/audio_icon.png&amp;audio_file=<?php echo CAPTCHA;?>/securimage_play.php" height="32" width="32">
    <param name="movie" value="<?php echo CAPTCHA;?>/securimage_play.swf?bgcol=#ffffff&amp;icon_file=<?php echo CAPTCHA;?>/images/audio_icon.png&amp;audio_file=<?php CAPTCHA;?>/securimage_play.php" />
</object>
</td>
</tr>
<tr>
<td colspan=2 align=left>
    <strong>Enter Code*:</strong>
    <input type="text" name="captcha" size="12" maxlength="8" />
</td>
</tr>
</table>
</td>
<?php
}

function captchaOK()
{
  $securimage = new Securimage();
  $captcha = getpost('captcha');

  return $securimage->check($captcha);
}
?>
