@extends('layout/mainTheme')

@section('header')
   
    <!-- fullCalendar 2.2.5-->
    <link href="../plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
    <link href="../plugins/fullcalendar/fullcalendar.print.css" rel="stylesheet" type="text/css" media='print' />
    <style type="text/css">
    .fc-event-container{cursor: pointer;}

    #container{color:#fff;display: none;}
    .box-title{color:#fff;}
    </style>
@stop

  
@section('content')

      <?php $className=profileController::get_class(); ?>
        
        <div class="box box-solid box-{{$className}} no-padding col-md-11">
          <div class="box-header with-border">
            <div class="box-title">Upcoming Events</div>
          </div>
          <div class="box-body"> 
           
        <div class="row">
          
          
            <center>
            <div class='col-md-7'>
            <div class="box box-solid box-{{$className}}">
                <div class="box-body no-padding">
                  <!-- THE CALENDAR -->
                  <div id="calendar"></div>
                </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div>
          </center>

           <div id='eventDetailsContainer'  class='col-md-5'>
              <div id='container'  class="box box-solid ">
                    <div class="box-header with-border">
                      <h4 id='eventTitle' class="box-title">Event Details</h4>
                    </div>
                    <div id='eventContent' class="box-body">
                      
                    </div><!-- /.box-body -->
              </div><!-- /. box -->
            </div>
          

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

        $("#eventDetailsContainer").hide();


        $('#calendar').fullCalendar({
            header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
            },
            eventLimit: true, // allow "more" link when too many events
            events:
            {url:'/calendar/event/getAll',success:function(data){
                //alert(data);
                //console.log(data);
            }},
            eventClick: function(event, element) {


              $("#eventTitle").html(event.title);
              
              var content="<div class='user-panel'><div class='pull-left image'><img class='img-circle' src='../../images/"+event.owner.profile_picture+"'/></div><div class='pull-left info user'><p>"+event.owner.first_name+" "+event.owner.last_name+"</p></div></div>";
              content+="<div class='date realText'>"+event.details+"</div>";
              
              content+="<div class='date'>Creation Time : "+event.created_at+"</div>";
              content+="<div class='date'>Start : "+event.start.format()+"</div>";
              
              if(event.gId!=null){
                content+="<div class='date'>Group : "+event.group.name+"</div>";
              }

              if(event.end!=null)
              content+="<div>End : "+event.end.format()+"</div>";


              $("#eventContent").html(content);

              $("#container").css('background',event.backgroundColor);
               
               if(event.backgroundColor==""){
               $("#container").css('background',"rgb(0, 115, 183)");
               }

              $("#container").show();
              $("#eventDetailsContainer").show();
            }
            
          });
      });
    </script>
 @stop