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
<div id="title"><?php echo $commonexpenses_text; ?></div>
<table class="tables" border='1' align="center">
    <tr class="header" align='center'>
        <th><?php echo $date_text; ?></th>
        <th><?php echo $type_text; ?></th>
        <th><?php echo $value_text; ?></th>
        <th><?php echo $category_text; ?></th>
        <th><?php echo $description_text; ?></th>
    </tr>
<?php
    $total = 0;
    $common->execute();
    while ($row = $common->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "    <td align='left'>" . date('d/m H:i', strtotime($row['Date'])) . "</td>";
        echo "    <td align='center'>" . $row['Type'] . "</td>";
        echo "    <td align='right'>" . $row['Value'] . " €</td>";
        echo "    <td align='left'>" . $row['Category'] . "</td>";
        echo "    <td align='left'>" . $row['Description'] . "</td>";
        echo "</tr>";
        $total += $row['Value'];
    }
    echo "<tr class='header'>";
    echo "    <td>".$total_text."</td>";
    echo "    <td></td>";
    echo "    <td align='right'>" . round($total, 2) . " €</td>";
    echo "    <td></td>";
    echo "    <td></td>";
    echo "</tr>";
?>
</table>
<p>&nbsp;</p>
<table class="tables" border='1' align="center">
    <tr class="header" align='center'>
        <th><?php echo $user_text; ?></th>
        <th><?php echo $balance_text; ?></th>
    </tr>
<?php
    try {
        $sql=$con->prepare("SELECT ".$tableuser.".User AS User, ROUND(SUM(CASE WHEN (".$tableact.".Common = 1) THEN ".$tableact.".Value ELSE 0 END),2) AS Value FROM ".$tableuser." LEFT JOIN ".$tableact." ON ".$tableuser.".IdUser = ".$tableact.".IdUser WHERE ".$tableuser.".User != :user GROUP BY ".$tableuser.".User ORDER BY ".$tableuser.".IdUser ASC");
        $sql->bindParam(':user', $_SESSION['user']);
        $sql->execute();
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "    <td>" . $row['User'] . "</td>";
            echo "    <td align='right'>" . $row['Value'] . " €</td>";
            echo "</tr>";
        }
    }
    catch (PDOException $e) {
        echo 'common.php Error: ' . $e->getMessage();
    }
?>

</table>
<p>&nbsp;</p>
<div id="adjust" align="center">
<select name="users" id="users">
    <option value="" selected><?php echo $selectuser_text; ?>:</option>
<?php
        try {
            $sql=$con->prepare("SELECT DISTINCT ".$tableuser.".IdUser, ".$tableuser.".User FROM ".$tableuser." WHERE ".$tableuser.".User != :user ORDER BY ".$tableuser.".IdUser ASC");
            $sql->bindParam(':user', $_SESSION['user']);
            $sql->execute();
            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="'.$row['IdUser'].'">'.$row['User'].'</option>';
            }
        }
        catch (PDOException $e) {
            echo 'common.php Error: ' . $e->getMessage();
        }
?>
</select>
<script>
jQuery(document).ready(function($){
    $("#users").change(function(){
        if ($("#users").val() === "") {
            $("#otheruser").html("");
        }
        else {
            $.get("common_ajax.php?iduser=" + $("#users").val(), function(data, status){
                if (status === "success") {
                    var total = parseFloat(<?php echo round($total, 2); ?>);
                    data = parseFloat(data);
                    var adjust = (data-total)/2;
                    $("#otheruser").html("<?php echo $balanceis_text; ?>: " + data.toFixed(2) + " <?php echo $money_symbol; ?><br><?php echo $adjustis_text; ?>: " + adjust.toFixed(2) + " <?php echo $money_symbol; ?>");
                }
                else {
                    $("#otheruser").html("Error: " + status);
                }
            });
        }
    });
});
</script>
<br><br>
<div id="otheruser"></div>