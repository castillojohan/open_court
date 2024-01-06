
const slider = {
    articles : [
        {
            pics : "pexels-pixabay-209977.jpg",
            title : "Yinnack Naoh en concert",
            author: "JeanJean01",
            content:"Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit culpa incidunt fuga nam aperiam vel, reiciendis vitae possimus accusantium tempore adipisci a magni commodi rerum saepe eligendi, eaque repellendus alias? Unde tempora eaque cupiditate libero. Sapiente assumenda esse cum nobis, aspernatur provident voluptatem, obcaecati expedita velit, perferendis fugit aut reiciendis voluptatibus dignissimos nisi aliquam quo minus vel repellendus veritatis dolores eligendi vitae? Voluptatum dolore a atque quo cum reprehenderit asperiores sequi laboriosam! Exercitationem eligendi facere rem, quod molestiae ex eveniet!",
            date: "16 Aout 2023",
            comments : "26",
            views : "165"
        },
        {
            pics  : "pexels-raj-tatavarthy-171568.jpg", 
            title : "Open PsO 2024, le 2 février 2024",
            author: "Paulo63",
            content:"Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit culpa incidunt fuga nam aperiam vel, reiciendis vitae possimus accusantium tempore adipisci a magni commodi rerum saepe eligendi, eaque repellendus alias? Unde tempora eaque cupiditate libero. Sapiente assumenda esse cum nobis, aspernatur provident voluptatem, obcaecati expedita velit, perferendis fugit aut reiciendis voluptatibus dignissimos nisi aliquam quo minus vel repellendus veritatis dolores eligendi vitae? Voluptatum dolore a atque quo cum reprehenderit asperiores sequi laboriosam! Exercitationem eligendi facere rem, quod molestiae ex eveniet!",
            date: "8 Aout 2023",
            comments : "15",
            views : "100"
        },
        {
            pics  : "pexels-mudassir-ali-1619860.jpg", 
            title : "Cours de tennis pour adultes",
            author: "JeanJean01",
            content:"Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit culpa incidunt fuga nam aperiam vel, reiciendis vitae possimus accusantium tempore adipisci a magni commodi rerum saepe eligendi, eaque repellendus alias? Unde tempora eaque cupiditate libero. Sapiente assumenda esse cum nobis, aspernatur provident voluptatem, obcaecati expedita velit, perferendis fugit aut reiciendis voluptatibus dignissimos nisi aliquam quo minus vel repellendus veritatis dolores eligendi vitae? Voluptatum dolore a atque quo cum reprehenderit asperiores sequi laboriosam! Exercitationem eligendi facere rem, quod molestiae ex eveniet!",
            date: "20 Aout 2023",
            comments : "36",
            views : "450"
        }
    ],

    currentArticle : 0,
    articlesList : [],

    init : () => {
        const articles = [...slider.articles];
        articles.forEach((article) => {
            slider.buildItems(article);
        });
        slider.goToSlide(0);
        slider.binding();
    },

    goToSlide : (newSlidePosition) => {
        slider.articlesList[slider.currentArticle].classList.remove('active');
        slider.articlesList[newSlidePosition].classList.add('active');

        slider.currentArticle = newSlidePosition;
    },

    binding : () => {
        document.querySelector('.right-arrow').addEventListener('click', slider.handleRightClick);
        document.querySelector('.left-arrow').addEventListener('click', slider.handleLeftClick);
    },
    
    handleRightClick: () => {
        const newCurrentSlide = slider.currentArticle === slider.articlesList.length -1 ? 0 : slider.currentArticle + 1 ;
        slider.goToSlide(newCurrentSlide);
    },

    handleLeftClick: () => {
        const newCurrentSlide = slider.currentArticle <= 0 ? slider.articlesList.length -1 : slider.currentArticle -1 ;
        slider.goToSlide(newCurrentSlide);
    },

    buildItems: (element) => {
        const parentDiv = document.querySelector('.main__slider')

        // building elements div , img , h3
        const childDiv = document.createElement("div");
        childDiv.classList = "slider__display";
        
        // build of img, with src and alt
        const childImg = document.createElement("img");
        childImg.src = `./assets/${element.pics}`;
        childImg.alt = `Image qui représente: ${element.title}`;
        
        //build of div with overlay class and h2
        const childDivOverlay = document.createElement("div");
        childDivOverlay.classList = "slider__overlay";
        const childTitleOverlay = document.createElement("h2");
        childTitleOverlay.innerText = element.title ;
        childDivOverlay.appendChild(childTitleOverlay);

        childDiv.appendChild(childImg);
        childDiv.appendChild(childDivOverlay);
        parentDiv.appendChild(childDiv);

        slider.articlesList.push(childDiv);
    }
}
export default slider;