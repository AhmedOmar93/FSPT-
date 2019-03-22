
var myApp=angular.module('projectDetailsApp',['ngRoute','ngSanitize']);
//window.location="#/questions";
//global variable
myApp.factory('Data',function(){
  return {
    path_prefix:path_prefix,
    image_path:path_prefix+'images'
  };
});


myApp.config(function($routeProvider) {
  $routeProvider.
 	when('/view/:projectId',{
            templateUrl:path_prefix+'partials/project/view.html',
            controller:'projectDetails'
            
        }).otherwise({
    redirectTo: '/view/0'
  	});

});

myApp.controller('projectDetails',function($scope,$http,$routeParams,Data){
$scope.pId=$routeParams.projectId;
$scope.className=className;
$scope.colorName=colorName;
$scope.color=color;
$scope.path=Data;
$scope.userId=User.id;



$http.get(path_prefix+'/projectMembers/'+$scope.pId).success(function(data){
	$scope.project=data[0];
	$scope.group_members=data[0].group_members;

  for(var i=0;i<$scope.group_members.length;i++){
    var count=0;
    for(var j=0;j<$scope.group_members[i].details.tasks.length;j++){
      if($scope.group_members[i].details.tasks[j].finished==1){
        count++;
      }
    }
    $scope.group_members[i].details.tasksFinished=count;
    
  }

});

$scope.addNewMember=function(){
    
    if(!$scope.addMemberForm.$error.required){
        
      $http.get(path_prefix+'/project/addMember/'+$scope.member.user_code+"/"+$scope.pId).
      success(function(data){
        if(data==-1){
          alert('There is no Student with this user code','error');
        }else if(data==-2){
          alert("Already Member",'error');
        }else if(data==0){
          alert("Fail , Try Again Later",'error');
        }else{
          alert("Student Successfully Added",'success');
          data.details.tasks=[];
          $scope.project.group_members.unshift(data);
          console.log(data);

        }

      });
      $scope.member.user_code="";

    }else{
      alert("Enter User Code",'error');
    }


}

$scope.toggle_body=function(event){
  $(event.target).parent().parent().find('.box-body').toggle({'duration':150});

}

$scope.addNewTask=function(addTaskForm,task){
  
  if(!addTaskForm.$error.required){
    if(task.end_at>task.start_at){
      task.group_id=$scope.pId;
      $http.post(path_prefix+"/task/add",task).success(function(data){
        if(data==-1){
          alert("Fail,Try Again",'error');
        }else{
          alert("Task Successfully Created");
          data.taskTime="Just Now";
          for(var i=0;i<$scope.project.group_members.length;i++){
            if($scope.project.group_members[i].details.id==data.user_id){
              $scope.project.group_members[i].details.tasks.unshift(data);
              if($scope.project.group_members[i].details.tasks.length==0){
                $scope.project.group_members[i].details
              }
            }
          }


        }

      }).error(function(data){
        console.log(data);
      });
    }else{
        alert("End Date Must be After Start Date",'error');    
    }
  }else{
    alert("All Fields Are Required",'error');
  }

}

$scope.markTaskDone=function(task,member){

$http.get(path_prefix+"/task/markTaskDone/"+task.id).success(function(data){
  if(data==1){
    alert("Task Successfully Done");
    task.finished=1;
    member.details.tasksFinished++;
  }else{
    alert("Fail,Try Again Later","error");
  }
});

}

$scope.markTaskUnDone=function(task,member){

$http.get(path_prefix+"/task/markTaskUnDone/"+task.id).success(function(data){
  if(data==1){
    alert("Task Successfully Updated");
    task.finished=0;
    member.details.tasksFinished--;
  
  }else{
    alert("Fail,Try Again Later","error");
  }
});

}
$scope.deleteTask=function(task,member){

$http.get(path_prefix+"/task/delete/"+task.id).success(function(data){
  if(data==1){
    alert("Task Successfully Deleted");
    task.finished=0;
    var member_index=$scope.project.group_members.indexOf(member);
    var task_index=$scope.project.group_members[member_index].details.tasks.indexOf(task);
    $scope.project.group_members[member_index].details.tasks.splice(task_index,1);
    
  }else{
    alert("Fail,Try Again Later","error");

  }
}).error(function(data){
  console.log(data);
});

}




});






$(document).ready(function() {
    angular.bootstrap(document.getElementById('chat-staff'), ['FriendsListApp']);
});

