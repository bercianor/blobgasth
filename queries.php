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
    if ($settings['database']['driver'] == 'mysql') {
        $tableactdatemonth="DATE_FORMAT(".$tableact.".Date,'%Y%m')";
    }
    else if ($settings['database']['driver'] == 'sqlite') {
        $tableactdatemonth="strftime('%Y%m', ".$tableact.".Date)";
    }
    $activities = $con->prepare("
        SELECT
            ".$tableact.".id AS idActivity,
            ".$tableact.".Date AS Date,
            ".$tableact.".Type AS Type,
            ROUND(".$tableact.".Value, 2) AS Value,
            ".$tableaccounts.".Account AS Account,
            ".$tableact.".External AS External,
            ".$tableact.".Common AS Common,
            ".$tablecat.".Category AS Category,
            ".$tableact.".Description AS Description
        FROM ".$tableact."
            JOIN ".$tableaccounts."
                ON ".$tableact.".IdAccount = ".$tableaccounts.".IdAccount
            JOIN ".$tableuser."
                ON ".$tableact.".IdUser = ".$tableuser.".IdUser
            JOIN ".$tablecat."
                ON ".$tableact.".IdCategory = ".$tablecat.".IdCategory
        WHERE
            ".$tableactdatemonth." = :month
                AND
            (
                ".$tableuser.".User = :user
                    OR
                ".$tableaccounts.".Common = 1
            )
        ORDER BY ".$tableact.".Date ASC
    ");
    $activities->bindParam(':month', $_GET['month']);
    $activities->bindParam(':user', $_SESSION['user']);
    
    if ($settings['database']['driver'] == 'mysql') {
        $trabletransdatemonth="DATE_FORMAT(".$tabletrans.".Date,'%Y%m')";
    }
    else if ($settings['database']['driver'] == 'sqlite') {
        $trabletransdatemonth="strftime('%Y%m', ".$tabletrans.".Date)";
    }
    $transfers = $con->prepare("
        SELECT 
            ".$tabletrans.".id AS idTransfer,
            ".$tabletrans.".Date AS Date,
            ROUND(".$tabletrans.".Value, 2) AS Value,
            ".$tableaccounts."_orig.Account AS OriginAccount,
            ".$tableaccounts."_dest.Account AS DestAccount,
            ".$tableaccounts."_orig.Common AS OriginCommon,
            ".$tableaccounts."_dest.Common AS DestCommon
        FROM ".$tabletrans."
            JOIN ".$tableaccounts." AS ".$tableaccounts."_orig
                ON ".$tabletrans.".IdAccountOrig = ".$tableaccounts."_orig.IdAccount
            JOIN ".$tableaccounts." AS ".$tableaccounts."_dest
                ON ".$tabletrans.".IdAccountDest = ".$tableaccounts."_dest.IdAccount
            JOIN ".$tableuser."
                ON ".$tabletrans.".IdUser = ".$tableuser.".IdUser
        WHERE
            ".$trabletransdatemonth." = :month
                AND
            (
                ".$tableuser.".User = :user
                    OR
                ".$tableaccounts."_orig.Common = 1
                    OR
                ".$tableaccounts."_dest.Common = 1
            )
        ORDER BY ".$tabletrans.".Date ASC
    ");
    $transfers->bindParam(':month', $_GET['month']);
    $transfers->bindParam(':user', $_SESSION['user']);
    
    $accounts = $con->prepare("
        SELECT
            ".$tableaccounts.".Account AS Account,
            ROUND(".$tableaccounts.".Balance, 2) AS Balance,
            ".$tableaccounts.".Common AS Common
        FROM ".$tableuser."
            JOIN ".$tableaccounts."
                ON ".$tableuser.".IdUser = ".$tableaccounts.".IdUser
        WHERE
            ".$tableuser.".User = :user
                OR
            ".$tableaccounts.".Common = 1
        GROUP BY ".$tableaccounts.".IdAccount
        ORDER BY ".$tableaccounts.".IdAccount ASC
    ");
    $accounts->bindParam(':user', $_SESSION['user']);
    
    if ($settings['database']['driver'] == 'mysql') {
        $tablecatdatemonth="DATE_FORMAT(".$tableact.".Date,'%Y%m')";
        $tablecatmonthnow="DATE_FORMAT(Now(),'%Y%m')";
    }
    else if ($settings['database']['driver'] == 'sqlite') {
        $tablecatdatemonth="strftime('%Y%m', ".$tableact.".Date)";
        $tablecatmonthnow="strftime('%Y%m', 'now')";
    }
    $categories = $con->prepare("
        SELECT
            ".$tablecat.".Category AS Category,
            ROUND(
                SUM(
                    CASE WHEN (".$tableuser.".User = :user AND ".$tablecatdatemonth." = ".$tablecatmonthnow.")
                        THEN ".$tableact.".Value
                        ELSE 0
                    END
                ), 2
            ) AS Value
        FROM ".$tablecat."
            LEFT JOIN ".$tableact."
                ON ".$tablecat.".IdCategory = ".$tableact.".IdCategory
            LEFT JOIN ".$tableuser."
                ON ".$tableact.".IdUser = ".$tableuser.".IdUser
        GROUP BY ".$tablecat.".IdCategory
        ORDER BY ".$tablecat.".IdCategory ASC
    ");
    $categories->bindParam(':user', $_SESSION['user']);
    
    $common = $con->prepare("
        SELECT
            ".$tableact.".Date AS Date,
            ".$tableact.".Type AS Type,
            ROUND(".$tableact.".Value, 2) AS Value,
            ".$tablecat.".Category AS Category,
            ".$tableact.".Description AS Description
        FROM ".$tableact."
            JOIN ".$tableaccounts."
                ON ".$tableact.".IdAccount=".$tableaccounts.".IdAccount
            JOIN ".$tableuser."
                ON ".$tableact.".IdUser=".$tableuser.".IdUser
            JOIN ".$tablecat."
                ON ".$tableact.".IdCategory=".$tablecat.".IdCategory
        WHERE
            ".$tableact.".Common = 1
                AND
            ".$tableact.".Date >= ".$tableuser.".Cutoff
                AND
            ".$tableuser.".User = :user
        ORDER BY ".$tableact.".Date ASC
    ");
    $common->bindParam(':user', $_SESSION['user']);
}
catch (PDOException $e) {
    echo 'queries.php Error: ' . $e->getMessage();
}
?>