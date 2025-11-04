document.addEventListener("DOMContentLoaded", function () {
    scrollToBottom();

    window.addEventListener("load", () => {
        scrollToBottom();
    });

    const sendButton = document.querySelector(".sendButton");
    const messageTextarea = document.getElementById("messageTextarea");

    sendButton.addEventListener("click", function () {
        const content = messageTextarea.value;

        if (!content || content === "") {
            return;
        }

        console.log(content);
    });
});


function scrollToBottom() {
    const messagesBox = document.getElementById("messagesBox");
    messagesBox.scrollTop = messagesBox.scrollHeight;
}
