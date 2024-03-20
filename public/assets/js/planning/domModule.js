import bindListeners from "./bindListeners.js";
import data from "./data.js";

const domModule = {
    
    timeSlotCount : 22,

    init: () => {
        domModule.buildTableHead(data.state.currentDate);
        domModule.buildTableItems();
        bindListeners.init();
    },

    buildTableHead : (usedDate) => {
        domModule.domUpdate();
        const tableParent = document.querySelector('table');
        
        //* Build thead + tr who contain time & datetime
        const theadChild = document.createElement('thead');
        const leftArrow = document.createElement('td');
        leftArrow.innerText = '<';
        
        const thChild = document.createElement('th');
        
        const rightArrow = document.createElement('td');
        rightArrow.innerText = '>';
        
        const timeChild = document.createElement('td');
        timeChild.dateTime = usedDate.toISOString();
        timeChild.innerText = usedDate.toLocaleDateString('fr');
        
        thChild.append(leftArrow);
        thChild.appendChild(timeChild);
        thChild.append(rightArrow);
        theadChild.appendChild(thChild);
        tableParent.appendChild(theadChild);
        },

        buildTableItems : () => {
        //* Trying to build the whole table
        const tableParent = document.querySelector('table');
        const tbodyChild = document.createElement('tbody');
        tbodyChild.classList.add('planning');
        for (let slot = 8; slot < domModule.timeSlotCount ; slot++){
            const trChild = document.createElement('tr');
            
            const firstTdChild = document.createElement('td');
            const secondTimeChild = document.createElement('time');
            secondTimeChild.innerText = `${slot}h - ${slot+1}h`;
            
            const slotTime =  new Date(data.state.currentDate);
            slotTime.setHours(slot+1);
            slotTime.setMinutes(0);
            slotTime.setSeconds(0);
            slotTime.setMilliseconds(0);
            secondTimeChild.dateTime = slotTime.toISOString();

            firstTdChild.append(secondTimeChild);
            
            const secondTdChild = document.createElement('td');

            const thirdTdChild = document.createElement('td');
            thirdTdChild.innerText = "Méteo du jour-vent-température";

            trChild.appendChild(firstTdChild);
            trChild.appendChild(secondTdChild);
            trChild.appendChild(thirdTdChild);
            
            tbodyChild.appendChild(trChild);
        
        }
        tableParent.appendChild(tbodyChild);
    },

    domUpdate : () => {
        const tableParent = document.querySelector('table');
        tableParent.textContent = '';
    }
}

export default domModule;