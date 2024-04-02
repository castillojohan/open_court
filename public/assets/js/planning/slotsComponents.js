import data from "./data.js";

const slotComponent = {
    
    timeSlots : [],
    memberInformations: {},
    membersList: {},
    token: '',
    timeSlotMemberName: '',

    loadReservedSlots : () => {
        return new Promise((resolve, reject)=> {
            const urlToFetch = "http://127.0.0.1:8000/booked-slots";
            fetch(urlToFetch)
            .then((urlResponse)=> {
                return urlResponse.json();
                })
            .then((datas)=>{
                for (const slot of datas.slots) {
                    let bookingData = "";
                    const newSlot = new Date(slot.startAt).toISOString();
                    slot.memb != null 
                        ? bookingData = `${newSlot},${slot.memb.id},${slot.memb.user.id},member`
                        : bookingData = `${newSlot},${slot.lesson.id},${slot.lesson.name},lesson`
                    slotComponent.timeSlots.push(bookingData);
                }
                //needed to load member's information and send them in post method
                slotComponent.memberInformations = datas.currentMember;
                slotComponent.membersList = datas.membersList;

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
            slotFromDb.forEach((slotString)=> {
                if(slotOnPlanning.firstChild.attributes[0].value < actualTime){
                    slotOnPlanning.parentNode.classList.add("unavailable"); 
                }
                else if(slotString.includes(slotOnPlanning.firstChild.attributes[0].value)){
                    const timeSlotMemberOrLessonId = slotString.split(',')[1];
                    const timeSlotUserIdOrLessonName = slotString.split(',')[2];
                    const timeSlotType = slotString.split(',')[3];
                    slotOnPlanning.parentNode.classList.add("reserved");
                    slotOnPlanning.nextSibling.setAttribute('data-values', (`${timeSlotMemberOrLessonId},${timeSlotUserIdOrLessonName}`));
                    // If time slot account ID is equal to current user ( member->user(id)
                    switch (timeSlotType) {
                        case "member":
                            if(timeSlotUserIdOrLessonName == slotComponent.memberInformations.user.id){
                                const memberNameOnSlot = slotComponent.seekMemberOrLesson(timeSlotUserIdOrLessonName);
                                slotOnPlanning.nextSibling.textContent = `Réservation de ma famille - ${memberNameOnSlot}.`
                            }
                            break;
                        case "lesson":
                            slotOnPlanning.nextSibling.textContent = `${timeSlotUserIdOrLessonName}.`
                            break;
                        /* case "event": */
                        default:
                            break;
                    }
                }
            })
        });
    },

    seekMemberOrLesson: (memberId) => {
        const membersArray = [...slotComponent.membersList];
        for(let member = 0; member < membersArray.length ; member++){
            if(membersArray[member].id == memberId){
                return membersArray[member].firstName;
            }
        }
        return "Nom introuvable";
    },

    init: () => {
        slotComponent.token = document.querySelector('input').value;
        slotComponent.checkDisplayedSlots();
    },
}

export default slotComponent;