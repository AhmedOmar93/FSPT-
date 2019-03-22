myApp.controller('calendarDetails',function($scope){
   if(is_member==1){
       active_tape(3);
  }else{
       active_tape(2);
  }  
  $scope.admin=false;
  //is_admin=true;
  if(is_admin){$(".showAdmin").show();}

  if(!calendar_loaded){

    calendar_loaded=true;

     $("#eventDetailsContainer").hide();

     if(is_admin){


      var length=($(document).height())/2-150;
        $("#wait_inner_content").css("margin-top",length);

        /* initialize the external events
         -----------------------------------------------------------------*/
        function ini_events(ele) {
          ele.each(function () {

            var text=$(this).text();
            var details=$("#eventDetails").val();
            
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
              title: $.trim(text), // use the element's text as the event title
              id:1,
              details:details
            };

            //alert(eventObject.title);

            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);

            // make the event draggable using jQuery UI
            $(this).draggable({
              zIndex: 1070,
              revert: true, // will cause the event to go back to its
              revertDuration: 0  //  original position after the drag
            });

          });
        }

      $('#calendar').fullCalendar({
            header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
     
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events:{type:"GET",url:'/calendar/event/getGroupEvents',data:{gId:group.id},
      beforeSend:function(){$("#wait").show();},
      success:function(data){$("#wait").hide();}},
          droppable: true, // this allows things to be dropped onto the calendar !!!
          drop: function (date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
            copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;
            copiedEventObject.backgroundColor = $(this).css("background-color");
            copiedEventObject.borderColor = $(this).css("border-color");
            
           
            $(this).remove();
            
            $.ajax({
              type: "POST",
              url: '/calendar/event/add',
              data: {title:originalEventObject.title,details:originalEventObject.details,start:copiedEventObject.start.format(),
                end:copiedEventObject.start.format(),
                borderColor:copiedEventObject.borderColor,backgroundColor:copiedEventObject.backgroundColor,gId:group.id},
              success:function(data){
                $("#wait").hide();
                copiedEventObject.id=data;
                /*
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                */
                $('#calendar').fullCalendar('refetchEvents');

                //alert("event Sucessfully Added");
                $('#latest_events').prepend("<div class='external-event cursor'style='background-color:"+copiedEventObject.backgroundColor+"'>"+originalEventObject.title+"<div class='btn btn-danger btn-xs pull-right' onclick='deleteEvent("+data+",event)'>X</div></div>");

              },
              error:function(data){
                console.log(data);
              },
              beforeSend:function(){
                $("#wait").show();

              }
            });

          },
          eventClick: function(event, element) {

          var endDate="";
          if(event.end==null){
            endDate=event.start.format();
           // event.allDay=true;
          }else{
            endDate=event.end.format();
          }
          
          $.ajax({
              type: "POST",
              url: '/calendar/event/updateDate',
              data: {id:event.id,start:event.start.format(),end:endDate,allDay:event.allDay},
              success:function(data){
                //alert(data);
                $("#wait").hide();
               // alert("Event Sucessfully updated");
              },
              error:function(data){
                console.log(data);
              },
              beforeSend:function(){
                $("#wait").show();

              }
            });

          },
          eventResize: function(event, delta, revertFunc) {
/*
          if (!confirm(" this okay?")) {
              revertFunc();
          }
*/
          }
        });

        /* ADDING EVENTS */
        var currColor = "#3c8dbc"; //Red by default
        //Color chooser button
        var colorChooser = $("#color-chooser-btn");
        $("#color-chooser > li > a").click(function (e) {
          e.preventDefault();
          //Save color
          currColor = $(this).css("color");
          //Add color effect to button
          $('#add-new-event').css({"background-color": currColor, "border-color": currColor});
        });

        $("#add-new-event").click(function (e) {
          e.preventDefault();
          //Get value and make sure it is not null
          var val = $("#new-event").val();

          if (val.length == 0) {
            return;
          }

          //Create events
          var event = $("<div />");
          event.css({"background-color": currColor, "border-color": currColor, "color": "#fff"}).addClass("external-event");
          event.html(val);
          $('#external-events').prepend(event);
          
          //Add draggable funtionality
          ini_events(event);

          //Remove event from text input
          $("#new-event").val("");
          $("#eventDetails").val("");

        });
      

      
    
     }else{
        $('#calendar').fullCalendar({
            header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
            },
            eventLimit: true, // allow "more" link when too many events
            events:
            {type:"GET",url:'/calendar/event/getAllGroupEvents',data:{gId:group.id},success:function(data){
                //alert(data);
                //console.log(data);
            }},
            eventClick: function(event, element) {

              showEventDetails(event);
            }
            
          });
    }

  }



});


function deleteEvent(eventId,event){
        $.ajax({
              type: "POST",
              url: '/calendar/event/delete',
              data: {id:eventId},
              success:function(data){
              
              var element=$(event.target).closest('div');
              element=element.parent();
              element.css("background-color","#FF3700");
              element.fadeOut(400,function(){
                element.remove();
              //  alert("sucessfully Deleted"); 
              $('#calendar').fullCalendar('refetchEvents');
              $("#wait").hide();

              });
              },
              error:function(data){
                console.log(data);
              },
              beforeSend:function(){
                $("#wait").show();
              }
            });

  }


function showEvent(myEvent){

showEventDetails(myEvent);

}


  function showEventDetails(event){
    $("#eventTitle").html(event.title);
              
              var content="<div class='user-panel'><div class='pull-left image'><img class='img-circle' src='../../../images/"+event.owner.profile_picture+"'/></div><div class='pull-left info user'><p>"+event.owner.first_name+" "+event.owner.last_name+"</p></div></div>";
              content+="<div class='date'>Creation Time : "+event.created_at+"</div>";
           //   content+="<div class='date'>Start : "+event.start+"</div>";
              content+="<div class='date'>Details : "+event.details+"</div>";
              
              if(event.end!=null)
           //   content+="<div>End : "+event.end+"</div>";
               

              $("#eventContent").html(content);
          
              $("#eventContent").html(content);
              $("#container").css('background',event.backgroundColor);
               
               if(event.backgroundColor==""){
               $("#container").css('background',"rgb(0, 115, 183)");
               }

              $("#container").show();
              $("#eventDetailsContainer").show();
  }