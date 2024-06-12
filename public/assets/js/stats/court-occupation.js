const courtOccupation = {
    
    generateElements: (courtName, percentage) => {
        const targetElement = document.querySelector('.courts-statistiques');
        
        const divParentElement = document.createElement('div');
        divParentElement.id = courtName;
        divParentElement.classList.add('stat-parent');

        const divChildContainerElement = document.createElement('div');
        divChildContainerElement.classList.add('stat-container');
        
        const spanProgessElement = document.createElement('span');
        spanProgessElement.classList.add('progress-value');
        spanProgessElement.innerText = percentage;

        const spanNameCourtElement = document.createElement('span');
        spanNameCourtElement.classList.add('court-name');
        spanNameCourtElement.innerText = `${courtName}`;

        divChildContainerElement.append(spanProgessElement);
        divParentElement.append(divChildContainerElement, spanNameCourtElement);
        targetElement.appendChild(divParentElement);
    },

    animeStats: () => {
        const statContainer = document.querySelectorAll('.stat-container');
        for (const stat of statContainer) {
        
            const progressValue = stat.querySelector('.progress-value');
  
            let startValue = 0,
            endValue = progressValue.innerText,
            speed = 100;
            
            const progress = setInterval(() => {
                startValue++;
                progressValue.innerText = `${startValue} %`;
                stat.style.background = `conic-gradient(var(--nav-links-hover)${startValue * 3.6}deg, var(--theme-secondary) 0deg)`;
                if(startValue >= endValue){
                    clearInterval(progress);
                }
            }, speed);
        }
    }
}
export default courtOccupation;