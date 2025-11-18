<?php

if (!empty($_SESSION["userID"]) && !empty($_GET["id"])) {
    $recipientId = htmlspecialchars($_GET["id"], ENT_QUOTES);
    $meId = $_SESSION["userID"];
} else {
    header("Location: index.php?page=auth");
    exit;
}

if ($recipientId == $meId) {
    echo '<script>history.back()</script>';
    exit;
}

$messages = MessageDAO::getAll($meId, $recipientId);

?>

<nav id="privateMessages">
    <div class="pmBox">
        <div class="user-avatar">A</div>
        <div>Administrateur</div>
    </div>
</nav>

<section id="mainBox">
    <div id="messagesBox" class="d-flex w-100 h-100" data-me-id="<?php echo htmlspecialchars($meId, ENT_QUOTES); ?>" data-recipient-id="<?php echo htmlspecialchars($recipientId, ENT_QUOTES); ?>">
        <?php foreach ($messages as $message) {
            $classes = $message->getAuthorId() == $meId ? "messageBox mbox-right" : "messageBox mbox-left";
            $msgClass = $message->getAuthorId() == $meId ? "message msg-right" : "message msg-left";

            echo "<section class='$classes'><article class='$msgClass'>" .
                    htmlspecialchars($message->getContent(), ENT_QUOTES) .
                    "</article></section>";
        } ?>
    </div>

    <div class="d-flex w-100">
        <textarea id="messageTextarea" class="form-control" placeholder="Envoyer un message"></textarea>
        <button id="sendMessageButton" class="btn">Envoyer</button>
    </div>
</section>