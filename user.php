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
        $sql=$con->prepare("SELECT * FROM ".$tableuser." WHERE lower(User) = lower(:user)");
        $sql->bindParam(':user', test_input($_POST['user']));
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        if (is_null($row['User'])) {
            echo "<script languaje='javascript'>alert('Acceso denegado: Usuario incorrecto')</script>";
            exit("Acceso denegado: Usuario incorrecto");
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
                echo "<script languaje='javascript'>alert('Acceso denegado: Contraseña incorrecta')</script>";
                exit("Acceso denegado: Contraseña incorrecta");
            }
        }
        echo "<script languaje='javascript'>window.open('index.php','_self');</script>";
    }
    if ($_GET['type'] == 'newpass') {
?>
        <div id="title">Cambiar Contraseña</div>
        <form action="ops.php" method="post"><table class="form" align="center" border='0'>
            <tr>
                <td align="right">Nueva contraseña:</td><td><input type="password" class="formelem" name="newpass" placeholder="Nueva Contraseña"></td>
            </tr>
            <tr>
                <td align="right">Repetir contraseña:</td><td><input type="password" class="formelem" name="reppass" placeholder="Repite la contraseña"></td>
            </tr>
            <tr><td></td><td><input type="submit" name="newpassb" class="formelem" value="Enviar"></td></tr>
        </table></form>
<?php
    }
}
catch (PDOException $e) {
    'user.php Error: ' . $e->getMessage();
}
?>