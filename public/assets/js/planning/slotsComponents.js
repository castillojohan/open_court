import data from "./data.js";

const slotComponent = {
    
    timeSlots : [],
    memberInformations: {},
    token: '',

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
                    const bookingData = newSlot
                    slotComponent.timeSlots.push(bookingData);
                }
                
                //needed to load member's information and send them in post method
                slotComponent.memberInformations = datas.member;
                console.log(slotComponent.memberInformations);

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
        let slotFromDb = [...data.state.slots];
        if (slotFromDb.length < 1){
            slotFromDb =[''];
        }

        allSlots.forEach((slotOnPlanning)=>{
                if(slotOnPlanning.firstChild.attributes[0].value < actualTime){
                    slotOnPlanning.parentNode.classList.add("unavailable"); 
                }
                else if(slotFromDb.includes(slotOnPlanning.firstChild.attributes[0].value)){
                    slotOnPlanning.parentNode.classList.add("reserved"); 
                }
        });
    },

    init: () => {
        slotComponent.token = document.querySelector('input').value;
        slotComponent.checkDisplayedSlots();
    },
}

export default slotComponent;