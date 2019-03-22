
<!-- Main Footer -->
      <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
          Anything you want
        </div>
        <!-- Default to the left --> 
        <strong>Copyright &copy; 2015 <a href="#">Company</a>.</strong> All rights reserved.
      </footer>

{{HTML::script("js/jQuery.print.js")}}
    </div><!-- ./wrapper -->

    {{HTML::script("bootstrap/js/bootstrap.min.js")}}
   
    {{HTML::script("dist/js/app.js")}}

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- Bootstrap 3.3.2 JS -->
    <!-- Morris.js charts -->
    <!-- Sparkline -->
    <!-- jvectormap -->
    <!-- jQuery Knob Chart -->
    
    <!-- daterangepicker -->
    <!-- datepicker -->
    <!-- Bootstrap WYSIHTML5 -->
    <!--
    {{HTML::script("plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js")}}
   
    {{HTML::script("plugins/iCheck/icheck.min.js")}}
    
    
    {{HTML::script("dist/js/app.min.js")}}
    
    
    {{HTML::script("dist/js/pages/dashboard.js")}}

    -->


 <div  id="blackground" style='overflow-y:s1croll;'>
<center>
<div  id='showimage'></div>
</center>

 </div>

 <div id="backk" onclick="hideimage()">x</div>
<div id='GoToUpButton' class='btn btn-{{ProfileController::get_class()}}'>Top</div>

<div class='offwhite_background'></div>

<script type="text/javascript">

function view(x){

var width,height;
var content=document.getElementById('showimage');
$("#showimage").css("marginTop",0);
var image=document.createElement("img");
image.src=x.src;

var page_height=window.innerHeight;

var page_width=window.innerWidth;

if(image.height>550){
    image.height=550;
}

if(image.width>1000){
    image.width=1000;
}

content.innerHTML="<img src='"+image.src+"'>";
image.width=$("#showimage").width();
image.height=$("#showimage").height();

var hhjj=(page_height-image.height)/2-20;
//console.log(image.height);
$("#showimage").css("marginTop",hhjj);
//content.style.marginTop=+"px";

$('#backk').css('top','100%');

var close_button=document.getElementById('backk');

var close_button_top=((page_height-image.height)/2)-30;
var close_button_right=((page_width-image.width)/2)-25;
$('#backk').css('marginTop',close_button_top);
$('#backk').css('right',close_button_right);

$('#blackground').css('top','100%');
            
$('#blackground').animate({top:'0.5',opacity:'1'},'slow');
$('#backk').animate({top:'0.5',opacity:'1'},'slow');

}

function hideimage(){
$('#blackground').animate({top:'-100%',opacity:'0'},'slow');
$('#backk').animate({top:'-100%',opacity:'0'},'slow');
}

$('#GoToUpButton').click(function(){
    $('html, body').animate({scrollTop : 0},800);
   //     return false;
});


$(document).ready(function(){

var page_height=window.innerHeight;
var page_width=window.innerWidth;


  $(window).scroll(function() {
   if ($(window).scrollTop() > 100) {
            $('#GoToUpButton').fadeIn('slow');
            
        } else {
            $('#GoToUpButton').fadeOut('slow');
        }
    });

  $('.showBox').click(function(){
    var page_height=window.innerHeight;
    var page_width=window.innerWidth;


     var BoxId="#"+$(this).attr('box-id');
     var box_width=$(BoxId).width();
     var box_height=$(BoxId).height();
    $(".offwhite_background").show();
    $(BoxId).show();

     $(BoxId).css({
        "left":(page_width-box_width)/2,
        "top":(page_height-400)/2,
        "opacity":"1"});

  });

  $(".offwhite_background").click(function(){
    $(".center_box").hide();
    $(this).hide();
  });

   var neg = $('.main-header').outerHeight() + $('.main-footer').outerHeight();
    var window_height = $(window).height();
    var sidebar_height = $(".sidebar").height();

    $(".content-wrapper, .right-side").css('min-height', window_height - $('.main-footer').outerHeight());
    
    
});

</script>
  </body>
</html>