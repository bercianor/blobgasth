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
<div id="title">Resumen</div>
<div width="100%" align="center">
    <div class="recap" align="center">
        <div class="subtitle">Balance de Cuentas</div>
        <table class="tables" border='1'>
            <tr class="header" align='center'>
                <th>Cuenta</th>
                <th>Saldo</th>
            </tr>
<?php
            $total = 0;
            $accounts->execute();
            while ($row = $accounts->fetch(PDO::FETCH_ASSOC)) {
                if ($row['Common']) {
                    $total += round($row['Balance']/2, 2);
                    echo "<tr bgcolor='#FFFFAA'>";
                }
                else {
                    $total += $row['Balance'];
                    echo "<tr>";
                }
                echo "    <td align='left'>" . $row['Account'] . "</td>";
                echo "    <td align='right'>" . $row['Balance'] . " €</td>";
                echo "</tr>";
            }
            echo "<tr class='header'>";
            echo "    <td>Total</td>";
            echo "    <td align='right'>" . round($total, 2) . " €</td>";
            echo "</tr>";
?>
        </table>
    </div>
    <div class="recap" align="center">
        <div class="subtitle">Gastos por Categoría</div>
        <table class="tables" border='1'>
            <tr class="header" align='center'>
                <th>Categoría</th>
                <th>Balance</th>
            </tr>
<?php
            $total = 0;
            $categories->execute();
            while ($row = $categories->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "    <td>" . $row['Category'] . "</td>";
                echo "    <td align='right'>" . $row['Value'] . " €</td>";
                echo "</tr>";
                $total += $row['Value'];
            }
            echo "<tr class='header'>";
            echo "    <td>Total</td>";
            echo "    <td align='right'>" . round($total, 2) . " €</td>";
            echo "</tr>";
?>
        </table>
    </div>
</div>