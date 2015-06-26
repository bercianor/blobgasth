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
<?php 
if ($_GET['type'] == 'newuser') {
?>
    <div class="subtitle">Nuevo Usuario</div>
    <br>
    <form action="ops.php" method="post"><table class="form" align="center" border='0'>
        <tr>
            <td align="right">Usuario:</td><td><input type="text" class="formelem" name="newuser" placeholder="Usuario"></td>
        </tr>
        <tr>
            <td align="right">Contraseña:</td><td><input type="password" class="formelem" name="newuserpass" placeholder="Nueva Contraseña"></td>
        </tr>
        <tr>
            <td align="right">Repite la Contraseña:</td><td><input type="password" class="formelem" name="newuserreppass" placeholder="Repite la contraseña"></td>
        </tr>
        <tr>
            <td></td><td><input type="submit" class="formelem" name="newuserb" value="Enviar"></td>
        </tr>
    </table></form>
<?php
}
else if ($_GET['type'] == 'newcat') {
?>
    <div class="subtitle">Añadir Categoría</div>
    <br>
    <form action="ops.php" method="post"><table class="form" align="center" border='0'>
        <tr>
            <td align="right">Nombre de la categoría:</td><td><input type="text" class="formelem" name="category" placeholder="Nombre de la categoría"></td>
        </tr>
        <tr>
            <td></td><td><input type="submit" class="formelem" name="newcat" value="Enviar"></td>
        </tr>
    </table></form>
<?php
}
else if ($_GET['type'] == 'newaccount') {
?>
    <div class="subtitle">Añadir Cuenta</div>
    <br>
    <form action="ops.php" method="post"><table class="form" align="center" border='0'>
        <tr>
            <td align="right">Nombre de la Cuenta:</td><td><input type="text" class="formelem" name="account" placeholder="Nombre de la Cuenta"></td>
        </tr>
        <tr>
            <td align="right">Balance inicial:</td><td><input type="number" class="formelem" name="balance" placeholder="Balance inicial de la cuenta"></td>
        </tr>
        <tr>
            <td align="right">¿Cuenta Común?</td><td><input type="checkbox" class="formelem" name="common"></td>
        </tr>
        <tr>
            <td></td><td><input type="submit" class="formelem" name="newaccount" value="Enviar"></td>
        </tr>
    </table></form>
<?php
}
else {
    echo "";
}
?>