<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Answer Challenge</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h2 style="text-align: center">Answer Challenge</h2>
        <?php
        function get_all_by_id($conn, $id) {
            $sql = "SELECT * FROM challenge WHERE id={$id}";
            if (!($result = mysqli_query($conn, $sql))) {
                echo "Error: " . mysqli_error($conn);
                exit();
            } else {
                $row = mysqli_fetch_assoc($result);
            }
            mysqli_free_result($result);
            return $row;
        }
        include 'connect.php';
        
        //kiem tra dang nhap.
        session_start();
        $id = $_SESSION["id"];
        if (!isset($id)) {
            header("Location: login.php");
        } else {
            //tai khoan cua giao vien hay sinh vien.
            $gv = "SELECT name, gv FROM acc WHERE id='{$id}'";
            $result_gv = mysqli_query($conn, $gv);
            if (mysqli_num_rows($result_gv) == 1) {
                $row = mysqli_fetch_assoc($result_gv);
                $gv = $row["gv"];
            }
        }
        $c_id = $_GET["c_id"];
        $row = get_all_by_id($conn, $c_id);
        $c_dir = $row["challenge"];
        $c_suggest = $row["suggestion"];
        echo "
            <table id='table_t'> 
            <tr> 
                <th>Challenge</th> 
                <th>Suggestion</th>
            </tr>
            <tr> 
                <td>".$c_dir."</td> 
                <td>".$c_suggest."</td>
            </tr>";
        echo '
            <form action="challenge_answer.php?c_id='.$c_id.'" method="post">
                <h3>Answer:</h3>
                <input type="text" name="answer" placeholder="Enter answer" required>
                <input type="submit" name="answer_b" value="Answer">
            </form>';
        if (isset($_POST["answer_b"]) && isset($_POST["answer"])) {
            $answer = $_POST["answer"];
            $file_dir = $c_dir . "/" . $answer . ".txt";
            if (file_exists($file_dir)) {
                echo "<h3 style='text-align: center'>Correct !!!</h3>";
                readfile($file_dir);
                echo nl2br("\n\n");
            } else {
                echo "<h3 style='text-align: center'>Incorrect !!!</h3>";
            }
        }
        
        mysqli_free_result($result_gv);
        mysqli_close($conn);
        ?>
        <form action="challenge.php" method="post">
            <button type="submit" name="return">Return</button><br><br>
        </form>
    </body>
</html>
