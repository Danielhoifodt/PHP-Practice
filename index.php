<?php
require_once "./vendor/autoload.php";
require_once "./db/db.php";

$edit_content_id = 0;
$delete_content = false;
$show_edit_content = "";
$updated = false;

if (isset($_POST["submit_edit"])) {
    $edit_content = $_POST["edit"];
}
if (isset($_GET["edit"])) {
    $edit_content_id = $_GET["edit"];
    $updated = true;

    $stmt_select_edit = $pdo->prepare("SELECT * FROM practice_table WHERE id=:id");
    $stmt_select_edit->bindParam(':id', $edit_content_id, PDO::PARAM_INT);
    $stmt_select_edit->execute();
    $row = $stmt_select_edit->fetch();
    $show_edit_content = $row["text_content"];
}
if (isset($_POST["edit"])) {
    $id = $_POST["id"];
    $text_content_new = $_POST["text_content"];

    $stmt_edit_task = $pdo->prepare("UPDATE practice_table SET text_content=:text_content_new WHERE id=:id");

    $stmt_edit_task->bindParam(':id', $id, PDO::PARAM_INT);

    $stmt_edit_task->bindParam(':text_content_new', $text_content_new);
    $stmt_edit_task->execute();
}
if (isset($_POST["submit"])) {
    $text_content = $_POST["text_content"];
    $stmt_post = $pdo->prepare("INSERT INTO practice_table (text_content)VALUE(:text_content)");

    $stmt_post->bindParam(':text_content', $text_content);
    $stmt_post->execute();
}

if (isset($_POST["delete"])) {
    $delete_content = true;
    $stmt_delete = $pdo->prepare("DELETE FROM practice_table");

    if ($delete_content) {
        $stmt_delete->execute();
    }
    header("Refresh:0");
}
if (isset($_GET["del_task"])) {
    $del_one = $_GET["del_task"];
    $stmt_del_one = $pdo->prepare("DELETE FROM practice_table WHERE id = :id");
    $stmt_del_one->bindParam(':id', $del_one);
    $stmt_del_one->execute();
}
$stmt_get = $pdo->query("SELECT * FROM practice_table");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>PHP Practice</title>
</head>

<body>
    <div class="container border">
        <div class="row">
            <div class="col-7">
                <h1 class="mt-5 mb-5">PHP Practice</h1>
                <form action="index.php" method="post" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $edit_content_id; ?>">
                        <input style="width:445px;" type="text" class="form-control" name="text_content" value="<?php echo $show_edit_content; ?>" placeholder="Skriv inn tekst">
                    </div>
                    <?php if ($updated == true) : ?>
                        <div class="form-group">
                            <input type="submit" class="form-control btn btn-info ml-2" name="edit" value="Endre">
                        </div>
                    <?php else : ?>
                        <div class="form-group">
                            <input type="submit" class="form-control btn btn-primary ml-2" name="submit" value="Opprett">
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <input type="submit" class="form-control btn btn-danger ml-2" name="delete" value="Slett liste">
                    </div>
                </form>
                <br><br>
            </div>
            <div class="col-5 border" style="margin-top:130px;">
                <p style="width:400px;">Dette programmet lar deg opprette tekst på skyen med knappen "opprett", for å å endre det med knappen "endre", eller slette med knappen "slette". Du kan også slette hele listen med knappen "slett liste".</p>
            </div>
        </div>
        <div class="row">
            <div class="col-7">
                <ul class="list-group mb-5">
                    <?php
                    while ($row = $stmt_get->fetch()) {
                        if ($row["text_content"] == null) {
                            echo "";
                        } else {
                            $id = $row["id"];
                            $text_content = $row["text_content"];
                        } ?>
                        <li class="list-group-item"><?php echo $text_content; ?><div class="float-right"><a id="button_change" style="color:#62bfcc;" onclick="onButtonEdit(<?php echo $id; ?>);">Endre</a>&nbsp;&nbsp;<a style="color:red;" href="index.php?del_task=<?php echo $id; ?>">Slette</a></div>
                        </li>
                    <?php
                    } ?>
                </ul>
            </div>
        </div>
    </div>
    <script>
        function onButtonEdit(id) {
            window.location.href = "index.php?edit=" + id;
        }
    </script>
</body>

</html>