import data from "./data.js";

const slotComponent = {
    
    timeSlots : [],

    loadReservedSlots : () => {
        return new Promise((resolve, reject)=> {
            const urlToFetch = "http://127.0.0.1:8000/booked-slots";
            fetch(urlToFetch)
            .then((urlResponse)=> {
                return urlResponse.json();
                })
            .then((datas)=>{
                for (const slot of datas.slots) {
                    const newSlot = new Date(slot.startAt).toISOString();
                    slotComponent.timeSlots.push(newSlot);
                }
                console.log(datas.member);
                if(data.state.slots !== slotComponent.timeSlots){
                    data.state.slots = slotComponent.timeSlots;
                }
                resolve();
            })
            .catch((error)=>{
                reject(error);
            })
        })
    },

    checkDisplayedSlots : async () => {
        try{
            await slotComponent.loadReservedSlots();
        }catch(error){
            console.error("Une erreur s'est produite", error.message);
        }
        slotComponent.reserveSlot();
    },

    reserveSlot : () => {
        const actualTime = new Date().toISOString();
        const allSlots = document.querySelectorAll('tbody.planning td:first-child');
        allSlots.forEach((slot)=>{
            if(data.state.slots.includes(slot.firstChild.attributes[0].value) || slot.firstChild.attributes[0].value < actualTime){
                slot.parentNode.classList.add("reserved");
            }
        });
    },

    init: () => {
        slotComponent.checkDisplayedSlots();
    },
}

export default slotComponent;