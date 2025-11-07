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
    <div id="messagesBox" class="d-flex w-100 h-100" data-me-id="<?php echo htmlspecialchars($meId, ENT_QUOTES); ?>" data-recipient-id="<?php echo htmlspecialchars($recipientId, ENT_QUOTES); ?>">
        <?php

        foreach ($messages as $message) {
            if ($message->getAuthorId() == $meId) {
                echo "<section class='messageBox mbox-right'>";
            } else {
                echo "<section class='messageBox mbox-left'>";
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