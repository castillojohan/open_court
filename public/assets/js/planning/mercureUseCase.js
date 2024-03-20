import slotComponent from "./slotsComponents.js";

const mercureUseCase = {

    init:()=>{
        const url = JSON.parse(document.getElementById('mercure-url').textContent);
        const eventSource = new EventSource(url);
        eventSource.onmessage = (evt) => {
            slotComponent.checkDisplayedSlots();
        }
    }
}
export default mercureUseCase;