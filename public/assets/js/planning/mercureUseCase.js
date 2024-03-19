const mercureUseCase = {

    init:()=>{
        const url = JSON.parse(document.getElementById('mercure-url').textContent);
        const eventSource = new EventSource(url);
        eventSource.onmessage = (evt) => {
            console.log(evt);
        }
    }
}
export default mercureUseCase;