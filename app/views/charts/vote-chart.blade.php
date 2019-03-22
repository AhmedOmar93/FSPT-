  
    <link rel="stylesheet" type="text/css" href="{{URL::asset('css/global.css')}}">


    {{ HTML::style('dist/css/AdminLTE.min.css') }}
    {{ HTML::style('dist/css/skins/skin-blue.min.css') }}
    {{ HTML::style('dist/css/skins/_all-skins.min.css') }}
    <link rel="stylesheet" type="text/css" href="{{URL::asset('dist/css/font-awesome-4.3.0/css/font-awesome.min.css')}}">


    {{HTML::script("plugins/jQuery/jQuery-2.1.3.min.js")}}
    
    {{HTML::script("js/jquery_lib.js")}}
    {{HTML::script("js/notifyjs.js")}}
  
    {{HTML::script("plugins/chartjs/Chart.min.js")}}

<style type="text/css">
.vote-option{
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 0px;
    right: 30px;
      }

</style>

                  <div class="chart-responsive">
                    <canvas id="pieChart" height="250"></canvas>
                  </div>

                  <div id="chartjs-tooltip"></div>
                  <ul class="vote-option" id="vote-option-list">
                    
                  </ul>
           
<script type="text/javascript">

var colorArray = ['','#f56954','#00a65a','#f39c12','#00c0ef','#3c8dbc','#d2d6de'];
var highLight = ['','#f56954','#00a65a','#f39c12','#00c0ef','#3c8dbc','#d2d6de'];
$.ajax({
      method: "GET",
      url: "http://localhost:8000/api/get/vote/choice/count/"+{{$_GET['voteId']}},
      success: function(result){

      	var data  = [];
      	var lastIndix = 0;
		for (var i = 0; i < result.length; i++) {
			var index = Math.floor((Math.random() * 6) + 1);
			if (lastIndix != 0 && lastIndix == index) {
				if(index == 6)
					index = 1;
				else
					index ++;
			}
        	data.push({value:result[i].vote_count,label:result[i].description,
        		color:colorArray[index],highlight:highLight[index]});
          var list = "<li><i style='color:"+colorArray[index]+";padding-right:5px;' class='fa fa-circle-o ''></i>"+result[i].description+"</li>";
          $("#vote-option-list").append(list);

        	lastIndix = index;
        }
        
        

        //-------------
        //- PIE CHART -
        //-------------
/*
        Chart.defaults.global.customTooltips = function(tooltip) {
        	// Tooltip Element
        
        // Hide if no tooltip
        if (!tooltip) {
            tooltipEl.css({
                opacity: 0
            });
            return;
        }
   
        // Set Text
        tooltipEl.html(tooltip.text+" <i class='fa fa-user-plus'></i>");
       
        }; */
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = data;


        var pieOptions = {
          //Boolean - Whether we should show a stroke on each segment
          segmentShowStroke: true,
          //String - The colour of each segment stroke
          segmentStrokeColor: "#fff",
          //Number - The width of each segment stroke
          segmentStrokeWidth: 2,
          //Number - The percentage of the chart that we cut out of the middle
          percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          animationSteps: 100,
          //String - Animation easing effect
          animationEasing: "easeOutBounce",
          //Boolean - Whether we animate the rotation of the Doughnut
          animateRotate: true,
          //Boolean - Whether we animate scaling the Doughnut from the centre
          animateScale: false,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true,
          // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: false,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",

          tooltipTemplate: function(valuesObject){
				  return valuesObject.label + " : +" + valuesObject.value + " Users";
				}
       
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);

        }
    });


</script>