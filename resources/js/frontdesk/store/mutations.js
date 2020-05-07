export default {
    mutationExperiencesData(state,data){
        state.experiences = data;
    },
    mutationKnowledgeData(state,data){
        data.forEach((item,key)=>{
            data[key].link = `/articlepage/knowledge-${item.id}`;
        });
        state.knowledge = data;
    },
    mutationSharesData(state,data){
        data.forEach((item,key)=>{
            data[key].link = `/vlog/${item.category}`;
        });
        state.shares = data;
    },
    mutationInterviewData(state,data){
        state.interview = data;
    },
    mutationNewsData(state,data){
        data.forEach((item,key)=>{
            data[key].link = `/articlepage/news-${item.id}`;
        });
        state.news = data;
    },
    mutationUserData(state,data){
        state.userData = data;
    }
}