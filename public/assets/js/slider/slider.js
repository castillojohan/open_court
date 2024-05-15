import domModule from "./domModule.js";

const slider = {

    currentArticle : 0,
    

    init : () => {
        domModule.init();
        slider.goToSlide(0);
        slider.binding();
    },

    goToSlide : (newSlidePosition) => {
        domModule.articlesList[slider.currentArticle].classList.remove('active');
        domModule.articlesList[newSlidePosition].classList.add('active');

        slider.currentArticle = newSlidePosition;
    },

    binding : () => {
        document.querySelector('.right-arrow').addEventListener('click', slider.handleRightClick);
        document.querySelector('.left-arrow').addEventListener('click', slider.handleLeftClick);
    },
    
    handleRightClick: () => {
        const newCurrentSlide = slider.currentArticle === domModule.articlesList.length -1 ? 0 : slider.currentArticle + 1 ;
        slider.goToSlide(newCurrentSlide);
    },

    handleLeftClick: () => {
        const newCurrentSlide = slider.currentArticle <= 0 ? domModule.articlesList.length -1 : slider.currentArticle -1 ;
        slider.goToSlide(newCurrentSlide);
    },

}
export default slider;