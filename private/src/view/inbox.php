<?php

$inPm = false;

if (!empty($_SESSION["userID"]) && !empty($_GET["id"])) {
    $meId = $_SESSION["userID"];
    $recipientId = htmlspecialchars($_GET["id"], ENT_QUOTES);

    $messages = MessageDAO::getAll($meId, $recipientId);
    $recipient = UserDAO::get($recipientId);

    if (!empty($recipient) && $meId != $recipientId) {
        $inPm = true;
    }
}

?>

<nav id="privateMessages">
    <?php if ($inPm) { ?>
        <div class="pmBox active">
            <div class="user-avatar">
                <?php echo substr($recipient->getGlobalName(), 0, 1); ?>
            </div>
            <div><?php echo $recipient->getGlobalName() ?></div>
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
    <div id="messagesBox" class="d-flex w-100 h-100" data-me-id="<?php echo htmlspecialchars($meId, ENT_QUOTES); ?>" data-recipient-id="<?php echo htmlspecialchars($recipientId, ENT_QUOTES); ?>">
        <?php

        if ($inPm) {
            if ($messages) {
                foreach ($messages as $message) {
                    $classes = $message->getAuthorId() == $meId ? "messageBox mbox-right" : "messageBox mbox-left";
                    $msgClass = $message->getAuthorId() == $meId ? "message msg-right" : "message msg-left";

                    echo "<section class='$classes'><article class='$msgClass'>" .
                            htmlspecialchars($message->getContent(), ENT_QUOTES) .
                            "</article></section>";
                }
            } else {
                echo "<div>Lancez votre première conversation avec <b>" . $recipient->getGlobalName() . "</b></div>";
            }
            }

        ?>
    </div>

    <?php if ($inPm) { ?>
        <div class="d-flex w-100">
            <textarea id="messageTextarea" class="form-control" placeholder="Envoyer un message"></textarea>
            <button id="sendMessageButton" class="btn">Envoyer</button>
        </div>
    <?php } ?>
</section>