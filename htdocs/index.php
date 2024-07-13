<?php
    header("content-type:text/html;charset=utf8mb4");
    $conn = mysqli_connect("localhost","root","66543986","chat_room");
    if(!$conn){ die("Connect Error: " . mysqli_connect_error()); }
    mysqli_set_charset($conn,"utf8mb4");
    $html = file_get_contents("../wide_docs/ar.html");
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    function getIP(){
        if (isset($_SERVER)){
            $real_ip = $_SERVER["HTTP_X_FORWARDED_FOR"] ?? $_SERVER["HTTP_CLIENT_IP"] ?? $_SERVER["REMOTE_ADDR"];
        } else {
            $real_ip = getenv("HTTP_X_FORWARDED_FOR") ?? getenv("HTTP_CLIENT_IP") ?? getenv("REMOTE_ADDR");
        }
        return $real_ip;
    }
    function judge_ban(): void{
        $pid = getIP();
        $result = mysqli_query($GLOBALS["conn"], "SELECT `ban_ip` FROM ban");
        $row = mysqli_num_rows($result);
        for($l = 0; $l < $row; $l++){
            list($ban_ip) = mysqli_fetch_row($result);
            if ($ban_ip == $pid) {
                @mysqli_close($GLOBALS["conn"]);
                echo "<meta charset='UTF-8'><h1 style='text-align: center'>你的IP已被封禁！</h1>";
                exit();
            }
        }
    }
    function add_chat($name, $content): void {
        try{
            if(trim($name) == "" || strlen($name) < 3 || strlen($name) > 16) { return; }
            $dat = date("Y-m-d H:i:s");
            $ip = getIP();
            $str = "INSERT INTO chat (`name`, `time`, `content`, `ip`) VALUES ('$name', '$dat','$content', '$ip')";
            mysqli_query($GLOBALS["conn"], $str);
            setcookie("username", $name, time() + (3600), "/");
        }catch(Exception){}
    }
    function unicodeDecode($str): string{
        $text = json_encode($str);
        $text = preg_replace_callback('/\\\\\\\\/', function() {return "\\";}, $text);
        return json_decode($text);
    }
    function show_chat(): void {
        $doc = $GLOBALS["dom"];
        $block = mysqli_query($GLOBALS["conn"], "SELECT * FROM block");
        $result = mysqli_query($GLOBALS["conn"], "SELECT * FROM chat ORDER BY `time`");
        $b_row = mysqli_num_rows($block);
        $row = mysqli_num_rows($result);
        if ($row <= 20){$l = $row;}else{
            mysqli_query($GLOBALS["conn"], "DELETE FROM chat LIMIT 1");
            $l = 20;
        }
        @mysqli_data_seek($result, $row - $l);
        $cont = $doc->getElementById("content");
        for ($i = 0; $i < $l; $i++) {
            list($name, $content, $time) = mysqli_fetch_row($result);
            @mysqli_data_seek($block, 0);
            for ($j = 0; $j < $b_row; $j++) {
                list($block_word, $replace) = mysqli_fetch_row($block);
                if($block_word != null && $replace != null) {
                    $name = preg_replace("/".$block_word."/i", $replace, $name);
                    $content = preg_replace("/".$block_word."/i", $replace, $content);
                }
            }
            $name = unicodeDecode($name);
            $span = $doc->createElement("span", $name. "——" . $time . ":");
            $cont->appendChild($span);
            $br = $doc->createElement("br");
            $cont->appendChild($br);
            $b = $doc->createElement("b", $content);
            $cont->appendChild($b);
            $br2 = $doc->createElement("br");
            $cont->appendChild($br2);
        }
        if(isset($_COOKIE["username"])){
            $username = $doc->getElementById("username");
            $username->setAttribute("value", $_COOKIE["username"]);
        }
        echo $doc->saveHTML();
    }
    judge_ban();
    if (isset($_POST["name"]) && isset($_POST["content"])) {
        add_chat($_POST["name"], $_POST["content"]);
        unset($_POST);
        $_POST = [];
        header("Location: index.php");
    }
    show_chat();
    @mysqli_close($conn);
