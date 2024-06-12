import getData from "./get-data.js";
import courtOccupation from "./court-occupation.js";

const stats = {
    
    dataStats : null,

    init : async () => {
        const datas = await getData.requestData();
        stats.dataStats = datas;
        
        for(const courtName in stats.dataStats.courts){
            const percentageOccupation = stats.dataStats.courts[courtName]; 
            courtOccupation.generateElements(courtName, percentageOccupation);
        }
        courtOccupation.animeStats();
    },
}
document.addEventListener('DOMContentLoaded', stats.init);