@extends('layout.mainTheme')

@section('content')
<div id="announcement_container">
</div>
{{HTML::script('js/jquery-1.11.2.min.js')}}
<script>
$(document).ready(function() {
	var text="";
    $.get('show-home-announcement',{},function(data){
		console.log(data.announcements)

		for(i=0;i<data.announcements.length;i++){
			if(data.announcements[i].check==1){
				console.log(data.announcements[i].check)
				text+="<div id='announcement'><div id='announcement_maker'>"+data.announcements[i].user_name+"</div><div id='announcement_for'>Announcement for Level"+data.announcements[i].destination+"</div><div id='announcement_title'>"+data.announcements[i].title+"</div><div id='announcement_content'>"+data.announcements[i].content+"</div></div>";
			}else if(data.announcements[i].check==0)
			{
				console.log(data.announcements[i].check)
				text+="<div id='announcement'><div id='announcement_maker'>"+data.announcements[i].user_name+"</div><div id='announcement_for'>Announcement for Group "+data.announcements[i].destination+"</div><div id='announcement_title'>"+data.announcements[i].title+"</div><div id='announcement_content'>"+data.announcements[i].content+"</div></div>";
			}

		}
		$("#announcement_container").append(text);
	});
});
</script>
@endsection
@stop
