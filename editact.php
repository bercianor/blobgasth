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
<script>
jQuery(document).ready(function($){
    var type = "<?php echo $_GET['type']; ?>";
    if (type == 'transfer') {
        $("div.form").slideUp("slow", function(){
            $("#datediv").show();
            $("#date").attr('required',true);
            $("#valuediv").show();
            $("#value").attr({'min' : '0.01', 'max' : ''});
            $("#accountdiv").hide();
            $("#account").removeAttr('required');
            $("#originaccountdiv").show();
            $("#originaccount").attr('required',true);
            $("#destaccountdiv").show();
            $("#destaccount").attr('required',true);
            $("#externaldiv").hide();
            $("#external").removeAttr('required');
            $("#commondiv").hide();
            $("#categorydiv").hide();
            $("#category").removeAttr('required');
            $("#descriptiondiv").hide();
            $("#description").removeAttr('required');
            $("#newactdiv").hide();
            $("#transfdiv").show();
            $("div.form").slideDown("slow");
        });
    }
    else if (type == 'activity') {
        $("div.form").slideUp("slow", function(){
            $("#datediv").show();
            $("#date").attr('required',true);
            $("#valuediv").show();
            $("#value").removeAttr('max');
            $("#value").removeAttr('min');
            $("#accountdiv").show();
            $("#account").attr('required',true);
            $("#originaccountdiv").hide();
            $("#originaccount").removeAttr('required');
            $("#destaccountdiv").hide();
            $("#destaccount").removeAttr('required');
            $("#externaldiv").show();
            $("#external").attr('required',true);
            $("#commondiv").show();
            $("#categorydiv").show();
            $("#category").attr('required',true);
            $("#descriptiondiv").show();
            $("#description").attr('required',true);
            $("#newactdiv").show();
            $("#transfdiv").hide();
            $("div.form").slideDown("slow");
        });
    }
    else {
        $("div.form").slideUp("slow", function(){
            $("#datediv").hide();
            $("#valuediv").hide();
            $("#accountdiv").hide();
            $("#originaccountdiv").hide();
            $("#destaccountdiv").hide();
            $("#externaldiv").hide();
            $("#commondiv").hide();
            $("#categorydiv").hide();
            $("#descriptiondiv").hide();
            $("#newactdiv").hide();
            $("#transfdiv").hide();
        });
    }
});
</script>
<?php
if ($_GET['type'] == 'transfer') {
    $sql=$con->prepare("SELECT * FROM ".$tabletrans." WHERE id = :id");
    $sql->bindParam(':id', $_GET['id']);
    $sql->execute();
    $edit = $sql->fetch(PDO::FETCH_ASSOC);
}
else if ($_GET['type'] == 'activity') {
    $sql=$con->prepare("SELECT * FROM ".$tableact." WHERE id = :id");
    $sql->bindParam(':id', $_GET['id']);
    $sql->execute();
    $edit = $sql->fetch(PDO::FETCH_ASSOC);
}
?>
<div id="title"><?php echo $editact_text; ?></div>
<form action="ops.php?id=<?php echo $_GET['id'] ?>" method="post">
    <div class="form" style="display:none"><table class="form" align="center" border='0'>
        <tr id="datediv" style="display:none">
            <td align="right"><?php echo $date_text; ?>:</td><td><input type="datetime-local" class="formelem" name="date" id="date" value="<?php echo date('Y-m-d', strtotime($edit['Date'])).'T'.date('H:i:s.u', strtotime($edit['Date'])); ?>"></td>
        </tr>
        <tr id="valuediv" style="display:none">
            <td align="right"><?php echo $value_text; ?>:</td><td><input type="number" class="formelem" name="value" id="value" step="0.01" required value=<?php echo $edit['Value']; ?>></td>
        </tr>
        <tr id="accountdiv" style="display:none">
            <td align="right"><?php echo $account_text; ?>:</td><td><select class="formelem" name="account" id="account">
                <option value=""><?php echo $selectaccount_text; ?>:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tableaccounts.".IdAccount, ".$tableaccounts.".Account FROM ".$tableaccounts." JOIN ".$tableuser." ON ".$tableaccounts.".IdUser = ".$tableuser.".IdUser WHERE ".$tableuser.".User = :user OR ".$tableaccounts.".Common = 1 ORDER BY ".$tableaccounts.".IdAccount ASC");
                    $sql->bindParam(':user', $_SESSION['user']);
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdAccount'].'"'.(($row['IdAccount'] == $edit['IdAccount']) ? ' selected>' : '>').$row['Account'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="originaccountdiv" style="display:none">
            <td align="right"><?php echo $origaccount_text; ?>:</td><td><select class="formelem" name="originaccount" id="originaccount">
                <option value="" selected><?php echo $selectaccount_text; ?>:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tableaccounts.".IdAccount, ".$tableaccounts.".Account FROM ".$tableaccounts." JOIN ".$tableuser." ON ".$tableaccounts.".IdUser = ".$tableuser.".IdUser WHERE ".$tableuser.".User = :user OR ".$tableaccounts.".Common = 1 ORDER BY ".$tableaccounts.".IdAccount ASC");
                    $sql->bindParam(':user', $_SESSION['user']);
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdAccount'].'"'.(($row['IdAccount'] == $edit['IdAccountOrig']) ? ' selected>' : '>').$row['Account'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="destaccountdiv" style="display:none">
            <td align="right"><?php echo $destaccount_text; ?>:</td><td><select class="formelem" name="destaccount" id="destaccount">
                <option value="" selected><?php echo $selectaccount_text; ?>:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tableaccounts.".IdAccount, ".$tableaccounts.".Account FROM ".$tableaccounts." JOIN ".$tableuser." ON ".$tableaccounts.".IdUser = ".$tableuser.".IdUser WHERE ".$tableuser.".User = :user OR ".$tableaccounts.".Common = 1 ORDER BY ".$tableaccounts.".IdAccount ASC");
                    $sql->bindParam(':user', $_SESSION['user']);
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdAccount'].'"'.(($row['IdAccount'] == $edit['IdAccountDest']) ? ' selected>' : '>').$row['Account'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="externaldiv" style="display:none">
            <td align="right"><?php echo $external_text; ?>:</td><td><input type="text" class="formelem" name="external" id="external" value="<?php echo $edit['External']; ?>"></td>
        </tr>
        <tr id="commondiv" style="display:none">
            <td align="right"><?php echo $common_text; ?></td><td><input type="checkbox" class="formelem" name="common" id="common"<?php echo (($edit['Common']) ? ' checked' : '') ?>></td>
        </tr>
        <tr id="categorydiv" style="display:none">
            <td align="right"><?php echo $category_text; ?>:</td><td><select class="formelem" name="category" id="category">
                <option value="" selected><?php echo $selectcat_text; ?>:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tablecat.".IdCategory, ".$tablecat.".Category FROM ".$tablecat." ORDER BY ".$tablecat.".IdCategory ASC");
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdCategory'].'"'.(($row['IdCategory'] == $edit['IdCategory']) ? ' selected>' : '>').$row['Category'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="descriptiondiv" style="display:none">
            <td align="right"><?php echo $description_text; ?>:</td><td><input type="text" class="formelem" name="description" id="description" value="<?php echo $edit['Description']; ?>"></td>
        </tr>
        <tr id="newactdiv"><td></td><td><input type="submit" class="formelem" name="editact" id="editact" value="<?php echo $editact_text; ?>"></td></tr>
        <tr id="transfdiv"><td></td><td><input type="submit" class="formelem" name="edittransf" id="edittransf" value="<?php echo $edittransf_text; ?>"></td></tr>
    </table></div>
</form>