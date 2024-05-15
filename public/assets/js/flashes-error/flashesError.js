const flashesError = {
    init: () => {
        const buttons = document.querySelectorAll('.btn-close');
        for (const button of buttons) {
            button.addEventListener('click', function(event){
                const parent = event.target.parentElement;
                parent.remove();
            })
        }
    }
    
}
export default flashesError;