export default {
    mutationExperiencesData(state,data){
        state.experiences = data;
    },
    mutationKnowledgeData(state,data){
        $.each(data,(index,row)=>{
            data[index].link = `/articlepage/knowledge-${row.id}`;
        });

        state.knowledge = data;
    },
    mutationSharesData(state,data){
        $.each(data,(index,row)=>{
            data[index].link = `/vlog/${row.category}`;
        });
        
        state.shares = data;
    },
    mutationInterviewData(state,data){
        state.interview = data;
    },
    mutationNewsData(state,data){
        $.each(data,(index,row)=>{
            data[index].link = `/articlepage/news-${row.id}`;
        });

        state.news = data;
    },
    mutationUserData(state,data){
        state.userData = data;
    }
}