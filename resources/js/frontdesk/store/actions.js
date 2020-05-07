export default {
    getExperiencesData({ commit }){
        $.ajax({
            url:'getExperiencesData',
            type:'POST',
            dataType:'json',
            success(data){
                commit('mutationExperiencesData',data);
            }
        });
    },
    getKnowledgeData({ commit }){
        $.ajax({
            url:'getKnowledgeData',
            type:'POST',
            dataType:'json',
            success(data){
                commit('mutationKnowledgeData',data);
            }
        });
    },
    getSharesData({ commit },params){
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
    getInterviewData({ commit }){
        $.ajax({
            url:'getInterviewData',
            type:'POST',
            dataType:'json',
            success(data){
                commit('mutationInterviewData',data);
            }
        });
    },
    getNewsData({ commit }){
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