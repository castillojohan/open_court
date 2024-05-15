const bookingHistory = {
    month:[
        "Janvier",
        "Février",
        "Mars",
        "Avril",
        "Mai",
        "Juin",
        "Juillet",
        "Aout",
        "Septembre",
        "Octobre",
        "Novembre",
        "Décembre"
    ], 

    countHours: 0,
    slotData: [],

    init: () => {
        bookingHistory.loadCountHours();
    },

    loadCountHours: async () => {
        await bookingHistory.buildStructure();
        const targetHoursCounted = document.querySelector('h3');
        targetHoursCounted.textContent += `${bookingHistory.slotData.slots.length} heures de réservation`;
    },

    buildStructure: async () => {
        let oldValue = '';
        let oldMember = '';
        let memberSlots = [];
        await bookingHistory.loadTimeSlots();
        const {members, slots} = bookingHistory.slotData;
        
        if(bookingHistory.slotData.slots.length){
            for (const slot of slots) {
                // create an index with member firstName in memberSlots array 
                if(!memberSlots[slot.memb.firstName]){
                    memberSlots[slot.memb.firstName] = []
                }
                const cleanDateTime = bookingHistory.dateWashing(slot.startAt);
                memberSlots[slot.memb.firstName].push(cleanDateTime);
            }
        }
        
        const target = document.querySelector('main section>section');
        for (const member in memberSlots) {
            if (Object.hasOwnProperty.call(memberSlots, member)) {
                
                const slots = memberSlots[member];
                const newDivMember = document.createElement('div'); 
                const newH4Member = document.createElement('h4');
                
                const newUlSLots = document.createElement('ul');
                
                slots.forEach(slot => {
                    const newLiSlot = document.createElement('li');
                    newLiSlot.textContent = slot;
                    const liMonth = newLiSlot.textContent.slice(3, 5);
                    if(liMonth !== oldValue || memberSlots[member] !== oldMember){
                        bookingHistory.createElement('li', newLiSlot, liMonth);
                        oldMember = memberSlots[member];
                        oldValue = liMonth;
                    }
                    newUlSLots.append(newLiSlot);
                });

                newH4Member.textContent = member;
                newDivMember.appendChild(newH4Member);
                newDivMember.appendChild(newUlSLots);
                target.append(newDivMember);
            }
        }
    },

    /**
     * Juste replace date in US format to EU format e.g. 'dd/mm/yyyy hh:mm'
     */
    dateWashing: (dateFromSlot) => {
        const [rawDate, rawTime] = dateFromSlot.split('T')
        const [year, month, day] = rawDate.split('-');
        const europeanDate = `${day}/${month}/${year}`;
        const cleanDateTime = europeanDate + ' ' +rawTime.slice(0,5);
        return cleanDateTime;
    },

    createElement: (tag, target, attr) => {
        const parentEl = target;
        const newliElement = document.createElement(tag);
        newliElement.textContent = bookingHistory.month[parseInt(attr)-1]
        parentEl.prepend(newliElement);
    },

    loadTimeSlots: async () => {
        const urlToFetch = "http://127.0.0.1:8000/account/booking-history"
        try {
            const response = await fetch(urlToFetch, {
                method: 'POST',
            });
            const datas = await response.json();
            bookingHistory.slotData = datas;
        }
        catch(error){
            console.log(error);
            throw error;
        }
    },    
}

document.addEventListener('DOMContentLoaded', bookingHistory.init);