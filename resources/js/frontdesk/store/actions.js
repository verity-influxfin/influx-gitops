export default {
    async getExperiencesData({ commit },type =''){
        try{
            let res = await axios.post(`${location.origin}/getExperiencesData`,{type});
            commit('mutationExperiencesData',res.data);
        } catch(error){
            console.error('getExperiencesData 發生錯誤');
        }
    },
    async getKnowledgeData({ commit }){
        try{
            let res = await axios.post(`${location.origin}/getKnowledgeData`);
            commit('mutationKnowledgeData',res.data);
        } catch(error){
            console.error('getKnowledgeData 發生錯誤');
        }
    },
    async getVideoData({ commit },params){
        try{
            let res = await axios.post(`${location.origin}/getVideoData`,{filter:params.category});
            commit('mutationVideoData',res.data);
        } catch(error){
            console.error('getVideoData 發生錯誤');
        }
    },
    async getNewsData({ commit }){
        try{
            let res = await axios.post(`${location.origin}/getNewsData`);
            commit('mutationNewsData',res.data);
        } catch(error){
            console.error('getNewsData 發生錯誤');
        }
    },
    async getRepaymentList({ commit }){
        try{
            let res = await axios.post(`${location.origin}/getRepaymentList`);
            commit('mutationRepaymentData',res.data);
        } catch(error){
            console.error('getRepaymentList 發生錯誤');
        }
    },
    async getMyInvestment({ commit }){
        try{
            let res = await axios.get(`${location.origin}/getMyInvestment`);
            commit('mutationInvestmentData',res.data);
        } catch(error){
            console.error('getMyInvestment 發生錯誤');
        }
    }
}