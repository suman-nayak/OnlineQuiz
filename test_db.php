
<?php

require_once("db.php");

$conn = get_connection();

echo "Database Connected Successfully!";

$conn->close();

?>
