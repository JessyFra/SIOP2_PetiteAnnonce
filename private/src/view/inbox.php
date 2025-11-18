<?php

$inPm = false;

if (!empty($_SESSION["userID"]) && !empty($_GET["id"])) {
    $authorId = $_SESSION["userID"];
    $recipientId = htmlspecialchars($_GET["id"], ENT_QUOTES);

    $messages = MessageDAO::getAll($authorId, $recipientId);
    $recipient = UserDAO::get($recipientId);

    if (!empty($recipient) && $authorId != $recipientId) {
        $inPm = true;
    }
}

?>

<nav id="privateMessages">
    <?php if ($inPm) { ?>
        <div class="pmBox active">
            <div class="user-avatar">
                <?php echo substr($recipient->getDisplayName(), 0, 1); ?>
            </div>
            <div><?php echo $recipient->getDisplayName() ?></div>
        </div>
    <?php } ?>

    <div class="pmBox">
        <div class="user-avatar">L</div>
        <div>Leurre 1</div>
    </div>

    <div class="pmBox">
        <div class="user-avatar">L</div>
        <div>Leurre 2</div>
    </div>
</nav>

<section id="mainBox">
    <?php

    if ($inPm) {
        echo "<div id='messagesBox' class='d-flex w-100 h-100' data-me-id='" . htmlspecialchars($authorId, ENT_QUOTES) . "' data-recipient-id='" . htmlspecialchars($recipientId, ENT_QUOTES) . "'>";

        if ($messages) {
            foreach ($messages as $message) {
                $classes = $message->getAuthorId() == $authorId ? "messageBox mbox-right" : "messageBox mbox-left";
                $msgClass = $message->getAuthorId() == $authorId ? "message msg-right" : "message msg-left";

                echo "<section class='$classes'><article class='$msgClass'>" .
                        htmlspecialchars($message->getContent(), ENT_QUOTES) .
                        "</article></section>";
            }
        } else {
            echo "<div id='introMessage'>Lancez votre première conversation avec " . $recipient->getDisplayName() . "</div>";
        }

        echo "</div>";
    }

    ?>

    <?php if ($inPm) { ?>
        <div class="d-flex w-100">
            <textarea id="messageTextarea" class="form-control" placeholder="Envoyer un message"></textarea>
            <button id="sendMessageButton" class="btn">Envoyer</button>
        </div>
    <?php } else { ?>
        <div id="introMessage">Lancez une conversation avec un client ou un particulier</div>
    <?php } ?>

</section>