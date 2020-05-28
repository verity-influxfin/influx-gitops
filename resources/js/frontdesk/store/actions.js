export default {
    async getExperiencesData({ commit }){
        try{
            let res = await axios.post('getExperiencesData');
            commit('mutationExperiencesData',res.data);
        } catch(error){
            console.error('getExperiencesData 發生錯誤');
        }
    },
    async getKnowledgeData({ commit }){
        try{
            let res = await axios.post('getKnowledgeData');
            commit('mutationKnowledgeData',res.data);
        } catch(error){
            console.error('getKnowledgeData 發生錯誤');
        }
    },
    async getSharesData({ commit },params){
        try{
            let res = await axios.post('getSharesData',{filter:params.category});
            commit('mutationSharesData',res.data);
        } catch(error){
            console.error('getSharesData 發生錯誤');
        }
    },
    async getInterviewData({ commit }){
        try{
            let res = await axios.post('getInterviewData');
            commit('mutationInterviewData',res.data);
        } catch(error){
            console.error('getInterviewData 發生錯誤');
        }
    },
    async getNewsData({ commit }){
        try{
            let res = await axios.post('getNewsData');
            commit('mutationNewsData',res.data);
        } catch(error){
            console.error('getNewsData 發生錯誤');
        }
    },
    async getRepaymentList({ commit }){
        try{
            let res = await axios.post('getRepaymentList');
            commit('mutationRepaymentData',res.data);
        } catch(error){
            console.error('getRepaymentList 發生錯誤');
        }
    },
    async getMyInvestment({ commit }){
        try{
            let res = await axios.get('getMyInvestment');
            commit('mutationInvestmentData',res.data);
        } catch(error){
            console.error('getMyInvestment 發生錯誤');
        }
    }
}