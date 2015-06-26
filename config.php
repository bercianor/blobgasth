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
<div id="title">Configuración</div>
<br>
<div class="submenu" align="center">
    <div class="submenuitem" id="newuser">Nuevo usuario</div>
    <div class="submenusep">|</div>
    <div class="submenuitem" id="newcat">Nueva categoria</div>
    <div class="submenusep">|</div>
    <div class="submenuitem" id="newaccount">Nueva cuenta</div>
</div>
<br>
<script>
    $(".submenuitem").click(function(){
        var e = event.target.id;
        $("#configcontent").slideUp("slow", function(){
            $("#configcontent").html("");
            $.get("config_ajax.php?type=" + e, function(data, status){
                if (status === "success") {
                    $("#configcontent").html(data);
                    $("#configcontent").slideDown("slow");
                }
                else {
                    $("#configcontent").html("Error: " + status);
                }
            });
        });
    });
</script>
<div id="configcontent" style="position:relative; z-index: 0"></div>