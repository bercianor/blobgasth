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
<?php
try {
    if (isset($_GET['logout'])) {
        if ($_GET['logout']) {
            $sql=$con->prepare("UPDATE ".$tableuser." SET authkey='' WHERE User = :user");
            $sql->bindParam(':user', $_SESSION['user']);
            $sql->execute();
            session_unset();
            session_destroy();
        
        }
        echo "<script languaje='javascript'>window.open('index.php','_self');</script>";
    }
    if (isset($_POST['login'])) {
        $sql=$con->prepare("SELECT * FROM ".$tableuser." WHERE User = :user");
        $sql->bindParam(':user', test_input($_POST['user']));
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        if (is_null($row['User'])) {
            echo "<script languaje='javascript'>alert('".$novaliduser_text."')</script>";
            exit($novaliduser_text);
        }
        else {
            if (password_verify($_POST['password'],$row['passhash'])) {
                $_SESSION['user'] = $_POST['user'];
                $_SESSION['lang'] = $row['lang'];
                $_SESSION['authkey'] = "AUTORIZADO";
                $_SESSION['auth'] = true;
                $sql=$con->prepare("UPDATE ".$tableuser." SET authkey = :authkey WHERE User = :user");
                $sql->bindParam(':authkey', $_SESSION['authkey']);
                $sql->bindParam(':user', $_SESSION['user']);
                $sql->execute();
            }
            else {
                echo "<script languaje='javascript'>alert('".$novalidpass_text."')</script>";
                exit($novalidpass_text);
            }
        }
        echo "<script languaje='javascript'>window.open('index.php','_self');</script>";
    }
    if ($_GET['type'] == 'newpass') {
?>
        <div id="title"><?php echo $changepass_text; ?></div>
        <form action="ops.php" method="post"><table class="form" align="center" border='0'>
            <tr>
                <td align="right"><?php echo $newpass_text; ?>:</td><td><input type="password" class="formelem" name="newpass" placeholder="<?php echo $newpass_text; ?>"></td>
            </tr>
            <tr>
                <td align="right"><?php echo $repeatpassword_text; ?>:</td><td><input type="password" class="formelem" name="reppass" placeholder="<?php echo $repeatpassword_text; ?>"></td>
            </tr>
            <tr><td></td><td><input type="submit" name="newpassb" class="formelem" value="<?php echo $send_text; ?>"></td></tr>
        </table></form>
<?php
    }
    else if ($_GET['type'] == 'newlang') {
?>
        <div id="title"><?php echo $changelang_text; ?></div>
        <form action="ops.php" method="post"><table class="form" align="center" border='0'>
            <tr>
                <td align="right"><?php echo $newlang_text; ?>:</td><td><select class="formelem" name="newlang">
                    <option value="" selected><?php echo $selectlang_text; ?>:</option>
<?php
                    foreach ($languages['languages'] as $value => $text) {
                        echo '<option value="'.$value.'"'.(($value == $_SESSION['lang']) ? 'selected' : '').'>'.$text.'</option>';
                    }
?>
                </select></td>
            </tr>
            <tr><td></td><td><input type="submit" name="newlangb" class="formelem" value="<?php echo $send_text; ?>"></td></tr>
        </table></form>
<?php
    }
}
catch (PDOException $e) {
    echo 'user.php Error: ' . $e->getMessage();
}
?>