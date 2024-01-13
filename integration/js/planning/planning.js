import data from "./data.js";

const planning = {

    timeSlotCount : 22,
    timeSlots : [],
    
    init : () => {
        planning.verifySlots();
        planning.buildItems();

        const trCollection = document.querySelectorAll("table tr");
        trCollection.forEach( tableRow => {
            document.addEventListener('click', planning.handleTrClick);
        });
        
        
    },
    
    handleTrClick : (event) => {
        //* it's ok i got datetime value in this way.
        const timeValue = event.target.firstElementChild.firstChild.attributes.dateTime.value;
    },

    buildItems : () => {
        const actualTime = new Date();
        const tableParent = document.querySelector('table');
        
        //* Build thead + tr who contain time & datetime
        const theadChild = document.createElement('thead');
        const thChild = document.createElement('th');
        const timeChild = document.createElement('time');
        timeChild.dateTime = actualTime.toISOString();
        timeChild.innerText = actualTime.toLocaleDateString('fr');
        
        thChild.appendChild(timeChild);
        theadChild.appendChild(thChild);
        tableParent.appendChild(theadChild);

        //* Trying to build the whole table
        const tbodyChild = document.createElement('tbody');
        for (let slot = 8; slot < planning.timeSlotCount ; slot++){
            const trChild = document.createElement('tr');
            
            const firstTdChild = document.createElement('td');
            const secondTimeChild = document.createElement('time');
            secondTimeChild.innerText = `${slot}h - ${slot+1}h`;
            /* TEST */
            const slotTime =  new Date(actualTime);
            slotTime.setHours(slot+1);
            slotTime.setMinutes(0);
            slotTime.setSeconds(0);
            slotTime.setMilliseconds(0);
            secondTimeChild.dateTime = slotTime.toISOString();
            const timeToCompare = Date.parse(secondTimeChild.dateTime);

            if(planning.timeSlots.includes(timeToCompare)){
                trChild.classList.add('reserved');
            }
            /* ENDTEST */
            firstTdChild.append(secondTimeChild);
            
            const secondTdChild = document.createElement('td');
            secondTdChild.innerText = "Méteo du jour-vent-température";

            trChild.appendChild(firstTdChild);
            trChild.appendChild(secondTdChild);

            tbodyChild.appendChild(trChild);
        }
        tableParent.appendChild(tbodyChild);
    },

    verifySlots : () => {
        const slotsReserved = [...data.courts.timeSlots];
        slotsReserved.forEach((slot) => {
            const convertedSlots = Date.parse(slot.startReservation);
            planning.timeSlots.push(convertedSlots);
        })
    }
}
export default planning;