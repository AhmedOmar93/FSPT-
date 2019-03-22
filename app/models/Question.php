<?php


class Question extends Eloquent
{
    protected $table="question";

    public function getDates()
    {
        return array("created_at");
    }
    
    /*  Insert single question in the question table
    Parameters :
    $question : Takes the following info
    content , title , tag , date of creation , user id posting the question, group id where the question is posted */
    public static function InsertQuestion ($question)
    {
       if(DB::table('question')->insert( $question)) 
        return true;
        else
        return false;
    }
    /* Updates single question in the question Table
    Parameters :
    $questionId .
    $questionUpdates : Takes the info to be updated*/
    public static function UpdateQuestion ($questionId , $questionUpdates)
    {
        DB::table('question')
            ->where('id', $questionId)
            ->update($questionUpdates);
    }
    /*  Deletes single question from the question Table
    Parameters :
    $questionId : Takes the question Id*/
    public static function DeleteQuestion ($questionId)
    {
        DB::table('question')->where('id', '=', $questionId)->delete();
    }
    /*  Select uploaded questions for a given column 
    Parameters :
    $questionColumn :for tag , userid , groupid
    $operator : = etc
    $questionValue : value for example c# , 20112222 , 4*/
    public static function SelectQuestionsBy ($questionColumn , $operator ,$questionValue)
    {
        $questionlist =  DB::table('question')->where($questionColumn, $operator , $questionValue)->get();
        return $questionlist;
    }

    public static function groupQuestion($groupId){

       return  Question::SelectQuestionsBy("group_id","=",$groupId);
    }


    public  function owner(){
        return $this->hasOne('User','id','user_id');
    }

    public function groups(){
        return $this->belongsTo('Group','group_id','id');
    }
    
    public static function GetNewQuestion($last_id)
    {
        $result=DB::table('question')
            ->join('users','question.user_id','=','users.id')
            ->join('groups','question.group_id','=','groups.id')
            ->where('question.id','>',$last_id)
            ->select('question.id','question.title','question.content','question.search_tag','users.user_name','groups.name as group_name')
            ->orderBy('question.id','decs')
            ->get();
        return $result;
    }

    public static function GetHomeQuestion($user_id , $last_id){
        $results = DB::select('SELECT q.id,q.title,q.content,q.search_tag,u.user_name,u.profile_picture,u.user_code,g.name,q.created_at FROM question q left outer join users u on q.user_id = u.id left outer join groups g on q.group_id = g.id WHERE group_id in (select distinct group_id from group_members where user_id = :id and q.id <= :last_id) order by q.id desc LIMIT 5', ['id' => $user_id , 'last_id' => $last_id]);
        return $results;
    }
}