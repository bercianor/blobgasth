<?php include 'db.con.php'; ?>
<?php
/*
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
*/
try {
    $sql=$con->prepare("SELECT ROUND(SUM(".$tableact.".Value), 2) AS Value FROM ".$tableact." JOIN ".$tableuser." ON ".$tableact.".IdUser=".$tableuser.".IdUser WHERE ".$tableact.".Common = 1 AND ".$tableact.".Date >= ".$tableuser.".Cutoff AND ".$tableact.".IdUser = :iduser");
    $sql->bindParam(':iduser', $_GET['iduser']);
    $sql->execute();
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if (isset($row['Value'])) {
        echo $row['Value'];
    }
    else {
        echo 0;
    }
}
catch (PDOException $e) {
    echo 'common_ajax.php Error: ' . $e->getMessage();
}
?>