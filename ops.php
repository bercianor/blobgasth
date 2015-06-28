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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include 'db.con.php'; ?>
<?php
try{
    if (isset($_POST['newpassb'])) {
        if ($_POST['newpass'] == $_POST['reppass']) {
            $sql=$con->prepare("UPDATE ".$tableuser." SET passhash = :newpass WHERE User = :user");
            $sql->bindParam(':newpass', password_hash(test_input($_POST['newpass']),PASSWORD_DEFAULT));
            $sql->bindParam(':user', $_SESSION['user']);
            $sql->execute();
        }
        else {
            echo "<script languaje='javascript'>alert('Las contraseÃ±as no coinciden')</script>";
            echo "<script languaje='javascript'>window.open('index.php?page=user&tipo=newpass','_self');</script>";
        }
    }
    if (isset($_POST['newuserb'])) {
        if ($_POST['newuserpass'] == $_POST['newuserreppass']) {
            $sql=$con->prepare("INSERT INTO ".$tableuser." (User, passhash) VALUES (:newuser, :newuserpass)");
            $sql->bindParam(':newuser', test_input($_POST['newuser']));
            $sql->bindParam(':newuserpass', password_hash(test_input($_POST['newuserpass']),PASSWORD_DEFAULT));
            $sql->execute();
        }
        else {
            echo "<script languaje='javascript'>alert('Las contraseÃ±as no coinciden')</script>";
            echo "<script languaje='javascript'>window.open('index.php?page=user&tipo=newuser','_self');</script>";
        }
    }
    if (isset($_POST['newcat'])) {
        $sql=$con->prepare("INSERT INTO ".$tablecat." (Category) VALUES (:category)");
        $sql->bindParam(':category', test_input($_POST['category']));
        $sql->execute();
    }
    if (isset($_POST['newaccount'])) {
        $sql=$con->prepare("SELECT IdUser FROM ".$tableuser." WHERE User = :user");
        $sql->bindParam(':user', $_SESSION['user']);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        
        $sql=$con->prepare("INSERT INTO ".$tableaccounts." (IdUser, Account, Balance, Common) VALUES (".$user['IdUser'].", :account,     :balance, ".(isset($_POST['common']) ? 1 : 0).")");
        $sql->bindParam(':account', test_input($_POST['account']));
        $sql->bindParam(':balance', test_input($_POST['balance']));
        $sql->execute();
    }
    if (isset($_POST['newact'])) {
        ((test_input($_POST['type']) == 'Expense' && test_input($_POST['value'])>0) ? ($op_value = -test_input($_POST['value'])) : ($op_value = test_input($_POST['value'])));
        
        $sql=$con->prepare("SELECT IdUser FROM ".$tableuser." WHERE User = :user");
        $sql->bindParam(':user', $_SESSION['user']);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        
        $sql=$con->prepare("SELECT Common FROM ".$tableaccounts." WHERE IdAccount = :account");
        $sql->bindParam(':account', test_input($_POST['account']));
        $sql->execute();
        $cuentacomun = $sql->fetch(PDO::FETCH_ASSOC);
        
        $sql=$con->prepare("INSERT INTO ".$tableact." (IdUser, Date, Type, Value, IdAccount, External, Common, IdCategory, Description) VALUES (".$user['IdUser'].", '".date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['date'])))."', :type, :op_value, :account, :external, ".(isset($_POST['common']) && $cuentacomun['Common']==0 ? 1 : 0).", :category, :description)");
        $sql->bindParam(':type', test_input($_POST['type']));
        $sql->bindParam(':op_value', $op_value);
        $sql->bindParam(':account', test_input($_POST['account']));
        $sql->bindParam(':external', test_input($_POST['external']));
        $sql->bindParam(':category', test_input($_POST['category']));
        $sql->bindParam(':description', test_input($_POST['description']));
        $sql->execute();
        
        $sql=$con->prepare("UPDATE ".$tableaccounts." SET Balance = ROUND(Balance + :op_value, 2) WHERE IdAccount = :account");
        $sql->bindParam(':op_value', $op_value);
        $sql->bindParam(':account', test_input($_POST['account']));
        $sql->execute();
    }
    if (isset($_POST['newtransf'])) {
        $sql=$con->prepare("SELECT IdUser FROM ".$tableuser." WHERE User = :user");
        $sql->bindParam(':user', $_SESSION['user']);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        
        $sql=$con->prepare("INSERT INTO ".$tabletrans." (IdUser, Date, Value, IdAccountOrig, IdAccountDest) VALUES (".$user['IdUser'].", '".date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $_POST['date'])))."', :value, :originaccount, :destaccount)");
        $sql->bindParam(':value', test_input($_POST['value']));
        $sql->bindParam(':originaccount', test_input($_POST['originaccount']));
        $sql->bindParam(':destaccount', test_input($_POST['destaccount']));
        $sql->execute();
        
        $sql=$con->prepare("UPDATE ".$tableaccounts." SET Balance = ROUND(Balance - :value, 2) WHERE IdAccount = :originaccount");
        $sql->bindParam(':value', test_input($_POST['value']));
        $sql->bindParam(':originaccount', test_input($_POST['originaccount']));
        $sql->execute();
        
        $sql=$con->prepare("UPDATE ".$tableaccounts." SET Balance = ROUND(Balance + :value, 2) WHERE IdAccount = :destaccount");
        $sql->bindParam(':value', test_input($_POST['value']));
        $sql->bindParam(':destaccount', test_input($_POST['destaccount']));
        $sql->execute();
    }
    echo "<script languaje='javascript'>window.open('index.php','_self');</script>";
}
catch (PDOException $e) {
    echo 'ops.php Error: ' . $e->getMessage();
}
?>