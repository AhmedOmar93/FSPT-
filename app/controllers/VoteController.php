<?php

class VoteController extends BaseController
{



    public function add($gId){

        // return Response::json(Input::get('title'));
        /*  $vote =file_get_contents("php://input");
          $vote=json_decode($vote);

          $newVote=new Vote;
          $newVote['user_id']=Auth::user()->id;
          $newVote['group_id']=vote.group_id;
          $newVote['content']=$vote.content;
          $newVote['title']=$vote.title;
          $newVote['create_date']=Carbon\Carbon::now()->toDateTimeString();
          $newVote['end_date']=Carbon\Carbon::now()->toDateTimeString();
          if($id = Vote::createVote($newVote))
          {
              return $id;
          }
          else {
              return "fail";
          }
  */

        // return Response::json(Input::get('title'));
        $vote =file_get_contents("php://input");
        $vote=json_decode($vote,true);

        $vote['user_id']=Auth::user()->id;
        //$vote['group_id']=group.id;
        $vote['group_id']=$gId;
        $vote['create_date']=Carbon\Carbon::now()->toDateTimeString();
        if($id = Vote::createVote($vote))
        {

            return $id;
        }
        else {
            return "fail";
        }

    }



    public function delete($vId){

        Vote:: deleteVoteById($vId);

    }


    /*
        * get all votes by group id
        */
    public function getAll($gId)
    {

        //$gId = 19;
        $AllVotes = Vote::getAllVotesWithItsOwnerByGroupId($gId);
        json_encode($AllVotes);
        return $AllVotes;

    }

    public function getHomeVote(){
        $last_id = Input::get('last_vote_id');
        $votes=Vote::getVoteGroup(Auth::user()->id , $last_id);
        $x=0;
        $date2=Carbon\Carbon::now()->toDateTimeString();
        $ts1=strtotime($date2);
        $result=array();
        $at=array();
        foreach ($votes as $index) {
            $ts2=strtotime($index->create_date);
            $result[$x]=$ts1-$ts2;
            if($result[$x]<60){
                $at[$x]=0;
                $result[$x]="few second ago";
            }elseif ($result[$x]>60&&$result[$x]/60<60) {
                $at[$x]=0;
                $result[$x]=floor(($result[$x]/60))." mintes ago";
            }elseif ($result[$x]/60>60&&$result[$x]/(60*60)<24) {
                $at[$x]=0;
                $result[$x]=floor(($result[$x]/(60*60)))." hour ago";
            }elseif ($result[$x]/(60*60)>24) {
                $at[$x]=date("H:i",strtotime($index->create_date)); 
                $result[$x]=floor(($result[$x]/(60*60*24)))." days ago";
            }
            $x++;
        }
        $response=array(
            'votes' => $votes,
            'date' =>$result,
            'at' =>$at
        );
        return Response::json($response);
    }

    public function getMaxVoteId(){
        $max_vote_id = DB::table('vote')->max('id');
        $response=array(
            'max_id' => $max_vote_id,
        );
        return Response::json($response);
    }

    public function showHomeNewVote(){
        $last_id=Input::get('last_id');
        $votes=Vote::GetNewVote($last_id);
        $response=array(
            'votes' => $votes,
        );
        return Response::json($response);
    }


    public function voteTime($voteId){

        $vote= Vote::getVoteInfoByVoteId($voteId);
        $createdDate=$vote[0]->create_date;

        $currentDate=  Carbon\Carbon::now()->toDateTimeString();
        $ts1=strtotime($currentDate);
        $ts2=strtotime($createdDate);
        $timePeriod=$ts1-$ts2;

        return($timePeriod);
    }

    public function countChosenVote($voteId)
    {

        $data = Vote::countChosenVote($voteId);
        $arrData = [];
        foreach ($data as $key => $value) {
            $tempData = [];
            foreach ($value as $key => $value) {
                    $tempData[$key] = $value;          
                } 
                $arrData[]= $tempData;        
        }
        //$data = json_encode($arrData);
        return $arrData;
        
    }
}


?>