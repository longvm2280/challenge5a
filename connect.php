<!DOCTYPE html>
<html>
    <body>
        <?php
        $hostname = "localhost";
        $username = "id14954445_root";
        $password = "L0ng@vm2280,";
        $dbname = "id14954445_class_db";

        $conn = mysqli_connect($hostname, $username, $password, $dbname);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
            exit();
        }
        ?>
    </body>
</html>
