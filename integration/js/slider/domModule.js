import data from "./data.js";

const domModule = {
    
    articlesList : [],
    renderArticles : [],

    init : () => {
        domModule.callMethod();  
    },

/*
 renderItems try to get a collection of items who possess .slider__display class,
 if collection size is stricltry inferior to 1 , 
 he will take the result of builditems function (fetched items with async function), 
 else we load data in property renderArticles with collection recovered from renderer and load the result of getItems. 
*/
    callMethod : () => {
        const renderItems = document.querySelectorAll(".slider__display");
        const buildOrGet = renderItems.length < 1 
            ? domModule.buildItems 
            : (domModule.articlesList = [...renderItems], domModule.getItems) ;
        buildOrGet();
    },

    // this method will used in case of fetched data , need to be changed ( become async, await function ) 
    buildItems : () => {
        const articles =  [...data.articles]
        articles.forEach(article => {
            const parentDiv = document.querySelector('.main__slider')

            // build elements div , img , h3
            const childDiv = document.createElement("div");
            childDiv.classList = "slider__display";
            
            // build img, with src and alt
            const childImg = document.createElement("img");
            childImg.src = `./assets/${article.pics}`;
            childImg.alt = `Image qui représente: ${article.title}`;
            
            //build div with overlay class and h2
            const childDivOverlay = document.createElement("div");
            childDivOverlay.classList = "slider__overlay";
            const childTitleOverlay = document.createElement("h2");
            childTitleOverlay.innerText = article.title ;
            childDivOverlay.appendChild(childTitleOverlay);
            
            // let insert all of them in their parent .. glurp
            childDiv.appendChild(childImg);
            childDiv.appendChild(childDivOverlay);
            parentDiv.appendChild(childDiv);
    
            domModule.articlesList.push(childDiv); 
        });
    },

    // this method will be used to treat data becomming from renderer ( twig or blade for example )
    getItems : () => {
        domModule.renderArticles.forEach((item) => {
            domModule.articlesList.push(item);
        });
    }
}

export default domModule;