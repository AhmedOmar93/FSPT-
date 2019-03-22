<?php 


class Answer extends Eloquent 
{
        protected $table = 'question_answers';

    /*
    |
    | SECTION 1
    | Answer Related Section
    |
    */
    /*
    |
    |answer table
    |
    */
    /*  Insert single answer in the question_answer table
    Parameters :
    $answer : Takes the following info
    content , user id posting the answer, question id , rate =0 */
    public static function InsertAnswer ($answer)
    {
        if(DB::table('question_answers')->insert( $answer))
            return true;
        else
            return false;


    }
    /* Updates single answer in the answer Table
    Parameters :
    $answerId .
    $answerUpdates : Takes the info to be updated*/
    public static function UpdateAnswer ($answerId , $answerUpdates)
    {
        DB::table('question_answers')
            ->where('id', $answerId)
            ->update($answerUpdates);
    }
    /*  Deletes single answer from the question_answer Table
    Parameters :
    $answerId : Takes the answer Id*/
    public static function DeleteAnswer ($answerId)
    {
        DB::table('question_answers')->where('id', '=', $answerId)->delete();
    }
    /*  Select uploaded answers for a given column 
    Parameters :
    $answerColumn :for a question , user id and so on
    $operator : = etc
    $answerValue : value for example 2 , 20112222 */
    public static function SelectAnswerBy ($answerColumn , $operator ,$answerValue)
    {
        $answerlist =  DB::table('question_answers')->where($answerColumn, $operator , $answerValue)->get();
        return $answerlist;
    }

    public static function questionAnswers($qId){

        return Answer::SelectAnswerBy("question_id","=",$qId);
    }


    public function comments(){
        $obj=$this->hasMany('comments','answer_id','id');
   //     $obj->load('owner');
    //    $obj=$this->load('owner');
        return $obj;
    }
    
    public function rates(){
        $obj=$this->hasMany('AnswerUserRate','answer_id','id');
        return $obj;
    }

    public  function owner(){
        return $this->hasOne('User','id','user_id');
    }


    /*
    |
    |answer_user_rate table
    |
    */

    /*  Insert a rate to an answer in the answer_user_rate table
    Parameters :
    $rate : Takes 
    user id , answer id , rate date*/
    public static function InsertRateOnAnswer ($rate)
    {
        DB::table('answer_user_rate')->insert( $rate);
    }
    /* Updates single rate in the answer_user_rate Table
    Parameters :
    $answerId .
    $userId .
    $rateUpdates : Takes the info to be updated*/
    public static function UpdateRateOnAnswer ($answerId ,$userId , $rateUpdates)
    {
    DB::table('answer_user_rate')
            ->where('user_id', $userId)
            ->where('answer_id', $answerId)
            ->update($rateUpdates);
    }
    /*  Deletes  a rate on a answer in the answer_user_rate table
    Parameters :
    $answerId .
    $userId .*/
    public static function DeleteRateOnAnswer ($answerId ,$userId)
    {
        DB::table('answer_user_rate')
            ->where('user_id', '=', $userId)
            ->where('answer_id', '=', $answerId)
            ->delete();
    }
    /*  Select rates for a given column 
    Parameters :
    $rateColumn :for a certain user id , answer id , rate date
    $operator : = ,> etc
    $rateValue : value 
    $rateOrder : its ONLY VALUES are DESC / ASC */
    public static function SelectAnswerRatesBy ($rateColumn , $operator ,$rateValue , $rateOrder)
    {
        $RateList =  DB::table('answer_user_rate')
            ->where($rateColumn, $operator , $rateValue)
            ->distinct()
            ->orderBy($rateColumn, $rateOrder)
            ->get();
        return $RateList;
    }

    /*
    |
    |answer_highlighting table
    |
    */

    /*  Insert highlighted answer in the answer_highlighting table
    Parameters :
    $highlightedAnswer : Takes the following info
    user id which is the id of the group admin who highlighted it and the answer id */
    public static function InsertHighlightedAnswer ($highlightedAnswer)
    {
        DB::table('answer_highlighting')->insert( $highlightedAnswer);
    }
    /* Updates a highlighted answer in the answer_highlighting Table
    Parameters :
    $answerId .
    $userId
    $updates : Takes the info to be updated*/
    public static function UpdateHighlightedAnswer ($userId ,$answerId ,$updates)
    {
        DB::table('answer_highlighting')
            ->where('user_id', $userId)
            ->where('answer_id', $answerId)
            ->update($updates);
    }
    /*  Deletes highlighted answer from the answer_highlighting Table
    Parameters :
    $userId .
    $answerId .*/
    public static function DeleteHighlightedAnswer ($userId ,$answerId)
    {
        DB::table('answer_highlighting')
            ->where('user_id', '=', $userId)
            ->where('answer_id', '=', $answerId)
            ->delete();
    }
    /*  Select user involved in highlighting an answer 
        Select the highlighted answers of certain user  
    Parameters :
    $column :for a certain user id or answer id
    $operator : = , > etc
    $value : value for example 20110144 , 2 */
    public static function SelectHighlightedAnswer ($column , $operator ,$value)
    {
        $AnswerOrUserList =  DB::table('answer_highlighting')->where($column, $operator , $value)->get();
        return $AnswerOrUserList;
    }
    /*
    |
    | SECTION 2
    | Comment On Answer Related Section
    |
    */


    /*
    |
    |comment_answer table
    |
    */

    /*  Insert single comment in the commentAnswer table
    Parameters :
    $commentAnswer : Takes the following info
    content , date of creation , user id posting the comment , answer id , number of likes = 0 */
    public static function InsertCommentForAnswer ($commentAnswer)
    {
        DB::table('comment_answer')->insert( $commentAnswer );
    }
    /* Updates single comment in the comment_answer Table
    Parameters :
    $commentId .
    $commentUpdates : Takes the info to be updated*/
    public static function UpdateCommentForAnswer ($commentId , $commentUpdates)
    {
        DB::table('comment_answer')
            ->where('id', $commentId)
            ->update($commentUpdates);
    }
    /*  Deletes single comment from the comment_answer Table
    Parameters :
    $commentId : Takes the comment Id*/
    public static function DeleteCommentForAnswer ($commentId)
    {
        DB::table('comment_answer')->where('id', '=', $commentId)->delete();
    }
    /*  Select uploaded comments for a given column 
    Parameters :
    $commentColumn :for a certain user , answer , like_nums , creation date 
    $operator : = > etc
    $commentValue : value for example 12/2/2014 , 20112222 , 4 ,  >25 like */
    public static function SelectCommentsBy ($commentColumn , $operator ,$commentValue)
    {
        $commentlist =  DB::table('comment_answer')->where($commentColumn, $operator , $commentValue)->get();
        return $commentlist;
    }

    /*
    |
    |comment_answer_rate table
    |
    */
    /*  Insert a rate to comment in the comment_answer_rate table
    Parameters :
    $rate : Takes 
    user id , comment id ,rate date*/
    public static function InsertRateOnComment ($rate)
    {
        DB::table('comment_answer_rate')->insert( $rate);
    }
    /* Updates single rate in the comment_answer_rate Table
    Parameters :
    $commentId .
    $userId .
    $rateUpdates : Takes the info to be updated*/
    public static function UpdateRateOnComment ($commentId ,$userId , $rateUpdates)
    {
    DB::table('comment_answer_rate')
            ->where('user_id', $userId)
            ->where('comment_id', $commentId)
            ->update($rateUpdates);
    }
    /*  Deletes  a rate on a comment in the comment_answer_rate table
    Parameters :
    $commentId .
    $userId .*/
    public static function DeleteRateOnComment ($commentId ,$userId)
    {
        DB::table('comment_answer_rate')
            ->where('user_id', '=', $userId)
            ->where('comment_id', '=', $commentId)
            ->delete();
    }
    /*  Select rates for a given column 
    Parameters :
    $rateColumn :for a certain user id , comment id , rate date
    $operator : = ,> etc
    $rateValue : value 
    $rateOrder : its ONLY VALUES are DESC / ASC */
    public static function SelectCommentRatesBy ($rateColumn , $operator ,$rateValue , $rateOrder)
    {
        $RateList =  DB::table('comment_answer_rate')
            ->where($rateColumn, $operator , $rateValue)
            ->distinct()
            ->orderBy($rateColumn, $rateOrder)
            ->get();
        return $RateList;
    } 
    
    
}