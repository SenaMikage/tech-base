<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8"/>
    <title>m5-1</title>
</head>

<body>

<?php
$dsn = 'mysql:dbname="ユーザ名";host=localhost';
$user = 'ユーザ名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));     

$sql = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "pass TEXT,"
    . "day TEXT"
    .");";
$stmt = $pdo->query($sql);


if ($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $day = date("Y/m/d H:i:s");
    $password = $_POST["password"];

 //新規投稿   
 if(isset($_POST["comment"]) && !empty($_POST["comment"])){   
    if (empty($_POST["editnumber"])) {
        $sql = "INSERT INTO test1 (name, comment, pass, day) VALUES (:name, :comment, :pass, :day)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
    $stmt->bindParam(':day', $day, PDO::PARAM_STR);
    $stmt->execute();
        
    //投稿編集    
    }elseif (isset($_POST["editnumber"]) && !empty($_POST["editnumber"])) {
    $editnum = intval($_POST["editnumber"]);
    $id = $editnum; //変更する投稿番号
    $sql = 'UPDATE test1 SET name=:name,comment=:comment,pass=:pass,day=:day WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':day', $day, PDO::PARAM_STR);
    $stmt->execute();
        }$enum="";
        
    }
 }      
    //投稿削除    
    if (isset($_POST["delete"]) && !empty($_POST["delete"])) {
        $delnum = intval($_POST["delete"]);
        $delpass = $_POST["delpassword"];
        $stmt = $pdo->prepare("SELECT pass FROM test1 WHERE id = :id");
        $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        $storedPass = $result['pass'];
        if($delpass==$storedPass){
        $sql = 'delete from test1 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $delnum, PDO::PARAM_INT);
        $stmt->execute();
        }
    }
    //編集番号指定
    if(isset($_POST["edit"]) && !empty($_POST["edit"])){
        
        $editnum=intval($_POST["edit"]);
        $editpass=$_POST["editpassword"];
        $stmt = $pdo->prepare("SELECT id, name, comment, pass FROM test1 WHERE id = :id");
        $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        $storedPass = $result['pass'];
        $storedNum = $result['id']; 
        $storedName = $result['name'];
        $storedComment =$result['comment'];

            if($storedNum == $editnum && $editpass==$storedPass) {
                $enum=$storedNum;
                $ename=$storedName;
                $ecomment=$storedComment;
                $epass=$storedPass;
            }   
    }
    
    

?>
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
<hr>
<?php
if($_SERVER["REQUEST_METHOD"] === "POST"){
    $sql = 'SELECT * FROM test1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'];
        echo $row['day'].'<br>';
    echo "<hr>";
    }

}?>

</body>
</html>
