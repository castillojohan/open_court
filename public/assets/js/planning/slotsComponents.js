import data from "./data.js";

const slotComponent = {
    
    timeSlots : [],

    init: () => {
        slotComponent.loadReservedSlots();
        slotComponent.checkDisplayedSlots();
    },

    loadReservedSlots : () => {
        const slotsReserved = [...data.state.slots];
        slotsReserved.forEach((slot) => {
            slotComponent.timeSlots.push(slot.startReservation);
        });
    },

    checkDisplayedSlots : () => {
        const actualTime = new Date().toISOString();
        const allSlots = document.querySelectorAll('tbody td:first-child');
        allSlots.forEach((slot)=>{
            if(slotComponent.timeSlots.includes(slot.firstChild.attributes[0].value) || slot.firstChild.attributes[0].value < actualTime){
                slot.parentNode.classList.add("reserved");
            }
        })
    }
}

export default slotComponent;