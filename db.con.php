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
session_start();
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
function tableExists($pdo, $table) {
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    }
    catch (Exception $e) {
        return FALSE;
    }
    return $result !== FALSE;
}
function arr2ini(array $a, array $parent = array()) {
    $out = '';
    foreach ($a as $k => $v) {
        if (is_array($v)) {
            $sec = array_merge((array) $parent, (array) $k);
            $out .= '[' . join('.', $sec) . ']' . PHP_EOL;
            $out .= arr2ini($v, $sec);
        }
        else {
            $out .= "$k=$v" . PHP_EOL;
        }
    }
    return $out;
}

$configfile = 'db_settings.ini';
$tablecat = "categories";
$tableaccounts = "accounts";
$tableact = "activities";
$tabletrans = "transfers";
$tableuser = "users";

if (!$settings = parse_ini_file($configfile, TRUE)) {
    $settings = [
        'database' => [
            'driver' => 'sqlite',
            'host' => 'database.sqlite3'
        ]
    ];
    $inifile = fopen($configfile, "w");
    fwrite($inifile, arr2ini($settings));
    fclose($inifile);
}
try {
    if ($settings['database']['driver'] == 'mysql') {
        $dsn =  'mysql:host=' . $settings['database']['host'] .
                ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '') .
                ';dbname=' . $settings['database']['db_name'];
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        $con = new PDO($dsn, $settings['database']['username'], $settings['database']['password'], $options);
        (($settings['database']['port'] == "") ? ($con->exec("SET lc_time_names = '".$_SESSION['lang']."'")) : null);
        if (!tableExists($con, $tablecat)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS `" . $tablecat . "` (
                    `IdCategory` int(11) NOT NULL,
                    `Category` varchar(50) NOT NULL
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tablecat . "` ADD PRIMARY KEY (`IdCategory`)");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tablecat . "` MODIFY `IdCategory` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $sql->execute();
        }
        if (!tableExists($con, $tableaccounts)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS `" . $tableaccounts . "` (
                    `IdAccount` int(11) NOT NULL,
                    `IdUser` int(11) NOT NULL,
                    `Account` varchar(50) NOT NULL,
                    `Balance` double NOT NULL,
                    `Common` tinyint(1) NOT NULL DEFAULT '0'
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tableaccounts . "` ADD PRIMARY KEY (`IdAccount`)");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tableaccounts . "` MODIFY `IdAccount` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $sql->execute();
        }
        if (!tableExists($con, $tableact)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS `" . $tableact . "` (
                    `id` int(11) NOT NULL,
                    `IdUser` int(11) NOT NULL,
                    `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `Type` varchar(13) NOT NULL DEFAULT 'Expense',
                    `Value` double NOT NULL,
                    `IdAccount` int(11) NOT NULL,
                    `External` varchar(50) NOT NULL,
                    `Common` tinyint(1) NOT NULL DEFAULT '0',
                    `IdCategory` int(11) NOT NULL,
                    `Description` varchar(500) NOT NULL
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tableact . "` ADD PRIMARY KEY (`id`)");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tableact . "` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $sql->execute();
        }
        if (!tableExists($con, $tabletrans)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS `" . $tabletrans . "` (
                    `id` int(11) NOT NULL,
                    `IdUser` int(11) NOT NULL,
                    `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `Value` double NOT NULL,
                    `IdAccountOrig` int(11) NOT NULL,
                    `IdAccountDest` int(11) NOT NULL
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tabletrans . "` ADD PRIMARY KEY (`id`)");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tabletrans . "` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $sql->execute();
        }
        if (!tableExists($con, $tableuser)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS `" . $tableuser . "` (
                    `IdUser` int(11) NOT NULL,
                    `User` varchar(50) NOT NULL,
                    `lang` varchar(5) NOT NULL DEFAULT 'ES_es',
                    `Cutoff` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `passhash` varchar(100) NOT NULL,
                    `authkey` text NOT NULL DEFAULT ''
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tableuser . "` ADD PRIMARY KEY (`IdUser`)");
            $sql->execute();
            
            $sql=$con->prepare("ALTER TABLE `" . $tableuser . "` MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1");
            $sql->execute();
?>
            <div id="firstuser" style="position:relative; z-index: 0">
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
            </div>
<?php
            exit("Crear el primer usuario");
        }
    }
    else if ($settings['database']['driver'] == 'sqlite') {
        $dsn = 'sqlite:' . $settings['database']['host'];
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        $con = new PDO($dsn, null, null, $options);
        if (!tableExists($con, $tablecat)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS " . $tablecat . " (
                    IdCategory INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    Category TEXT NOT NULL
                )
            ");
            $sql->execute();
        }
        if (!tableExists($con, $tableaccounts)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS " . $tableaccounts . " (
                    IdAccount INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    IdUser INTEGER NOT NULL,
                    Account TEXT NOT NULL,
                    Balance REAL NOT NULL,
                    Common INTEGER NOT NULL DEFAULT '0'
                )
            ");
            $sql->execute();
        }
        if (!tableExists($con, $tableact)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS " . $tableact . " (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    IdUser INTEGER NOT NULL,
                    Date INTEGER NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    Type TEXT NOT NULL DEFAULT 'Expense',
                    Value REAL NOT NULL,
                    IdAccount INTEGER NOT NULL,
                    External TEXT NOT NULL,
                    Common INTEGER NOT NULL DEFAULT '0',
                    IdCategory INTEGER NOT NULL,
                    Description TEXT NOT NULL
                )
            ");
            $sql->execute();
        }
        if (!tableExists($con, $tabletrans)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS " . $tabletrans . " (
                    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    IdUser INTEGER NOT NULL,
                    Date INTEGER NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    Value REAL NOT NULL,
                    IdAccountOrig INTEGER NOT NULL,
                    IdAccountDest INTEGER NOT NULL
                )
            ");
            $sql->execute();
        }
        if (!tableExists($con, $tableuser)) {
            $sql=$con->prepare("
                CREATE TABLE IF NOT EXISTS " . $tableuser . " (
                    IdUser INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                    User TEXT NOT NULL COLLATE NOCASE,
                    lang TEXT NOT NULL DEFAULT 'ES_es',
                    Cutoff INTEGER NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    passhash TEXT NOT NULL,
                    authkey TEXT NOT NULL DEFAULT ''
                )
            ");
            $sql->execute();
?>
            <div id="firstuser" style="position:relative; z-index: 0">
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
            </div>
<?php
            exit("Crear el primer usuario");
        }
    }
}
catch (PDOException $e) {
    echo $settings['database']['driver'] . ' Error: ' . $e->getMessage();
}
include 'queries.php';
?>