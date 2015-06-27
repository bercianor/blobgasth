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
<div class="subtitle">Ingresos/Gastos</div>
<table class="tables" border='1' align="center">
    <tr class="header" align='center'>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Valor</th>
        <th>Cuenta</th>
        <th>Externo</th>
        <th>Categoria</th>
        <th>Descripcion</th>
    </tr>
<?php
    $total = 0;
    $activities->execute();
    while ($row = $activities->fetch(PDO::FETCH_ASSOC)) {
        if ($row['Common']) {
            echo "<tr bgcolor='#FFFFAA'>";
        }
        else {
            echo "<tr>";
        }
        echo "    <td align='left'>" . date('d/m H:i', strtotime($row['Date'])) . "</td>";
        echo "    <td align='center'>" . $row['Type'] . "</td>";
        echo "    <td align='right'>" . $row['Value'] . " €</td>";
        echo "    <td align='left'>" . $row['Account'] . "</td>";
        echo "    <td align='left'>" . $row['External'] . "</td>";
        echo "    <td align='left'>" . $row['Category'] . "</td>";
        echo "    <td align='left'>" . $row['Description'] . "</td>";
        echo "</tr>";
        $total += $row['Value'];
    }
    echo "<tr class='header'>";
    echo "    <td>Total</td>";
    echo "    <td></td>";
    echo "    <td align='right'>" . round($total, 2) . " €</td>";
    echo "    <td></td>";
    echo "    <td></td>";
    echo "    <td></td>";
    echo "    <td></td>";
    echo "</tr>"
?>
</table>
<p>&nbsp;</p>
<div class="subtitle">Transferencias</div>
<table class="tables" border='1' align="center">
    <tr class="header" align='center'>
        <th>Fecha</th>
        <th>Valor</th>
        <th>Cuenta Origen</th>
        <th>Cuenta Destino</th>
    </tr>
<?php
    $transfers->execute();
    while ($row = $transfers->fetch(PDO::FETCH_ASSOC)) {
        if ($row['CommonOrigin'] or $row['CommonDest']) {
            echo "<tr bgcolor='#FFFFAA'>";
        }
        else {
            echo "<tr>";
        }
        echo "    <td align='left'>" . date('d/m H:i', strtotime($row['Date'])) . "</td>";
        echo "    <td align='right'>" . $row['Value'] . " €</td>";
        echo "    <td align='left'>" . $row['OriginAccount'] . "</td>";
        echo "    <td align='left'>" . $row['DestAccount'] . "</td>";
        echo "</tr>";
    }
?>
</table>