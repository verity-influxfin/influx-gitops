export default {
    async getExperiencesData({ commit }){
        $.ajax({
            url:'getExperiencesData',
            type:'POST',
            dataType:'json',
            success(data){
                commit('mutationExperiencesData',data);
            }
        });
    },
    async getKnowledgeData({ commit }){
        $.ajax({
            url:'getKnowledgeData',
            type:'POST',
            dataType:'json',
            success(data){
                commit('mutationKnowledgeData',data);
            }
        });
    },
    async getSharesData({ commit },params){
        $.ajax({
            url:'getSharesData',
            type:'POST',
            dataType:'json',
            data:{
                filter:params.category
            },
            success(data){
                commit('mutationSharesData',data);
            }
        });
    },
    async getInterviewData({ commit }){
        $.ajax({
            url:'getInterviewData',
            type:'POST',
            dataType:'json',
            success(data){
                commit('mutationInterviewData',data);
            }
        });
    },
    async getNewsData({ commit }){
        $.ajax({
            url:'getNewsData',
            type:'POST',
            dataType:'json',
            success(data){
                commit('mutationNewsData',data);
            }
        });
    }
}