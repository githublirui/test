<?php
var_dump($_POST);
if (!empty($_POST)) {
    $con = $_POST['test'];
    var_dump(json_decode($con, true));
}
?>
<!DOCTYPE html>
<html>
    <body>

        <form method="POST" action="#">
            <input type="text" name="test" />
            <input type="submit" />
        </form>
    </body>
</html>