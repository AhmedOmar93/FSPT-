<?php 

class MaterialController extends BaseController{

	public function Add(){
			
			$destinationPath = 'public/upload/metrials';
			foreach ($_FILES['file']['name'] as $key => $name) {

				$filename=str_random(10).".".pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
		        
				$material=new Material();
				$material->description=$_POST['description'];
				$material->gId=$_POST['gId'];
				$material->file_name=$filename;
				$material->real_name=$name;
	
				if($_FILES['file']['error'][$key]==0 && move_uploaded_file($_FILES['file']['tmp_name'][$key],'public/files/'.$filename)){
					if($material->save())
						$uploaded[]=$material;
				}else{
				//	$uploaded[]="{$name} this file n't uploaded because : ".$_FILES['file']['error'][$key];
				$uploaded[]=$filename." failed";
				}
			}
			return json_encode($uploaded);
	}

	public function getAll($gId){

		$materials=Material::where('gId','=',$gId)->orderBy('id','desc')->get();
		foreach ($materials as  $material) {
			$material->time=$material->created_at->diffForHumans();
		
		}
		return $materials;
	}
}