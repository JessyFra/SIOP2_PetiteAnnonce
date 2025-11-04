document.addEventListener("DOMContentLoaded", function () {
    scrollToBottom();

    window.addEventListener("load", () => {
        scrollToBottom();
    });

    const sendMessageButton = document.getElementById("sendMessageButton");
    const messageTextarea = document.getElementById("messageTextarea");
    const messagesBox = document.getElementById("messagesBox");

    sendMessageButton.addEventListener("click", function () {
        scrollToBottom();

        const content = messageTextarea.value;
        const recipientId = new URLSearchParams(window.location.search).get("id");

        if (!content || content === "") {
            return;
        }

        let message = {};
        message.content = content;
        message.receiverId = recipientId;

        const ajaxRequest = new XMLHttpRequest();
        ajaxRequest.open("POST", "index.php?page=sendMessageAjax");
        ajaxRequest.setRequestHeader("Content-Type", "application/json");
        ajaxRequest.send(JSON.stringify(message));

        ajaxRequest.onreadystatechange = function() {
            if (ajaxRequest.readyState === 4) {
                if (ajaxRequest.status === 200) {
                    messageTextarea.value = "";

                    let messageBox = document.createElement("section");
                    messageBox.classList.add("messageBox");
                    messageBox.classList.add("mbox-left");

                    let message = document.createElement("article");
                    message.classList.add("message");
                    message.innerHTML = content;

                    messageBox.appendChild(message);
                    messagesBox.appendChild(messageBox);
                }

                console.log(ajaxRequest.status);
                scrollToBottom();
            }
        };
    });
});


function scrollToBottom() {
    const messagesBox = document.getElementById("messagesBox");
    messagesBox.scrollTop = messagesBox.scrollHeight;
}
