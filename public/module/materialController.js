var request=new XMLHttpRequest();
var data=new FormData();
window.reset = function (e) {
    e.wrap('<form>').closest('form').get(0).reset();
    e.unwrap();
}


myApp.controller('materialController',function($scope,$http){
	active_tape(6);
	  $scope.className=className;
	  $scope.color=color;
	  $scope.colorName=colorName;
	

	$scope.path_prefix=path_prefix;

	$scope.download=function(event){
		
		event.preventDefault();
		console.log(this.material.file_name);
		window.location.href=path_prefix+"files/"+this.material.file_name;
	}

	$scope.max_pic=function(event){
		console.log(event.toElement.src);
		console.log(event);
		var width=event.target.width;

		if(width>$("#picture_maximum").width()){
			width=("#picture_maximum").width();
		}

		

		var height=event.target.height;
		if(height>$("#picture_maximum").height()){
			height=("#picture_maximum").height();
		}
		

		var src=event.toElement.src;
		
		//$("#picture_maximum").show();
		//$("#picture_maximum").html("<img src="+src+" style='width:"+width+";height:"+height+"'/>");


	}

	if(!load_material){
	$http.get(path_prefix+"api/material/getAll/"+group.id).success(function(data){
		
		for(var i=0;i<data.length;i++){
			data[i].description=decodeURIComponent(data[i].description);
			data[i].is_image=is_image(data[i].file_name);
		}
		all_material=data;
		$scope.materials=data;
		load_material=true;
	});
	}else{
		$scope.materials=all_material;
		console.log(all_material);
		
	}



	$scope.upload_file=function(event){
		event.preventDefault();
		event.stopPropagation();
		//alert("kok");
		var fileInpt=document.getElementById('file');
		if(fileInpt.files.length!=0){

			var data=new FormData();

			data.append('ajax',true);
			data.append('gId',group.id);
			data.append('description',encodeURIComponent($("#materialDesc").val()));

		 	console.log($("#materialDesc").val());

			for(var i=0;i<fileInpt.files.length;++i){
				data.append('file[]',fileInpt.files[i]);
			}

			var request=new XMLHttpRequest();
			var progress=document.getElementById('upload_progress');
			var progress_bar=document.getElementById('loading_bar');
			var bar_content=document.getElementById('bar_content');
			var links=document.getElementById('uploaded');

			request.upload.addEventListener('progress',function(event){



		if(event.lengthComputable){

		bar_content.style.display='block';
		

		var percent=event.loaded/event.total;

			progress.innerHTML="";
			progress.innerHTML=Math.round(percent*100)+' % ';
		    progress_bar.style.width=Math.round(percent*100)*1+"%";

			}
		});

		request.upload.addEventListener('load',function(event){
		//	document.getElementById('upload_progress').style.display='none';
		//progress.innerHTML="Uploaded Successfully";
		//progress_bar.style.display='none';
		bar_content.style.display='none';
		//progress_bar.style.backgroundColor='green';
		//bar_content.style.borderColor='green';
		reset($('#file'));
		$("#materialDesc").val("");
		});

	request.upload.addEventListener('error',function(event){
				progress.innerHTML="Upload Fail";
				console.log(event);
			});

	request.addEventListener('readystatechange',function(event){
		if(this.readyState==4){
			if(this.status==200){

				var uploadedFiles=eval(this.response);
				console.log(uploadedFiles);
				for(var i=0;i<uploadedFiles.length;i++){
					uploadedFiles[i].description=decodeURIComponent(uploadedFiles[i].description);	
											
					if(is_image(uploadedFiles[i].file_name)){
					
						$("#materials").prepend("<div  class='box panel box-"+className+" custom-box' ><div class='box-body'><p class='qContent desc'>"+uploadedFiles[i].description+"</p><img onclick='view(this)'  class='uploaded_image' src='"+path_prefix+"files/"+uploadedFiles[i].file_name+"'></div></div>");
						uploadedFiles[i].is_image=true;
					}else{
						$("#materials").prepend("<div  class='box panel box-"+className+" custom-box' ><div class='box-body'><p class='qContent desc'>"+uploadedFiles[i].description+"</p><table class='table'><tr><td style='width:20px;background:#efefef;'><img src='"+path_prefix+"img/file.png'></td><td><a  href='"+path_prefix+"files/"+uploadedFiles[i].file_name+"'>"+uploadedFiles[i].real_name+"</a></td></tr><tr><td colspan='2'>Just Now</td></tr></table></div></div>");
					}
					
					all_material.unshift(uploadedFiles[i]);	
					//$scope.materials.unshift(uploadedFiles[i]);
					console.log(uploadedFiles[i]);	
				}


				progress.innerHTML="Uploaded Successfully";
			}else{
				//alert("failed");
				progress.innerHTML="Upload Fail";
			}
		}
		
	});


request.open('POST',path_prefix+'api/metrial/add');
request.setRequestHeader('Cache-Control','no-cache');
document.getElementById('upload_progress').style.display='block';

console.log(data.file);

request.send(data);


		}else{
			alert("please Choose File");
		}

	}
});

function is_image(fileName){

var names=fileName.split('.');
if(names[1]=='jpg' || names[1]=='png'){
return true;
}

return false;

}