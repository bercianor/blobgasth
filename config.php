<!--
    Blobgasth Copyright (C) 2015  bercianor[at]haztelo[dot]es

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->
<?php include 'db.con.php'; ?>
<div id="title"><?php echo $config_text; ?></div>
<?php
if ($_GET['type'] == 'newuser') {
?>
    <div class="subtitle"><?php echo $newuser_text; ?></div>
    <br>
    <form action="ops.php" method="post"><table class="form" align="center" border='0'>
        <tr>
            <td align="right"><?php echo $user_text; ?>:</td><td><input type="text" class="formelem" name="newuser" placeholder="<?php echo $user_text; ?>"></td>
        </tr>
        <tr>
            <td align="right"><?php echo $password_text; ?>:</td><td><input type="password" class="formelem" name="newuserpass" placeholder="<?php echo $password_text; ?>"></td>
        </tr>
        <tr>
            <td align="right"><?php echo $repeatpassword_text; ?>:</td><td><input type="password" class="formelem" name="newuserreppass" placeholder="<?php echo $repeatpassword_text; ?>"></td>
        </tr>
        <tr>
            <td align="right"><?php echo $lang_text; ?>:</td><td><select class="formelem" name="newuserlang">
                <option value="" selected><?php echo $selectlang_text; ?>:</option>
<?php
                    foreach ($languages['languages'] as $value => $text) {
                        echo '<option value="'.$value.'">'.$text.'</option>';
                    }
?>
            </select></td>
        </tr>
        <tr>
            <td></td><td><input type="submit" class="formelem" name="newuserb" value="<?php echo $send_text; ?>"></td>
        </tr>
    </table></form>
<?php
}
else if ($_GET['type'] == 'newcat') {
?>
    <div class="subtitle"><?php echo $addcat_text; ?></div>
    <br>
    <form action="ops.php" method="post"><table class="form" align="center" border='0'>
        <tr>
            <td align="right"><?php echo $catname_text; ?>:</td><td><input type="text" class="formelem" name="category" placeholder="<?php echo $catname_text; ?>"></td>
        </tr>
        <tr>
            <td></td><td><input type="submit" class="formelem" name="newcat" value="<?php echo $send_text; ?>"></td>
        </tr>
    </table></form>
<?php
}
else if ($_GET['type'] == 'newaccount') {
?>
    <div class="subtitle"><?php echo $addaccount_text; ?></div>
    <br>
    <form action="ops.php" method="post"><table class="form" align="center" border='0'>
        <tr>
            <td align="right"><?php echo $accountname_text; ?>:</td><td><input type="text" class="formelem" name="account" placeholder="<?php echo $accountname_text; ?>"></td>
        </tr>
        <tr>
            <td align="right"><?php echo $initialbalance_text; ?>:</td><td><input type="number" class="formelem" name="balance" placeholder="<?php echo $initialbalancedesc_text; ?>"></td>
        </tr>
        <tr>
            <td align="right"><?php echo $commonaccount_text; ?></td><td><input type="checkbox" class="formelem" name="common"></td>
        </tr>
        <tr>
            <td></td><td><input type="submit" class="formelem" name="newaccount" value="<?php echo $send_text; ?>"></td>
        </tr>
    </table></form>
<?php
}
else {
    echo "";
}
?>