<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8"/>
    <title>m3-5</title>
</head>

<body>

<?php
$fname = "m3-5.txt";

if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $day = date("Y/m/d H:i:s");
    $password = $_POST["password"];
    
 if(isset($_POST["comment"]) && !empty($_POST["comment"])){   
    if (empty($_POST["editnumber"])) {
        
        if (file_exists($fname)) {
            $lines = file($fname);
            $lastLine = end($lines);
            $lastLineData = explode("<>", $lastLine);
            $num = intval($lastLineData[0]) + 1;
        } else {
            $num = 1;
        }$fp = fopen($fname, "a");
        fwrite($fp, $num . "<>" . $name . "<>" . $comment . "<>" . $day . "<>" . $password ."<>". PHP_EOL);
        fclose($fp);
        
    }elseif (isset($_POST["editnumber"]) && !empty($_POST["editnumber"])) {
    $editnum = intval($_POST["editnumber"]);
    if (file_exists($fname)) {
        $lines = file($fname);
        $fp = fopen($fname, "w");
        foreach ($lines as $line) {
            $data = explode("<>", $line);
            $postNum = intval($data[0]);
            if ($postNum === $editnum) {
                fwrite($fp,$editnum . "<>" . $name . "<>" . $comment . "<>" . $day . "<>" .$password."<>".PHP_EOL);
            } else {
                fwrite($fp, $line);
            }
        }
        fclose($fp);
        }$enum="";
        
    }
 }      
        
    if (isset($_POST["delete"]) && !empty($_POST["delete"])) {
        $delnum = intval($_POST["delete"]);
        $delpass = $_POST["delpassword"];
        if (file_exists($fname)) {
            $lines = file($fname);
            $fp = fopen($fname, "w");
            foreach ($lines as $line) {
                $data = explode("<>", $line);
                $postNum = intval($data[0]);
                if ($postNum !== $delnum || $delpass !== $data[4]) {
                        fwrite($fp, $line);
                    
                }
            }
            fclose($fp);
        }
    }
    if(isset($_POST["edit"]) && !empty($_POST["edit"])){
        
        $editnum=intval($_POST["edit"]);
        $editpass=$_POST["editpassword"];
         if (file_exists($fname)) {
            $lines = file($fname);
            foreach ($lines as $line) {
                $data = explode("<>", $line);
                $postNum = intval($data[0]);
                
                if($postNum == $editnum && $editpass==$data[4]) {
                    $enum=$data[0];
                    $ename=$data[1];
                    $ecomment=$data[2];
                    $epass=$data[4];}   
                }
        }
    }

}?>
    
    <form action="" method="post">
    <input type="text" name="name" placeholder="名前" value="<?php if(isset($ename)) {echo $ename;} ?>"><br>
    <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($ecomment)) {echo $ecomment;} ?>"><br>
    <input type="text" name="password" placeholder="パスワード" value="<?php if(isset($epass)) {echo $epass;} ?>">
    <input type="hidden" name="editnumber" value="<?php if(isset($enum)) {echo $enum;} ?>">
    <input type="submit" value="送信">
    <br><br>
    <input type="number" name="delete" placeholder="削除対象番号"><br>
    <input type="text" name="delpassword" placeholder="パスワード">
    <input type="submit" value="削除">
    <br><br>
    <input type="number" name="edit" placeholder="編集対象番号"><br>
    <input type="text" name="editpassword" placeholder="パスワード">
    <input type="submit" value="編集">
</form>

<br>

<?php
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $lines = file($fname);
        foreach ($lines as $line) {
           $data = explode("<>", $line);
            echo $data[0] . " " . $data[1] . " " . $data[2] . " " . $data[3] . "<br>";
    }

}?>

</body>
</html>
