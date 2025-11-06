const recipientId = new URLSearchParams(window.location.search).get("id");
let oldCountReceiverMessages = 0;
let newCountReceiverMessages = 0;


document.addEventListener("DOMContentLoaded", function () {
    window.addEventListener("load", () => {
        scrollToBottom();
        setInterval(checkMessages, 1000)
    });

    const sendMessageButton = document.getElementById("sendMessageButton");
    const messageTextarea = document.getElementById("messageTextarea");

    sendMessageButton.addEventListener("click", function () {
        const content = messageTextarea.value;

        if (!content || content === "") {
            return;
        }

        sendMessage(content, recipientId);
    });

});


function scrollToBottom() {
    const messagesBox = document.getElementById("messagesBox");
    messagesBox.scrollTop = messagesBox.scrollHeight;
}

function postAjax(page, object) {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open("POST", `index.php?page=${page}`);
    ajaxRequest.setRequestHeader("Content-Type", "application/json");
    ajaxRequest.send(JSON.stringify(object));

    return ajaxRequest;
}

function getAjax(page) {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open("GET", `index.php?page=${page}`);
    ajaxRequest.setRequestHeader("Content-Type", "application/json");
    ajaxRequest.send();

    return ajaxRequest;
}


function sendMessage(content, receiverId) {
    let message = {};
    message.content = content;
    message.receiverId = receiverId;

    const ajaxRequest = postAjax("sendMessageAjax", message);

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState === 4) {
            if (ajaxRequest.status === 200) {
                const messageTextarea = document.getElementById("messageTextarea");
                messageTextarea.value = "";
                appendMessage(content, true);
            }

            console.log(ajaxRequest.status);
            scrollToBottom();
        }
    };
}

function appendMessage(content, isAuthor) {
    const messagesBox = document.getElementById("messagesBox");

    let messageBox = document.createElement("section");
    messageBox.classList.add("messageBox");

    if (isAuthor) {
        messageBox.classList.add("mbox-right");
    } else {
        messageBox.classList.add("mbox-left");
    }

    let message = document.createElement("article");
    message.classList.add("message");
    message.innerHTML = content;

    messageBox.appendChild(message);
    messagesBox.appendChild(messageBox);
}


function checkMessages() {
    if (oldCountReceiverMessages === newCountReceiverMessages) {
        return;
    }

    const ajaxRequest = getAjax(`countReceiverAjax?id=${recipientId}`);

    ajaxRequest.addEventListener ("readystatechange", function(){
        if (ajaxRequest.readyState === 4) {
            if (ajaxRequest.status === 200) {
                const response = JSON.parse(ajaxRequest.responseText);
                console.log(response);
            }

            console.log(ajaxRequest.status);
            scrollToBottom();
        }
    });

    // todo : Ajax -> BDD -> Content du dernier message du receveur -> appendMessage(content, false)

    oldCountReceiverMessages = newCountReceiverMessages;
}

