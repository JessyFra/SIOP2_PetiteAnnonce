<?php

include_once 'private/src/model/DAO/MessageDAO.php';

class InboxControl {
    public function inbox() {
        include_once 'private/src/view/inbox.php';
    }

    public function sendMessageAjax() {
        header('Content-Type: application/json');
        $message = json_decode(file_get_contents("php://input"));

        if (empty($message->content) || empty($_SESSION['userID']) || empty($message->receiverId)) {
            return;
        }

        MessageDAO::insert($message->content, $_SESSION['userID'], $message->receiverId);
    }

    public function countMessagesAjax() {
        header('Content-Type: application/json');
        $receiverId = $_GET["id"];

        if (empty($_SESSION['userID']) || empty($receiverId)) {
            echo json_encode(0);
            return;
        }

        $count = MessageDAO::getCountMessages($_SESSION["userID"], $receiverId);
        echo json_encode($count);
    }

    public function getLastMessageAjax() {
        header('Content-Type: application/json');
        $receiverId = $_GET["id"];

        if (empty($_SESSION['userID']) || empty($receiverId)) {
            echo json_encode(new stdClass());
            return;
        }

        $lastMessage = MessageDAO::getLastMessage($_SESSION["userID"], $receiverId);
        echo json_encode($lastMessage ?: new stdClass());
    }


}
