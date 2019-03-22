<?php

class OptionsController extends BaseController{

    public function add(){
        //   die(var_dump(Input::all()));
        $options=file_get_contents('php://input');
        $options=json_decode($options,true);


        if($options)
        {
            $arrChoices = [];
            $id = 0;
            foreach(Input::all() as $key => $value ){
                foreach($value as $key2 => $value2){
                    if($key2 == "name")
                        $arrChoices[] = $value2;
                    else
                        $id = $value2;
                }
            }

            var_dump($arrChoices);

            foreach($arrChoices as $key => $value){
                $choice = array(

                    'description'=>$value,
                    'vote_id'=>$id
                );
                Vote::createChoice($choice);
            }
            // return 0;
        }

    }


    public function getAll($vId){


        $options=Vote::getAllChoicesByVoteId($vId);

//start

        $prevSelectedOption=NULL;

        $OldOption = Vote::getUserChoice($vId,Auth::user()->id);
        // var_dump($OldOption);

        //this user choose prev. one choice in this vote
        if($OldOption != NULL)
        {
            $prevSelectedOption=$OldOption[0]->choice_id;
        }

        //end

        $response = array(
            'prevSelectedOptionResult'=>$prevSelectedOption,
            'optionsResult'=>$options
        );

        // var_dump($arr);
        // json_encode($arr);
        return Response::json($response);
    }


    /* public function getAll($vId){

         $options=Vote::getAllChoicesByVoteId($vId);
         $prevSelectedOption = null;
         $OldOption = Vote::getUserChoice($vId,Auth::user()->id);
         // var_dump($OldOption);

         //this user doesn't choose any choices in this vote
         if($OldOption != NULL)
         {
             $prevSelectedOption = $OldOption[0]->choice_id;
         }
         $response = array(
             'prevSelectedOption'=>$prevSelectedOption,
             'options'=>$options
         );
         return Response::json($response);

         //end
     }
 */
    public function select($newOptionId){

        $option=Vote::getChoiceInfoBychoiceId($newOptionId);
        $rate = $option->rate + 1 ;

        $new_rate = array(
            'rate'=>$rate
        );
        Vote::updateChoiceRate($newOptionId,$new_rate);


        $OldOption = Vote::getUserChoice($option->vote_id,Auth::user()->id);
        // var_dump($OldOption);

        //this user doesn't choose any choices in this vote
        if($OldOption == NULL)
        {
            $userC = array(
                'choice_id'=>$newOptionId,
                'user_id'=>Auth::user()->id,
                'choice_date'=>Carbon\Carbon::now()->toDateTimeString()
            );
            Vote::AddUserInSpecificChoice($userC);

        }
        //this user choose prev. one choice in this vote
        else{

            $prevSelected=$OldOption[0]->choice_id;

            $newChoice = array(
                'choice_id'=>$newOptionId
            );

            Vote::updateUserChoice($OldOption[0]->choice_id,$newChoice);
            $oldOp=Vote::getChoiceInfoBychoiceId($OldOption[0]->choice_id);
            $r =$oldOp->rate-1;
            $old_rate = array(
                'rate'=>$r
            );

            Vote::updateChoiceRate($OldOption[0]->choice_id,$old_rate);


        }
        // return json_encode($dataOldUserChoice[0]->choice_id);

    }


    /*public function alreadySelectedOption($OldOptionId){



    }
    */
    public function delete($oId){

        Vote::deleteOneChoiceByChoiceId($oId);

    }

}

?>