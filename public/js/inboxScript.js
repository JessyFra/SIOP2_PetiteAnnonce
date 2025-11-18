let recipientId = new URLSearchParams(window.location.search).get("id");
let currentUserId = null;
let oldCountMessages = 0;
let newCountMessages = 0;
let isCountInitialized = false;


document.addEventListener("DOMContentLoaded", function () {
    const messagesBox = document.getElementById("messagesBox");

    if (messagesBox) {
        recipientId = messagesBox.dataset.recipientId || recipientId;
        currentUserId = messagesBox.dataset.meId || null;
    }

    window.addEventListener("load", () => {
        scrollToBottom();

        if (recipientId) {
            checkMessages();
            setInterval(checkMessages, 10);
        }
    });

    const sendMessageButton = document.getElementById("sendMessageButton");
    const messageTextarea = document.getElementById("messageTextarea");

    if (sendMessageButton && messageTextarea) {
        sendMessageButton.addEventListener("click", function () {
            const content = messageTextarea.value;

            if (!content || content.trim() === "") {
                return;
            }

            sendMessage(content, recipientId);
        });
    }
});


function scrollToBottom() {
    const messagesBox = document.getElementById("messagesBox");
    if (!messagesBox) {
        return;
    }

    messagesBox.scrollTop = messagesBox.scrollHeight;
}

function postAjax(page, object) {
    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open("POST", `index.php?page=${page}`);
    ajaxRequest.setRequestHeader("Content-Type", "application/json");
    ajaxRequest.send(JSON.stringify(object));

    return ajaxRequest;
}

function getAjax(page, params = {}) {
    const searchParams = new URLSearchParams({ page });

    Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null && value !== "") {
            searchParams.set(key, value);
        }
    });

    const ajaxRequest = new XMLHttpRequest();
    ajaxRequest.open("GET", `index.php?${searchParams.toString()}`);
    ajaxRequest.setRequestHeader("Content-Type", "application/json");
    ajaxRequest.send();

    return ajaxRequest;
}


function sendMessage(content, receiverId) {
    const trimmedContent = content.trim();

    if (!trimmedContent || !receiverId) {
        return;
    }

    const payload = {
        content: trimmedContent,
        receiverId: receiverId
    };

    const ajaxRequest = postAjax("sendMessageAjax", payload);

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState === 4) {
            if (ajaxRequest.status === 200) {
                const messageTextarea = document.getElementById("messageTextarea");
                if (messageTextarea) {
                    messageTextarea.value = "";
                }
                appendMessage(trimmedContent, true);
                oldCountMessages += 1;
                newCountMessages = oldCountMessages;
            }

            scrollToBottom();
        }
    };
}

function appendMessage(content, isAuthor) {
    const messagesBox = document.getElementById("messagesBox");

    if (!messagesBox) {
        return;
    }

    let messageBox = document.createElement("section");
    messageBox.classList.add("messageBox");

    let message = document.createElement("article");
    message.classList.add("message");

    if (isAuthor) {
        messageBox.classList.add("mbox-right");
        message.classList.add("msg-right");
    } else {
        messageBox.classList.add("mbox-left");
        message.classList.add("msg-left");
    }

    message.textContent = content;

    messageBox.appendChild(message);
    messagesBox.appendChild(messageBox);
}


function checkMessages() {
    if (!recipientId) {
        return;
    }

    const ajaxRequest = getAjax("countMessagesAjax", { id: recipientId });

    ajaxRequest.addEventListener("readystatechange", function() {
        if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
            let response = 0;

            try {
                response = JSON.parse(ajaxRequest.responseText);
            } catch (e) {
                // Rien
            }

            newCountMessages = Number(response) || 0;

            if (!isCountInitialized) {
                oldCountMessages = newCountMessages;
                isCountInitialized = true;
                return;
            }

            if (newCountMessages > oldCountMessages) {
                oldCountMessages = newCountMessages;
                getLastMessage();
                return;
            }

            oldCountMessages = newCountMessages;
        }
    });
}

function getLastMessage() {
    if (!recipientId) {
        return;
    }

    const ajaxRequest = getAjax("getLastMessageAjax", { id: recipientId });

    ajaxRequest.addEventListener("readystatechange", function() {
        if (ajaxRequest.readyState === 4 && ajaxRequest.status === 200) {
            let response = {};

            try {
                response = JSON.parse(ajaxRequest.responseText);
            } catch (e) {
                // Rien
            }

            if (response && response.content) {
                const isAuthor = response.author_id && currentUserId && String(response.author_id) === String(currentUserId);
                appendMessage(response.content, Boolean(isAuthor));
                scrollToBottom();
            }
        }
    });

}



