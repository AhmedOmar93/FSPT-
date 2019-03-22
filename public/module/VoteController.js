

myApp.factory('all_Votes',function(){
    var votes = {};
    return {
        addVotes:function(paramVotes ){
            votes = paramVotes;
        },
        getVotes:function (){
            return votes;
        }

    };
});


myApp.controller('listController',function($scope,$http,all_Votes,Data){

    active_tape(4);
    $scope.className = className;
    $scope.userId=User.id;
    $scope.path=Data;


    //get all votes inside group as json array
    $http.get('/api/vote/get_all/'+group.id).success(function(data){
        all_Votes.addVotes(data);
        $scope.votes=data;
    }).error();


    $scope. delete_vote=function(vote_id) {
        console.log("here"+vote_id);
        $http.get('/api/delete/vote/'+ vote_id)
            .success(function () {
                $("#vote-"+vote_id).parent().remove();
              //  alert("success in deleting vote! ");

                //$(this).parent().remove();


                          }).error(function () {
                alert("failed in deleting vote! ");
            });
    }

});


myApp.controller('detailsController',function($scope,$http,$routeParams,$location,all_Votes,Data, $sce){

    active_tape(4);
    $scope.color = color;
    $scope.colorName = colorName;
    $scope.className = className;
    $scope.userId=User.id;
    $scope.path=Data;

    if(all_Votes.getVotes()){

        $scope.votes=all_Votes.getVotes();
        $scope.whichItem=$routeParams.itemId;

        $scope.voteId=$scope.votes[$scope.whichItem].id;
        $scope.Item=$scope.votes[$scope.whichItem];

        $scope.Item['chartUrl'] = $sce.trustAsResourceUrl("../../vote-chart?voteId=" + $scope.Item.id);
        //not first item
        if($routeParams.itemId > 0){
            $scope.prevItem=Number($routeParams.itemId)-1;
        }else{
            $scope.prevItem=$scope.votes.length-1;
        }

        //not last element
        if($routeParams.itemId < $scope.votes.length-1){
            $scope.nextItem=Number($routeParams.itemId)+1;
        }else{
            $scope.nextItem=0;
        }

        if($routeParams.itemId < 0 || $routeParams.itemId > $scope.votes.length-1){
            $scope.mess='The Vote Page not Found!';
        }else{
            $scope.artistNumber=(Number($routeParams.itemId)+1)+'.';
        }



        $http.get('/api/options/get_all/'+$scope.voteId)

            .success(function(data){
                $scope.options=data.optionsResult;
                $scope.selectedOptionId=data.prevSelectedOptionResult;

                // console.log("success1"+data.optionsResult);
                 console.log("success2"+data.prevSelectedOptionResult);
            }).error(function(){ alert("fail");
            });




        $scope.delete_choice=function(choice_id) {
            $http.get('/api/delete/option/'+choice_id )
                .success(function (data) {
                    alert("success in deleting option! ");

                    $("#"+choice_id).parent().remove();

                }).error(function () {
                    alert("failed in deleting option! ");
                });
        }


        $scope.select_choice=function(choice_id) {
            $http.get('/api/select/option/'+choice_id )
                .success(function (data) {
                    alert("success in selecting option! ");
                    $window.location.reload();
                    //$route.reload();
                    //   $location.path('/details/:$routeParams.itemId');
                }).error(function () {
                    alert("failed in selecting option! ");
                });
        }

    }



});


myApp.controller('notFoundController',function($scope){

    $scope.myMessages={notFound:"This Page Not Found!",mess2:'ay 7aga'};

});




myApp.controller('addVoteController',function($scope,$http,$location,$routeParams){

    active_tape(4);
    $scope.myClass = className;

    $scope.choices = [{name: 'choice1'}, {name: 'choice2'}];

    $scope.addNewChoice = function() {
        var newItemNo = $scope.choices.length+1;
        $scope.choices.push({'name':'choice'+newItemNo});
    };


    $scope.showAddChoice = function(choice) {
        return choice.name === $scope.choices[$scope.choices.length-1].name;
    };


    $scope.add_vote=function(vote,form){

        $scope.optionsAll={};
        if(!form.$error.required){

            // $scope.vote.group_id=group.id;

            $scope.myData=angular.toJson($scope.vote);
            console.log($scope.vote);
            $http.post('/api/add/vote/'+group.id,$scope.myData).
                success(function(data, status, headers, config){

                    $location.path('/addVote');
                    // $scope.optionsAll= $scope.choices;
                    $scope.optionsAll= angular.copy($scope.choices);
                    var v_id = data;
                    $scope.optionsAll.push({'id':v_id});
                    //console.log($scope.choices);

                    $http.post('/api/option/add',$scope.optionsAll).
                        success(function(data){
                            alert("success in adding vote with it's options");

                            $location.path('/VoteList');
                        })
                        .error(function(){
                            alert("failed in adding options");
                        });


                })
                .error(function(data, status, headers, config){
                    $scope.result=data;
                    alert("failed in adding vote");
                });

        }else{
            $scope.result="Check Valid Data of form";
        }}

});
