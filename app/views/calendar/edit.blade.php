@extends('layout/mainTheme')

@section('header')
   
    <!-- fullCalendar 2.2.5-->
    <link href="../plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media='print' />

    <style type="text/css">
      .space{
        
        float: right;
       // background:#fff;
      //  color: red;
      }
      #latest_events li{
        font-size:16px;
      }

      #eventDetails{width:100%;border:1px solid #d2d6de;resize:none;min-height:70px;}
      #wait{z-index:50000;position:fixed;top:0px;left:0px;width: 100%;height: 100%;
        background:rgba(255,255,255,0.1);display: none;color: #000;}
        .load{width: 100x;height: 100px;
          background: transparent;
        }
        .cursor{cursor:pointer; color: #fff;}
    </style>


@stop

  
@section('content')

        <div id='wait'>
          <div id='wait_inner_content'>
            <center><img src='../../img/loading1.gif' class='load'></center>
          </div>
        </div>

       
       <?php $className=profileController::get_class(); ?>
        
        <div class="box box-solid box-{{$className}} no-padding col-md-11">
          <div class="box-header with-border">
            <div class="box-title">Upcoming Events</div>
          </div>
          <div class="box-body"> 
           
        
        <div class="row">

          <div class="col-md-7">
              <div class="box box-solid box-{{$className}} box-{{ProfileController::get_class()}}">
                <div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div><!-- /.col -->

            <div class="col-md-5">
              <div class="box box-solid box-{{$className}} box-solid" data-step="1" data-intro="Add New Event To Faculty's Calendar" data-position='left'>
                <div class="box-header with-border">
                  <h3 class="box-title">Create Event</h3>
                </div>
                <div class="box-body">
                  <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                    <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                    <ul class="fc-color-picker" id="color-chooser">
                      <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>                                           
                      <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                      <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                    </ul>
                  </div><!-- /btn-group -->
                  <input id="new-event" type="text" class="form-control" placeholder="Event Title">
                  <textarea placeholder="Event Detials" class='form-control' id='eventDetails'></textarea>
                  <button id="add-new-event" type="button" class="btn btn-block btn-primary btn-flat">Add New Event</button>

                </div>
              </div>
              <div class="box box-solid box-{{$className}}" data-step="2" data-intro="Delete Events Created By You" data-position='left'>
                <div class="box-header with-border">
                  <h4 class="box-title">Draggable Events</h4>
                </div>
                <div class="box-body">
                  <!-- the events -->
                  <div id='external-events'>
                    
                    
                  </div>
                  <div class="box box-{{$className}} ">
                <div class="box-header with-border">
                    <h4 class="box-title">Latest Events</h4>
                  </div>
                  
                <div class="box-body">
                  <!-- the events -->
                <div id='latest_events' style='width:100%;'>
                  <?php $events=EventController::allUserEvents(); ?>
                  @foreach($events as $event)
                    <div class='external-event cursor'style='background-color:{{$event->backgroundColor}}'> {{$event->title}} <div class="btn space btn-danger btn-xs" onclick='deleteEvent({{$event->id}},event)'>X</div> </div>
                  @endforeach
                </div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
                </div><!-- /.box-body -->
              </div><!-- /. box -->

              

            </div><!-- /.col -->
            
          </div><!-- /.row -->

        </div>

      </div>
       


    <!-- jQuery UI 1.11.1 -->
    <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js" type="text/javascript"></script>
    <script src="../plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
    <!-- Page specific script -->
    <script type="text/javascript">
      $(function () {

        var length=($(document).height())/2-100;
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

        //ini_events($('#external-events div.external-event'));

        /* initialize the calendar
         -----------------------------------------------------------------*/

        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();

                var yy=new Date(y, m, d, 12, 0);
                //alert(yy);

        $('#calendar').fullCalendar({
            header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
     
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events:{url:'/calendar/event/getUserEvents',
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
                borderColor:copiedEventObject.borderColor,backgroundColor:copiedEventObject.backgroundColor},
              success:function(data){
                $("#wait").hide();
                copiedEventObject.id=data;
                /*
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                */
                $('#calendar').fullCalendar('refetchEvents');

                //alert("event Sucessfully Added");
                $('#latest_events').prepend("<div class='external-event cursor'style='background-color:"+copiedEventObject.backgroundColor+"'>"+originalEventObject.title+"<div class='btn btn-danger btn-xs pull-right' onclick='deleteEvent("+data+",event)'>X</div></div>");
                alert("Event Sucessfully Added");
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
                alert("Event Sucessfully updated");
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
                alert("sucessfully Deleted"); 
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

    
    </script>

 @stop