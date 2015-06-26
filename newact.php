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
    $("#type").change(function(){
        if ($("#type").val() == 'Transfer') {
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
        else if ($("#type").val() == 'Expense' || $("#type").val() == 'Income') {
            $("div.form").slideUp("slow", function(){
                $("#datediv").show();
                $("#date").attr('required',true);
                $("#valuediv").show();
                if ($("#type").val() == 'Expense') {
                    $("#value").removeAttr('max');
                    $("#value").removeAttr('min');
                }
                else if ($("#type").val() == 'Income') {
                    $("#value").attr({'min' : '0.01', 'max' : ''});
                }
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
});
</script>
<div id="title">Añadir Movimientos</div>
<form action="ops.php" method="post">
    <div id="typediv" align="center">
        Tipo: <select name="type" id="type">
            <option value="" selected>Tipo de movimiento:</option>
            <option value="Expense">Gasto</option>
            <option value="Income">Ingreso</option>
            <option value="Transfer">Transferencia</option>
        </select>
    </div>
    <br>
    <div class="form" style="display:none"><table class="form" align="center" border='0'>
        <tr id="datediv" style="display:none">
            <td align="right">Fecha:</td><td><input type="datetime-local" class="formelem" name="date" id="date" placeholder="Fecha del movimiento"></td>
        </tr>
        <tr id="valuediv" style="display:none">
            <td align="right">Valor:</td><td><input type="number" class="formelem" name="value" id="value" placeholder="Valor de la operación" step="0.01" required></td>
        </tr>
        <tr id="accountdiv" style="display:none">
            <td align="right">Cuenta:</td><td><select class="formelem" name="account" id="account">
                <option value="" selected>Selecciona una cuenta:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tableaccounts.".IdAccount, ".$tableaccounts.".Account FROM ".$tableaccounts." JOIN ".$tableuser." ON ".$tableaccounts.".IdUser = ".$tableuser.".IdUser WHERE ".$tableuser.".User = :user OR ".$tableaccounts.".Common = TRUE ORDER BY ".$tableaccounts.".IdAccount ASC");
                    $sql->bindParam(':user', $_SESSION['user']);
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdAccount'].'">'.$row['Account'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="originaccountdiv" style="display:none">
            <td align="right">Cuenta origen:</td><td><select class="formelem" name="originaccount" id="originaccount">
                <option value="" selected>Selecciona una cuenta:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tableaccounts.".IdAccount, ".$tableaccounts.".Account FROM ".$tableaccounts." JOIN ".$tableuser." ON ".$tableaccounts.".IdUser = ".$tableuser.".IdUser WHERE ".$tableuser.".User = :user OR ".$tableaccounts.".Common = TRUE ORDER BY ".$tableaccounts.".IdAccount ASC");
                    $sql->bindParam(':user', $_SESSION['user']);
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdAccount'].'">'.$row['Account'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="destaccountdiv" style="display:none">
            <td align="right">Cuenta destino:</td><td><select class="formelem" name="destaccount" id="destaccount">
                <option value="" selected>Selecciona una cuenta:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tableaccounts.".IdAccount, ".$tableaccounts.".Account FROM ".$tableaccounts." JOIN ".$tableuser." ON ".$tableaccounts.".IdUser = ".$tableuser.".IdUser WHERE ".$tableuser.".User = :user OR ".$tableaccounts.".Common = TRUE ORDER BY ".$tableaccounts.".IdAccount ASC");
                    $sql->bindParam(':user', $_SESSION['user']);
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdAccount'].'">'.$row['Account'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="externaldiv" style="display:none">
            <td align="right">Externo:</td><td><input type="text" class="formelem" name="external" id="external" placeholder="A dónde va o de dónde viene"></td>
        </tr>
        <tr id="commondiv" style="display:none">
            <td align="right">¿Común?</td><td><input type="checkbox" class="formelem" name="common" id="common"></td>
        </tr>
        <tr id="categorydiv" style="display:none">
            <td align="right">Categoría:</td><td><select class="formelem" name="category" id="category">
                <option value="" selected>Selecciona una categoría:</option>
<?php
                try {
                    $sql=$con->prepare("SELECT DISTINCT ".$tablecat.".IdCategory, ".$tablecat.".Category FROM ".$tablecat." ORDER BY ".$tablecat.".IdCategory ASC");
                    $sql->execute();
                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="'.$row['IdCategory'].'">'.$row['Category'].'</option>';
                    }
                }
                catch (PDOException $e) {
                    echo 'newact.php Error: ' . $e->getMessage();
                }
?>
            </select></td>
        </tr>
        <tr id="descriptiondiv" style="display:none">
            <td align="right">Descripción:</td><td><input type="text" class="formelem" name="description" id="description" placeholder="Descripción del movimiento"></td>
        </tr>
        <tr id="newactdiv"><td></td><td><input type="submit" class="formelem" name="newact" id="newact" value="Nuevo movimiento"></td></tr>
        <tr id="transfdiv"><td></td><td><input type="submit" class="formelem" name="transf" id="transf" value="Nueva transferencia"></td></tr>
    </table></div>
</form>