<?php

$inPm = false;

if (!empty($_SESSION["userID"])) {

    $authorId = $_SESSION["userID"];
    $recipientId = null;
    $privates_messages = PrivateMessagesDAO::getAll($authorId);

    if (!empty($_GET["id"])) {
        $recipientId = htmlspecialchars($_GET["id"], ENT_QUOTES);

        $messages = MessageDAO::getAll($authorId, $recipientId);
        $recipient = UserDAO::get($recipientId);

        if (!empty($recipient) && $authorId != $recipientId) {
            PrivateMessagesDAO::create($authorId, $recipientId);
            PrivateMessagesDAO::deleteOldRecipientId($authorId, $recipientId);
            $inPm = true;
        }
    }
} else {
    header("Location: index.php?page=auth");
    exit;
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

    <?php foreach ($privates_messages as $private_messages) { ?>
        <?php $recipientPm = $private_messages->getRecipient() ?>

        <?php if ($recipientPm->getId() != $recipientId) { ?>
            <div class="pmBox">
                <div class="user-avatar">
                    <?php echo substr($recipientPm->getDisplayName(), 0, 1); ?>
                </div>
                <div><?php echo $recipientPm->getDisplayName() ?></div>
            </div>
        <?php } ?>
    <?php } ?>
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