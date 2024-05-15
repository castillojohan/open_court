import bindListeners from "./bindListeners.js";
import data from "./data.js";

const domModule = {
    
    timeSlotCount : 15,

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

    buildTableItems: () => {
        const tableParent = document.querySelector('table');
        const tbodyChild = document.createElement('tbody');
        tbodyChild.classList.add('planning');
        
        const currentDate = new Date();
        const currentDay = document.querySelector('thead th td:nth-child(2)').textContent.slice(0,2);
        currentDate.setDate(currentDay);
        currentDate.setHours(7);
        currentDate.setMinutes(0);
        currentDate.setSeconds(0);
        currentDate.setMilliseconds(0);

        for (let slot = 0; slot < domModule.timeSlotCount ; slot++){
            const trChild = document.createElement('tr');
            
            const firstTdChild = document.createElement('td');
            const secondTimeChild = document.createElement('time');
            
            const startTime = new Date(currentDate);
            startTime.setHours(startTime.getHours() + slot);
            const endTime = new Date(startTime);
            endTime.setHours(endTime.getHours() + 1);

            secondTimeChild.innerText = `${startTime.toLocaleString([], {hour: '2-digit', minute: '2-digit'})}h - ${endTime.toLocaleString([], {hour: '2-digit', minute: '2-digit'})}h`;
            
            const dateToLocaleIsoString = domModule.convertToLocalISOString(startTime.toLocaleString());
            secondTimeChild.dateTime = dateToLocaleIsoString;

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

    /**
     * Needed to format a locale datetime into en-US format 
     * @param dateLocaleString | date.toLocalString()
     * @returns string formated in ISO dateTime YYYY-MM-DDTHH:MM:SS.000Z
     */
    convertToLocalISOString: (dateLocaleString) => {
        const extractedDate = dateLocaleString.split(' ');
        
        const date = extractedDate[0].split('/');
        const newDateOrderdate = date.reverse().join('-'); 
        const hour = extractedDate[1];

        const dateToLocaleIsoString = `${newDateOrderdate}T${hour}.000Z`;
        return dateToLocaleIsoString;
    },

    domUpdate : () => {
        const tableParent = document.querySelector('table');
        tableParent.textContent = '';
    }
}

export default domModule;