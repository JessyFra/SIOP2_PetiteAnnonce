let recipientId = new URLSearchParams(window.location.search).get("id");
let authorId = null;
let oldCountMessages = 0;
let newCountMessages = 0;
let isCountInitialized = false;


document.addEventListener("DOMContentLoaded", function() {
    const messagesBox = document.getElementById("messagesBox");

    if (messagesBox) {
        recipientId = messagesBox.dataset.recipientId || recipientId;
        authorId = messagesBox.dataset.meId || null;
    }

    window.addEventListener("load", () => {
        scrollToBottom();

        if (recipientId) {
            checkMessages();
            setInterval(checkMessages, 100);
        }
    });

    const sendMessageButton = document.getElementById("sendMessageButton");
    const messageTextarea = document.getElementById("messageTextarea");

    if (sendMessageButton && messageTextarea) {
        sendMessageButton.addEventListener("click", function() {
            const content = messageTextarea.value;

            if (!content || content.trim() === "") {
                return;
            } else {
                sendPopup("Un message ne peut pas être vide");
            }

            sendMessage(content, recipientId);
        });

        messageTextarea.addEventListener("keydown", function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                const content = messageTextarea.value;

                if (!content || content.trim() === "") {
                    return;
                } else {
                    sendPopup("Un message ne peut pas être vide");
                }

                sendMessage(content, recipientId);
            }
        });

        messageTextarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }

    const pmsBox = document.getElementsByClassName("pmBox");

    for (let i = 0; i < pmsBox.length; i++) {
        pmsBox[i].addEventListener("click", function(event) {
            const element = event.target;

            window.location.href = "index.php?page=inbox&id=" + element.id;
        });
    }

    const privateMessages = document.getElementById("privateMessages");
    const showPms = document.getElementById("showPms");

    showPms.addEventListener("click", () => {
        privateMessages.classList.toggle("open");
    });
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


function sendMessage(content, recipientId) {
    const trimmedContent = content.trim();

    if (!trimmedContent || !recipientId) {
        return;
    }

    const payload = {
        content: trimmedContent,
        recipientId: recipientId
    };

    const ajaxRequest = postAjax("sendMessageAjax", payload);

    ajaxRequest.onreadystatechange = function() {
        if (ajaxRequest.readyState === 4) {
            if (ajaxRequest.status === 200) {
                const messageTextarea = document.getElementById("messageTextarea");
                if (messageTextarea) {
                    messageTextarea.value = "";
                }
            } else {
                sendPopup("Erreur de connexion");
            }

            scrollToBottom();
        }
    };
}

function sendPopup(message) {
    const popup = document.createElement("div");
    popup.id = "messageBox";
    popup.dataset.message = message;
    document.body.appendChild(popup);

    const script = document.createElement("script");
    script.src = "public/js/popup.js";
    document.body.appendChild(script);
    console.log("Popup")
}

function appendMessage(content, isAuthor, recipientId) {
    const messagesBox = document.getElementById("messagesBox");
    const introMessage = document.getElementById("introMessage");

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

    if (introMessage) {
        introMessage.style.transition = "all .4s";

        requestAnimationFrame(() => {
            introMessage.style.height = "0";
            introMessage.style.opacity = "0";
        });

        setTimeout(() => introMessage.remove(), 400);
    }

    scrollToBottom();
    const privateMessages = document.getElementById("privateMessages");
    const recipientBox = document.getElementById(recipientId);

    if (recipientBox && privateMessages) {
        privateMessages.prepend(recipientBox);
    }
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

            if (response && response.content && response.recipient_id) {
                const isAuthor = response.author_id && authorId && String(response.author_id) === String(authorId);
                appendMessage(response.content, Boolean(isAuthor), response.recipient_id);
                scrollToBottom();
            }
        }
    });

}
