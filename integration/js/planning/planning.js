import data from "./data.js";

const planning = {

    timeSlotCount : 22,
    timeSlots : [],
    
    init : () => {
        planning.loadReservedSlots();
        planning.buildItems();

        const trCollection = document.querySelectorAll("table tr");
        trCollection.forEach( tableRow => {
            tableRow.addEventListener('click', planning.handleTrClick);
        });
        
        planning.checkDisplayedSlots();
    },
    
    handleTrClick : (event) => {
        const cleanedEvent = event.stopPropagation();
        //* Maybe my element must have an attribute 'datetime' , will be easiest, stopPropagation seems not work ( pick childs and modify event.target )
        //
        console.log(event.target.childNodes[0].firstChild.dateTime)
        //const timeValue = event.target.firstElementChild.firstChild.attributes.dateTime.value;
        
    },

    buildItems : () => {
        const actualTime = new Date();
        console.log(actualTime.toLocaleString());
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
            
            const slotTime =  new Date(actualTime);
            slotTime.setHours(slot+1);
            slotTime.setMinutes(0);
            slotTime.setSeconds(0);
            slotTime.setMilliseconds(0);
            secondTimeChild.dateTime = slotTime.toISOString();

            firstTdChild.append(secondTimeChild);
            
            const secondTdChild = document.createElement('td');
            secondTdChild.innerText = "Méteo du jour-vent-température";

            trChild.appendChild(firstTdChild);
            trChild.appendChild(secondTdChild);

            tbodyChild.appendChild(trChild);
        }
        tableParent.appendChild(tbodyChild);
    },

    loadReservedSlots : () => {
        const slotsReserved = [...data.courts.timeSlots];
        slotsReserved.forEach((slot) => {
            planning.timeSlots.push(slot.startReservation)
        })
    },

    checkDisplayedSlots : () => {
        const actualTime = new Date().toISOString();
        const allSlots = document.querySelectorAll('td:first-child');
        allSlots.forEach((slot)=>{
            if(planning.timeSlots.includes(slot.firstChild.attributes[0].value) || slot.firstChild.attributes[0].value < actualTime){
                slot.parentNode.classList.add("reserved");
            }
        })
    }
}
export default planning;