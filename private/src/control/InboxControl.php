<?php

include_once 'private/src/model/DAO/MessageDAO.php';

class InboxControl {
    public function inbox() {
        include_once 'private/src/view/inbox.php';
    }

    public function sendMessageAjax() {
        header('Content-Type: application/json');
        $message = json_decode(file_get_contents("php://input"));

        if (!empty($message->content) && !empty($_SESSION['userID']) && !empty($message->receiverId)) {
            MessageDAO::insert($message->content, $_SESSION['userID'], $message->receiverId);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Missing data']);
        }
    }

}
