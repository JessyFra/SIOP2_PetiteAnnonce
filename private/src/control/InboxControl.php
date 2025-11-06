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

    public function countReceiverAjax() {
        $receiverId = $_GET["id"];

        if (empty($_SESSION['userID']) || empty($receiverId)) {
            return;
        }

        $count = MessageDAO::getCountReceiverMessages($_SESSION["userID"], $receiverId);
        $arrayCount = array($count);
        echo json_encode($arrayCount);
    }

}
