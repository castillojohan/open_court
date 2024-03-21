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

    countHours: document.querySelectorAll('li.hours'),

    init: () => {
        bookingHistory.loadCountHours();
        bookingHistory.cleanDateTime();
    },

    loadCountHours: () => {
        const targetHoursCounted = document.querySelector('h3');
        targetHoursCounted.textContent += `${bookingHistory.countHours.length} heures de réservation`;
    },

    cleanDateTime: () => {
        let oldValue = '';
        let oldMember = '';
        for (const liElement of bookingHistory.countHours) {
            const parentElement = liElement.parentElement;
            const h4MemberName = parentElement.parentElement.children[0].textContent;
            const liMonth = bookingHistory.month[parseInt(liElement.innerText.split('/')[1])-1];
            if((liMonth !== oldValue) || (h4MemberName !== oldMember)){
                bookingHistory.createElement('li', liElement, liMonth);
                oldValue = liMonth;
                oldMember = h4MemberName;
            }
        }
    },

    createElement: (tag, target, attr) => {
        const parentEl = target;
        const newliElement = document.createElement(tag);
        newliElement.textContent = attr
        parentEl.prepend(newliElement);
    }
}

document.addEventListener('DOMContentLoaded', bookingHistory.init);