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
<?php
if (isset($_COOKIE['authkey'])){
    try {
        $sql=$con->prepare("SELECT * FROM ".$tableuser." WHERE authkey = :authkey");
        $sql->bindParam(':authkey', $_COOKIE['authkey']);
        $sql->execute();
        $row = $sql->fetch(PDO::FETCH_ASSOC);
        if (is_null($row['user']) or ($row['authkey'] != $_COOKIE['authkey'])) {
            echo "<script languaje='javascript'>alert('".$novalidcookie_text."')</script>";
            exit($novalidcookie_text);
        }
        else {
            $_SESSION['user'] = $row['user'];
            $_SESSION['lang'] = $row['lang'];
            $_SESSION['authkey'] = $row['authkey'];
            $_SESSION['auth'] = true;
        }
    }
    catch (PDOException $e) {
        echo 'index.php Error: ' . $e->getMessage();
    }
}
if (isset($_GET['setcookie'])) {
    if ($_GET['setcookie']) {
        date_default_timezone_set($timezone); //set according with lang
        setcookie('authkey', $_SESSION['authkey'], mktime(22,0,0,7,20,2015)); //fecha (mktime) 3 meses
    }
}

$url = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
if (strpos($url,'index.php') !== false) {
    echo "<script languaje='javascript'>window.open('".substr($url, 0, strpos($url, 'index.php'))."','_self');</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Blobgasth</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="robots" content="noindex">
        
        <link href="styles.css" rel="stylesheet" type="text/css" />
        
        <!-- https://developer.chrome.com/multidevice/android/installtohomescreen -->
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <link rel="manifest" href="manifest.json">
        <meta name="mobile-web-app-capable" content="yes">
        <link rel="shortcut icon" sizes="192x192" href="iconos/launcher-icon-4x.png">
        <!-- https://developer.chrome.com/multidevice/android/installtohomescreen -->
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js">$.noConflict();</script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script>
        jQuery(document).ready(function($){
            $(window).width() <= 768 ? $("#menu").css('left', -$("#menu").outerWidth()) : false
            $("#topmenu").css('height', $("#logo").outerHeight());
            $("#all").css('left', $(window).width() <= 768 ? 0 : $("#menu").outerWidth());
            $("#all").css('top', $("#logo").outerHeight());
            $("#logo").css('left', $(window).width() <= 768 ? 0 : $("#menu").outerWidth());
            $("#content").css('left', -$("#content").outerWidth());
            
            var selected = 'recap';
            $.get(selected + '.php', function(data, status){
                if (status === "success") {
                    $("#content").html(data);
                }
                else {
                    $("#content").html("Error: " + status);
                }
                $("#content").animate({
                    left: 0
                }, "slow", "swing");
                $(".menuitem").css("background-color", $("#menu").css('background-color'));
                $("#" + selected).css("background-color", "#F9F9F9");
            });
            
            $("#menubutton").click(function(){
                $("#menu").animate({
                    left: parseInt($("#menu").css('left'),10) == 0 ? -$("#menu").outerWidth() : 0
                }, "slow", "swing");
                $("#menubutton").toggle();
                event.stopPropagation();
            });
            if ($(window).width() <= 768) {
                $("html").click(function(){
                    $("#menu").animate({
                        left: -$("#menu").outerWidth()
                    }, "slow", "swing");
                    $("#menubutton").show();
                });
            }
            
            $(window).resize(function() {
                if ($(window).width() <= 768) {
                    $("#menu").hide();
                    $("#menubutton").show();
                }
                else {
                    $("#menu").show();
                    $("#menubutton").hide();
                }
            });
            
            $(".menuitem").click(function(){
                selected = event.target.id;
                $(".menuitem").css("background-color", $("#menu").css('background-color'));
                $("#" + selected).css("background-color", "#F9F9F9");
                if ($(window).width() <= 768) {
                    $("#menu").animate({
                        left: -$("#menu").outerWidth()
                    }, "slow", "swing");
                        $("#menubutton").show();
                }
                $("#content").animate({
                    left: -$("#content").outerWidth()
                }, "slow", "swing", function() {
                    switch (selected) {
                        case ('newpass'):
                            var page = 'user.php?type=newpass';
                            break;
                        case ('logout'):
                            var page = 'user.php?logout=true';
                            break;
                        default:
                            var page = selected + '.php';
                            break;
                    }
                    $.get(page, function(data, status){
                        if (status === "success") {
                            $("#content").html(data);
                        }
                        else {
                            $("#content").html("Error: " + status);
                        }
                        $("#content").animate({
                            left: 0
                        }, "slow", "swing");
                    });
                });
                event.stopPropagation();
            });
            
        });
        </script>
    </head>
    <body>
<?php 
    if (isset($_SESSION['auth'])) { 
        if ($_SESSION['auth']) {
?>
            <img src="iconos/ic_menu_black_48dp.png" alt="menu" id="menubutton">
            <div id="menu" align="center">
                <div id="topmenu" style="border-bottom: 1px solid;"></div>
                <div class="menuitem" id="recap"><?php echo $recap_text; ?></div>
                <div class="menusep">|</div>
                <div class="menuitem" id="activities"><?php echo $activities_text; ?></div>
                <div class="menusep">|</div>
                <div class="menuitem" id="common"><?php echo $commonexpenses_text; ?></div>
                <div class="menusep">|</div>
                <div class="menuitem" id="newact"><?php echo $newact_text; ?></div>
                <div class="menusep">|</div>
                <div class="menuitem" id="newpass"><?php echo $changepass_text; ?></div>
                <div class="menusep">|</div>
                <div class="menuitem" id="config"><?php echo $config_text; ?></div>
                <div class="menusep">|</div>
                <div class="menuitem" id="logout"><?php echo $logout_text; ?></div>
                <div id="copyright" align="right" style="position:absolute; bottom:0"><a href="LICENSE">Copyright (C) 2015  bercianor</a></div>
            </div>
            <div id="all">
                <div id="logo">Blobgasth</div>
                <div id="content"></div>
                <?php include 'footer.php'; ?>
            </div>
<?php
        }
        else {
?>
            <form id="login" action="user.php" method="post">
                <?php echo $user_text; ?>: <input type="text" class="formelem" name="user" placeholder="<?php echo $user_text; ?>"><br>
                <?php echo $password_text; ?>: <input type="password" class="formelem" name="password" placeholder="<?php echo $password_text; ?>"><br>
                <br>
                <input type="submit" class="formelem" name="login" value="<?php echo $send_text; ?>">
            </form>
<?php
            include 'footer.php';
        }
    }
    else {
?>
        <form id="login" action="user.php" method="post">
            <?php echo $user_text; ?>: <input type="text" class="formelem" name="user" placeholder="<?php echo $user_text; ?>"><br>
            <?php echo $password_text; ?>: <input type="password" class="formelem" name="password" placeholder="<?php echo $password_text; ?>"><br>
            <br>
            <input type="submit" class="formelem" name="login" value="<?php echo $send_text; ?>">
        </form>
<?php
        include 'footer.php';
    }
?>
    </body>
</html>
