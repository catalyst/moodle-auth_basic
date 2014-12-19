<?php

if (!isset($config->send401)) {
    $config->send401 = false;
}
$yesno = array(get_string('no'), get_string('yes'));

?>
<table cellspacing="0" cellpadding="5" border="0">
<tr valign="top">
    <td align="right">
        <label for="start_tls"><?php print_string('send401', 'auth_basic') ?></label>
    </td>
    <td>
        <?php echo html_writer::select($yesno, 'send401', $config->send401, false); ?>
    </td>
    <td>
        <?php print_string('send401_help', 'auth_basic') ?>
    </td>
</tr>
</table>
