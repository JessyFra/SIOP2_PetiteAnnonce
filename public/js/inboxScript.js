document.addEventListener("DOMContentLoaded", function () {
    window.addEventListener("load", () => {
        scrollToBottom();
        setInterval(checkMessages, 250)
    });

    const sendMessageButton = document.getElementById("sendMessageButton");
    const messageTextarea = document.getElementById("messageTextarea");

    sendMessageButton.addEventListener("click", function () {
        const content = messageTextarea.value;
        const recipientId = new URLSearchParams(window.location.search).get("id");

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

function sendAjax(page, object) {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open("POST", `index.php?page=${page}`);
    ajaxRequest.setRequestHeader("Content-Type", "application/json");
    ajaxRequest.send(JSON.stringify(object));

    return ajaxRequest;
}


function sendMessage(content, receiverId) {
    let message = {};
    message.content = content;
    message.receiverId = receiverId;

    const ajaxRequest = sendAjax("sendMessageAjax", message);

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


let oldCountMessages = 0;
let newCountMessages = 0;

function checkMessages() {
    if (oldCountMessages === newCountMessages) {
        return;
    }

    // todo : Ajax -> BDD -> Content du dernier message du receveur -> appendMessage(content, false)

    oldCountMessages = newCountMessages;
}

