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
                    const bookingData = [
                        newSlot,
                        slot.memb.firstName
                    ]
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
        const slotAndFirstName = [...data.state.slots];
        allSlots.forEach((slot)=>{
            if(slotAndFirstName[0].includes(slot.firstChild.attributes[0].value) || slot.firstChild.attributes[0].value < actualTime){
                slot.parentNode.classList.add("reserved");
                const nameLocation = document.querySelector('.booking-name');
                nameLocation.innerText = slotAndFirstName[1];
            }
        });
    },

    init: () => {
        slotComponent.token = document.querySelector('input').value;
        slotComponent.checkDisplayedSlots();
        console.log(slotComponent.timeSlots);
    },
}

export default slotComponent;