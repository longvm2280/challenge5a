<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
        <title>Homework</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h2 style="text-align: center">Home work</h2>
        <?php
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
        
        if (!file_exists("upload/")) {
            mkdir("upload/");
        }
        
        $col_1st = '<table id="table_t"> 
                    <tr> 
                        <th>File name</th> 
                        <th>Download</th>
                        <th>Danh sach bai lam</th>
                    </tr>';
        if ($gv == 0) {
            $col_1st = '<table id="table_t"> 
                    <tr> 
                        <th>File name</th> 
                        <th>Download</th>
                        <th>Upload bai lam</th>
                    </tr>';
        }
        echo $col_1st;
        
        $list = "SELECT * FROM file WHERE gv_or_sv='1'";
        $result_list = mysqli_query($conn, $list);
        if (mysqli_num_rows($result_list) > 0) {
            $numRows = mysqli_num_rows($result_list);
            for ($i = 0; $i < $numRows; $i++) {
                $row = mysqli_fetch_assoc($result_list);
                $file_id = $row["id"];
                $name = $row["file_name"];
                $upload_name = "upload".$file_id;
                $answer_name = "answer".$file_id;

                $col_next = '
                        <tr> 
                            <td>'.$name.'</td> 
                            <td>
                            <form method="post">
                            <a download href="upload/'.$name.'">
                            <button type="button" name="download">Dowload</button>
                            </form>
                            </td>
                            <td>
                            <form action="home_work.php" method="post" enctype="multipart/form-data">
                                <input type="file" name="file" id="file"><br><br>
                                <input type="submit" name="'.$upload_name.'" value="Upload">
                            </form>
                            </td>
                        </tr>';
                if ($gv == 1) {
                    $col_next = '
                        <tr> 
                            <td>'.$name.'</td> 
                            <td>
                            <form method="post">
                            <a download href="upload/'.$name.'">
                            <button type="button" name="download">Dowload</button>
                            </form>
                            </td>
                            <td>
                            <form action="answer_list.php?question_id='.$file_id.'" method="post">
                                <button type="submit" name="answer_list">List</button>
                            </form>
                            </td>
                        </tr>';
                }
                echo $col_next;
                
                if (isset($_POST["{$upload_name}"])) {
                    $target_dir = "upload/";
                    $target_file = $target_dir . basename($_FILES["file"]["name"]);
                    $file_name = basename($_FILES["file"]["name"]);
                    if (!file_exists($target_file)) {
                        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
                        $add_file = "INSERT INTO file (file_name, gv_or_sv, uploader_id, question_id) VALUES ('{$file_name}', '{$gv}', '{$id}', '{$file_id}')";
                        if (!mysqli_query($conn, $add_file)) {
                            echo "Error ". mysqli_error($conn);
                        }
                        echo "<meta http-equiv='refresh' content='0'>";
                    }
                }
            }
        }
        
        ?>
        <?php if ($gv == 1) { ?>
            <form action="home_work.php" method="post" enctype="multipart/form-data">
                <h3>Upload new home work:</h3>
                <input type="file" name="file" id="file">
                <input type="submit" name="upload" value="Upload"><br><br>
            </form>
        <?php
        if (isset($_POST["upload"])) {
            $target_dir = "upload/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            $file_name = basename($_FILES["file"]["name"]);
            if (!file_exists($target_file)) {
                move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
                $add_file = "INSERT INTO file (file_name, gv_or_sv, uploader_id) VALUES ('{$file_name}', '{$gv}', '{$id}')";
                if (!mysqli_query($conn, $add_file)) {
                    echo "Error ". mysqli_error($conn);
                }
                echo "<meta http-equiv='refresh' content='0'>";
            }
        }
        ?>
        <?php } ?>
        <form action="info.php" method="post">
            <button type="submit" name="return">Return</button><br><br>
        </form>
    </body>
</html>
