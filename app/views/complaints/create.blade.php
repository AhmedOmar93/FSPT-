
<?php
$color = ProfileController::get_class();
?>

@if($IsAdmin != NULL)
<div>
         <?php $complaints = Complaint::getAllComplaintsByInGroup($groupId);
         // var_dump($complaints);
          ?>

   @if($complaints!=NULL)
        @foreach($complaints as $complaint)

        <div  class="panel box box-<?php echo $color; ?> box-solid"  >
               <div class="box-header bg-<?php echo $color; ?>  with-border" id="complaintId">
                 <div >
                     <h3 class="box-title" >
                         <?php echo $complaint->title ?>

                     </h3>
                     <a class = 'removeComplaint pull-right btn btn-danger btn-xs' href="/delete/complaint/<?php echo $complaint->id ?>">
                         X
                     </a>
                 </div>
                </div>

            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="box-body">

                    <div class="user-panel">
                        
                        <div class="pull-left image">


	                   <img  style='width:80px;height:80px;' src="../../../images/<?php echo $complaint->profile_picture ?>" class="img-circle"  alt="User Image"/>

                        </div>
                          
                        <div style="padding-left:14%; font-size: 14px;">
                            <p ><u> From : </u></p>
                            <a href="/user/<?php echo $complaint->user_code ?>"> <p style="color:blue">  <?php echo $complaint->first_name ?> <?php echo $complaint->last_name ?> </p> </a> 
                            "<?php echo $complaint->email ?>"
                        </div>
                        
                    </div>

                    <blockquote style="padding:0px;">
                        <p  style="padding-left:3%;">
                        <?php echo $complaint->content ?>
                        </p>
                    </blockquote >
                 </div>
             </div>
        </div>
        @endforeach
   @else


          <div  class="box box-<?php echo $color; ?>  post_cont">
               <div class="box-header bg-<?php echo $color; ?>  with-border">
                    <blockquote style='padding:10px;'>
                        <?php echo "No Suggestions and Complaints to view in this group !"  ?>
                    </blockquote >
              </div>
          </div>

   @endif
</div>

@else

     <div  class="box box-<?php echo $color; ?>  box-solid">
         <div class="box-header">
             <i class="fa fa-envelope"></i>
             <h3 class="box-title">Add New Suggestions and Complaints</h3>
         </div>

         <div class="box-body">
            <form action="{{  URL::route('add-complaint',$groupId) }}" method="post" id="AddSuggestionsForm">

                 <div class="form-group">
                     <input type='text' class="form-control"  name='cTitle'  required="" placeholder="title"/>
                 </div>

                 <div>
                     <textarea class="textarea" placeholder="body" name='cContent'  style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"  required=""></textarea>
                  </div>

                 <br>

                 <div class="box-footer clearfix">
                <input  class="btn btn-<?php echo $color; ?>  pull-right btn  " type="submit" value="Add Suggestion">
                                                             {{ Form::token() }}

                </div>

             </form>
         </div>

     </div>


@endif
    <script>
        jQuery( document ).ready( function() {

            $('#AddSuggestionsForm').on( 'submit', function() {
                $.post(
                        $( this ).prop( 'action' ),
                        $('form#AddSuggestionsForm').serialize(),
                        function( data ) {
                          if(data.state == 'success'){
                              document.getElementById("AddSuggestionsForm").reset();
                              $.notify('success in add suggestion', "success");
                          }

                        },
                        'json'
                );
                return false;
            } );

             $('a.removeComplaint').click(function(event){
                    $.get($(this).attr('href'),function( data ) {
                                if(data.success == 'success')
                                    $('#complaintId').parent().remove(),
                              $.notify('success in delete suggestion', "success");
                                else
                                    alert('error');
                            },
                            'json');
                    return false;
                });
          } );

    </script>
