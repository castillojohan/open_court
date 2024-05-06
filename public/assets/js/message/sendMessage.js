const sendMessage = {

    init: () => {
        const buttonsElements = document.querySelectorAll(".button.send");
        for (const element of buttonsElements) {
            element.addEventListener('click', sendMessage.handleSendButtonClick);
        }
    },

    handleSendButtonClick: (event) => {
        event.preventDefault();
        const inputTargetElement = event.target.parentNode.parentNode;
        const textAreaEl = inputTargetElement.childNodes[3].value;
        sendMessage.fetchDataToSend(event.target.href, textAreaEl);
    },

    fetchDataToSend: async (linkToFetch, messageContent) => {
        const recipientId = sendMessage.getRecipientId(linkToFetch);
        const response = await fetch(linkToFetch, 
            {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.getElementById('send-message').value,
                },
                body: JSON.stringify({
                    recipient: parseInt(recipientId),
                    content : messageContent
                })
            }
        );
        const messageSend = await response.json();
        //sendMessage.getUpdate(messageSend);
    },

    getRecipientId: (url) => {
        const recipientId = url.split('/');
        return recipientId[recipientId.length -1];
    },
    
    getUpdate: async () => {
        const fetchUrl = "http://127.0.0.1:8000/account/messages/";
        try{
            const response = await fetch(fetchUrl,
            {
                method: 'GET',
                header:{
                    'Content-Type': 'application/json'
                },
            });
            const datas = await response.json();
        } 
        catch(error){
            throw error;
        }
    }
}

export default sendMessage;