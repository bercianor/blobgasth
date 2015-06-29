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
<div id="title">Movimientos</div>
<br><div align="center"><select name="months" id="months" align="center">
<?php
    $months = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
    echo '<option value="" selected>Mes actual: '.$months[date('n')-1].' '.date('y').'</option>';
    try {
        if ($settings['database']['driver'] == 'mysql') {
            $tableactdatevalue="DATE_FORMAT(".$tableact.".Date,'%Y%m')";
            $tableactdatemonth="DATE_FORMAT(".$tableact.".Date,'%m')";
            $tableactdateyear="DATE_FORMAT(".$tableact.".Date,'%Y')";
            $tableactdatenow="DATE_FORMAT(Now(),'%Y%m')";
        }
        else if ($settings['database']['driver'] == 'sqlite') {
            $tableactdatevalue="strftime('%Y%m', ".$tableact.".Date)";
            $tableactdatemonth="strftime('%m', ".$tableact.".Date)";
            $tableactdateyear="strftime('%Y', ".$tableact.".Date)";
            $tableactdatenow="strftime('%Y%m', 'now')";
        }
        $sql=$con->prepare("SELECT DISTINCT ".$tableactdatemonth." AS month, ".$tableactdateyear." AS year FROM ".$tableact." WHERE ".$tableactdatevalue." <> ".$tableactdatenow." ORDER BY ".$tableact.".Date ASC");
        $sql->execute();
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="'.$row['year'].$row['month'].'">'.$months[$row['month']-1].' '.$row['year'].'</option>';
        }
    }
    catch (PDOException $e) {
        echo 'activities.php Error: ' . $e->getMessage();
    }
?>
</select></div><br>
<script>
jQuery(document).ready(function($){
    var today = new Date()
    var yyyy = today.getFullYear().toString();
    var mm = (today.getMonth()+1).toString();
    $.get("activities_ajax.php?month=" + yyyy+(mm[1]?mm:"0"+mm[0]), function(data, status){
        if (status === "success") {
            $("#activitytables").html(data);
            $("#activitytables").slideDown("slow");
        }
        else {
            $("#activitytables").html("Error: " + status);
        }
    });
    $("#months").change(function(){
        $("#activitytables").slideUp("slow", function(){
            $("#activitytables").html("");
            if ($("#months").val() === "") {
                $.get("activities_ajax.php?month=" + yyyy+(mm[1]?mm:"0"+mm[0]), function(data, status){
                    if (status === "success") {
                        $("#activitytables").html(data);
                        $("#activitytables").slideDown("slow");
                    }
                    else {
                        $("#activitytables").html("Error: " + status);
                    }
                });
            }
            else {
                $.get("activities_ajax.php?month=" + $("#months").val(), function(data, status){
                    if (status === "success") {
                        $("#activitytables").html(data);
                        $("#activitytables").slideDown("slow");
                    }
                    else {
                        $("#activitytables").html("Error: " + status);
                    }
                });
            }
        });
    });
});
</script>
<div id="activitytables" style="display:block"></div>