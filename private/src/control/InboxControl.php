<?php

include_once 'private/src/model/DAO/MessageDAO.php';
include_once 'private/src/model/DAO/UserDAO.php';

class InboxControl {
    public function inbox() {
        include_once 'private/src/view/inbox.php';
    }

    public function sendMessageAjax() {
        header('Content-Type: application/json');
        $message = json_decode(file_get_contents("php://input"));

        if (empty($message->content) || empty($_SESSION['userID']) || empty($message->recipientId)) {
            return;
        }

        MessageDAO::insert($message->content, $_SESSION['userID'], $message->recipientId);
    }

    public function countMessagesAjax() {
        header('Content-Type: application/json');
        $recipientId = $_GET["id"];

        if (empty($_SESSION['userID']) || empty($recipientId)) {
            echo json_encode(0);
            return;
        }

        $count = MessageDAO::getCountMessages($_SESSION["userID"], $recipientId);
        echo json_encode($count);
    }

    public function getLastMessageAjax() {
        header('Content-Type: application/json');
        $recipientId = $_GET["id"];

        if (empty($_SESSION['userID']) || empty($recipientId)) {
            echo json_encode(new stdClass());
            return;
        }

        $lastMessage = MessageDAO::getLastMessage($_SESSION["userID"], $recipientId);
        echo json_encode($lastMessage ?: new stdClass());
    }


}
