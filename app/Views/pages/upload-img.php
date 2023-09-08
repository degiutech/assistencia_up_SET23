<?php
var_dump($_FILES);
echo'<br>';

move_uploaded_file($_FILES['arquivo']['tmp_name'], '../public/uploads/img/' . $_FILES['arquivo']['name']);

echo '<hr>';

?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="arquivo">
    <input type="submit" value="Enviar">
</form>