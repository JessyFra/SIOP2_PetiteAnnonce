<?php

if (!empty($_SESSION["userID"]) && !empty($_GET["id"])) {
    $receiverId = htmlspecialchars($_GET["id"], ENT_QUOTES);
    $authorId = $_SESSION["userID"];
} else {
    header("Location: index.php?page=auth");
    exit;
}

$messages = MessageDAO::getAll($authorId, $receiverId);

?>

<section class="privateMessages">

</section>

<section class="messagesBox">

    <textarea class="form-control messageTextarea" placeholder="Envoyer un message"></textarea>
    <button class="btn btn-primary sendButton ">Envoyer</button>
</section>