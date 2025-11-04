<?php

if (!empty($_SESSION["userID"]) && !empty($_GET["id"])) {
    $recipientId = htmlspecialchars($_GET["id"], ENT_QUOTES);
    $meId = $_SESSION["userID"];
} else {
    header("Location: index.php?page=auth");
    exit;
}

$messages = MessageDAO::getAll($meId, $recipientId);

?>

<nav id="privateMessages">

</nav>

<section id="mainBox">
    <div id="messagesBox" class="d-flex w-100 h-100">
        <?php

        foreach ($messages as $message) {
            if ($message->getAuthorId() == $meId) {
                echo "<section class='messageBox mbox-left'>";
            } else {
                echo "<section class='messageBox mbox-right'>";
            }

        ?>
            <article class="message"><?php echo $message->getContent(); ?></article></section>
        <?php } ?>
    </div>

    <div class="d-flex w-100">
        <textarea id="messageTextarea" class="form-control" placeholder="Envoyer un message"></textarea>
        <button id="sendMessageButton" class="btn btn-primary">Envoyer</button>
    </div>
</section>