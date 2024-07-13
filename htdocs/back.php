<?php
    header("content-type:text/html;charset=utf8mb4");
    $conn = mysqli_connect("localhost","root","66543986","chat_room");
    if(!$conn){ die("Connect Error: " . mysqli_connect_error()); }
    mysqli_set_charset($conn,"utf8mb4");
    $html = file_get_contents("../wide_docs/aq.html");
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    function show_doc(): void{
        $doc = $GLOBALS["dom"];
        {
            $result = mysqli_query($GLOBALS["conn"], "SELECT * FROM block");
            $row = mysqli_num_rows($result);
            $cont = $doc->getElementById("block_tbody");
            for($i = 0; $i < $row; $i++) {
                $tr = $doc->createElement("tr");
                list($block_word, $replace) = mysqli_fetch_row($result);
                $td1 = $doc->createElement("td", $block_word);
                $td2 = $doc->createElement("td", $replace);
                $tr->appendChild($td1);
                $tr->appendChild($td2);
                $cont->appendChild($tr);
            }
        }
        {
            $result = mysqli_query($GLOBALS["conn"], "SELECT * FROM ban");
            $row = mysqli_num_rows($result);
            $cont = $doc->getElementById("ban_ip_tbody");
            for($i = 0; $i < $row; $i++) {
                $tr = $doc->createElement("tr");
                list($ban_ip) = mysqli_fetch_row($result);
                $td = $doc->createElement("td", $ban_ip);
                $tr->appendChild($td);
                $cont->appendChild($tr);
            }
        }
        {
            $result = mysqli_query($GLOBALS["conn"], "SELECT * FROM chat");
            $row = mysqli_num_rows($result);
            $cont = $doc->getElementById("chat_tbody");
            for($i = 0; $i < $row; $i++) {
                $tr = $doc->createElement("tr");
                list($name, $content, $time, $ip) = mysqli_fetch_row($result);
                $td1 = $doc->createElement("td", $name);
                $td2 = $doc->createElement("td", $content);
                $td3 = $doc->createElement("td", $time);
                $td4 = $doc->createElement("td", $ip);
                $tr->appendChild($td1);
                $tr->appendChild($td2);
                $tr->appendChild($td3);
                $tr->appendChild($td4);
                $cont->appendChild($tr);
            }
        }
        echo $doc->saveHTML();
    }
    if(isset($_GET["password"])){
        $password = $_GET["password"];
        $result = mysqli_query($conn, "SELECT * FROM root_password");
        $line_pass = mysqli_fetch_row($result);
        if($password == $line_pass[0]){
            if(isset($_POST["block-word"]) && isset($_POST["replace-word"]) && $_POST["block-word"] != "" && $_POST["replace-word"] != "") {
                $result = mysqli_query($GLOBALS["conn"], "SELECT * FROM block");
                $row = mysqli_num_rows($result);
                for($i = 0; $i < $row; $i++) {
                    list($block_word, $replace) = mysqli_fetch_row($result);
                    if(strtolower($block_word) == strtolower($_POST["block-word"])) {
                        show_doc();
                        return;
                    }
                }
                $str = "INSERT INTO block(`block_word`, `replace`) VALUES ('".$_POST["block-word"]."','".$_POST["replace-word"]."')";
                mysqli_query($conn, $str);
            }else if(isset($_POST["ban-ip"]) && $_POST["ban-ip"] != "") {
                $result = mysqli_query($GLOBALS["conn"], "SELECT * FROM ban");
                $row = mysqli_num_rows($result);
                for($i = 0; $i < $row; $i++) {
                    list($ban_ip) = mysqli_fetch_row($result);
                    if(strtolower($ban_ip) == strtolower($_POST["ban-ip"])) {
                        show_doc();
                        return;
                    }
                }
                $str = "INSERT INTO ban(`ban_ip`) VALUES ('".$_POST["ban-ip"]."')";
                mysqli_query($conn, $str);
            }
            show_doc();
            unset($_POST);
            $_POST = [];
            @mysqli_close($conn);
            return;
        }
    }
    echo "<meta charset='UTF-8'><h1 style='text-align: center'><h1 style='text-align: center'>密码输入错误！</h1>";
    @mysqli_close($conn);
?>

