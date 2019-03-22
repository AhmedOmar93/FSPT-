<?php

Route::get('/testing',function(){
	return EventController::getAllEvents();
//	echo "ahmed";
});

Route::get('/myTesting',function(){
	return View::make('calendar/testing');
});

Route::get('/',array(
	'before'=>'auth',
	'as'=>'home',
	'uses'=>'HomeController@home'
));

Route::get('/home',array(
	'before'=>'auth',
	'as'=>'home',
	'uses'=>'HomeController@test'
));

Route::get('/user/{userCode}',array(
	'before'=>'auth',
	'as'=>'profile-user',
	'uses'=>'ProfileController@user'
));



// test quiz

// test chat

Route::get('/test/{id}',array(
	'before'=>'auth',
	'as'=>'test',
	'uses'=>'FriendsListController@activeFriends'
));

Route::get('/test',array(
	'before'=>'auth',
	'as'=>'test',
	'uses'=>'FriendsListController@getFriendsRequest'
));



Route::group(array('before'=>'auth'),function(){

	/*
     * CSRF protection group
     */
	Route::group(array('before'=>'csrf'),function(){
		/*
         * Change password (POST)
         */
		Route::post('/account/change-password',array(
			'as'=>'account-change-password-post',
			'uses'=>'AccountController@postChangePassword'
		));

		/*
         * Change password (POST)
         */
		Route::post('/account/change-email',array(
			'as'=>'account-change-email-post',
			'uses'=>'AccountController@changeEmail'
		));

		// create group (POST)
		Route::post('/group/create',array(
			'as'=>'group-create-post',
			'uses'=>'GroupsController@postCreate'
		));

		// create group (POST)
		Route::post('/group/technical/create',array(
			'as'=>'technical-group-create-post',
			'uses'=>'GroupsController@postCreateTechnichal'
		));

		// add question in quiz (POST)
		Route::post('/quiz/create/question/{gId}',array(
			'as'=>'quiz-create-question-post',
			'uses'=>'QuizController@postCreateQuestion'
		));

		// add quiz  (POST)
		Route::post('/quiz/create/{gId}',array(
			'as'=>'quiz-create-quiz-post',
			'uses'=>'QuizController@postCreateQuiz'
		));

		// check answer  (POST)
		Route::post('/quiz/show/{group_id}/{quiz_id}/{model}',array(
			'as'=>'check-answer-post',
			'uses'=>'QuizController@postQuizAnswer'
		));
		// add survey  (POST)
		Route::post('/survey/create',array(
			'as'=>'create-survey-post',
			'uses'=>'SurveyController@postCreateSurvey'
		));

		// add question in survey (POST)
		Route::post('/survey/create/question',array(
			'as'=>'survey-create-question-post',
			'uses'=>'SurveyController@postCreateQuestion'
		));

		// show servey (POST)
		Route::post('/survey/show/{surveyId}',array(
			'as'=>'check-answer-post',
			'uses'=>'SurveyController@postQuizAnswer'
		));

		// add member in group(POST)
		Route::post('/group/addMemebr/{group_id}',array(
			'as'=>'groups-add-member',
			'uses'=>'GroupsController@addMember'
		));

		// remove member in group(POST)
		Route::post('/group/removeMemebr/{group_id}',array(
			'as'=>'groups-remove-member',
			'uses'=>'GroupsController@removeMember'
		));


		// send request to group(POST)
		Route::post('/group/request/{group_id}',array(
			'as'=>'groups-request',
			'uses'=>'GroupsController@request'
		));


});


/*
 * sign out (GEt)
 */

	Route::get('/account/sign-out',array(
		'as'=>'account-sign-out',
		'uses'=>'AccountController@getSignOut'
	));
	/*
     * Change password (GET)
     */
	Route::get('/account/change-password',array(
		'as'=>'account-change-password',
		'uses'=>'AccountController@getChangePassword'
	));


	// create group (GET)
	Route::get('/group/create',array(
		'as'=>'group-create',
		'uses'=>'GroupsController@getCreate'
	));


	// create group (GET)
	Route::get('/group/technical/create',array(
		'as'=>'technical-group-create',
		'uses'=>'GroupsController@getCreateTechnical'
	));

	// show groups  (GET)
	Route::get('/group/show/{name}',array(
		'as'=>'groups-show',
		'uses'=>'GroupsController@showGroup'
	));

	// show groups  (GET)
	Route::get('/employee/group/show/{name}',array(
		'as'=>'Technical-groups-show',
		'uses'=>'GroupsController@showTechnicalGroup'
	));


	// accept request to group(GET)
	Route::get('/group/request/{group_id}/{user_id}',array(
		'as'=>'groups-request',
		'uses'=>'GroupsController@acceptRequest'
	));



	// add question in quiz (GET)
	Route::get('/quiz/create/question/{gId}',array(
		'as'=>'quiz-create-question',
		'uses'=>'QuizController@getCreateQuestion'
	));

	// add quiz (GET)
	Route::get('/quiz/create/{gId}',array(
		'as'=>'quiz-create-quiz',
		'uses'=>'QuizController@getCreateQuiz'
	));
	// check answer (GET)
	Route::get('/quiz/show/{group_id}/{quiz_id}/{model}',array(
		'as'=>'check-answer',
		'uses'=>'QuizController@getSubjectQuestions'
	));
// get all user Answer
	Route::get('/quiz/show/user/answers/{quiz_id}/{model}',array(
		'as'=>'check-answer',
		'uses'=>'QuizController@getAllUserAnswer'
	));


    // get all quizzes
	Route::get('api/quize/getAll/{gId}',['uses'=>'QuizController@getAllQuizzes']);

	Route::get('api/quize/one',function(){
		return View::make('quiz.one');
	});

	// get all surveys
	Route::get('survey/getAll',['uses'=>'SurveyController@getAllSurveys']);

	Route::get('api/survey/one',function(){
		return View::make('survey.one');
	});

	// delete quiz question  (get)
	Route::get('/delete/quiz/question/{question_id}',array(
		'as'=>'delete-quiz-question',
		'uses'=>'QuizController@DeleteQuizQuestion'
	));

	// delete quiz(get)
	Route::get('/delete/quiz/{quiz_id}',array(
		'as'=>'delete-quiz',
		'uses'=>'QuizController@DeleteQuiz'
	));

	// change color quiz(get)
	Route::get('/change/user/color/{color}',array(
		'as'=>'change-color',
		'uses'=>'AccountController@ChangeColor'
	));

	// add survey (GET)
	Route::get('/survey/create',array(
		'as'=>'create-survey',
		'uses'=>'SurveyController@getCreateSurvey'
	));
	// add question in survey (GET)
	Route::get('/survey/create/question',array(
		'as'=>'survey-create-question',
		'uses'=>'SurveyController@getCreateQuestion'
	));
	// show servey (GET)
	Route::get('/survey/show/{surveyId}',array(
		'as'=>'check-answer',
		'uses'=>'SurveyController@getAllQuestionsChoices'
	));

	// show servey (GET)
	Route::get('/survey/showData/{surveyId}',array(
		'as'=>'survey-data',
		'uses'=>'SurveyController@getAllQuestionsChoicesWithRate'
	));


	// delete survey(get)
	Route::get('/delete/survey/{survey_id}',array(
		'as'=>'delete-survey',
		'uses'=>'SurveyController@DeleteSurvey'
	));

	// delete survey Question(get)
	Route::get('/delete/survey/question/{survey_question_id}',array(
		'as'=>'delete-survey-question',
		'uses'=>'SurveyController@DeleteSurveyQuestion'
	));


});

/*
 * unauthenticated group
 */
Route::group(array('before'=>'guest'),function(){

	/*
     * CSRF protection group
     */
	Route::group(array('before'=>'csrf'),function(){

		/*
	 * create account (POST)
	 */
		Route::post('/account/create',array(
			'as'=>'account-create-post',
			'uses'=>'AccountController@postCreate'
		));

		/*
         * sign in account (POST)
         */
		Route::post('/account/sign-in',array(
			'as'=>'account-sign-in-post',
			'uses'=>'AccountController@postSignIn'
		));

		/*
         * Forgot Password(POST)
         */
		Route::post('/account/forgot-password',array(
			'as'=>'account-forgot-password-post',
			'uses'=>'AccountController@postForgotPassword'
		));

	});

	/*
	 * create account (GET)
	 */
	Route::get('/account/create',array(
		'as'=>'account-create',
		'uses'=>'AccountController@getCreate'
	));
/*
 * redirect
 */
	Route::get('/account/direct',array(
		'as'=>'account-create',
		'uses'=>'AccountController@getCreate'
	));

	/*
	 * Activation
	 */
	Route::get('/account/activate/{code}',array(
		'as'=>'account-activate',
		'uses'=>'AccountController@getActivate'
	));

	/*
     * sign in account (GET)
     */
	Route::get('/account/sign-in',array(
		'as'=>'account-sign-in',
		'uses'=>'AccountController@getSignIn'
	));

	/*
     * Forgot Password(GET)
     */
	Route::get('/account/forgot-password',array(
		'as'=>'account-forgot-password',
		'uses'=>'AccountController@getForgotPassword'
	));

	Route::get('/account/recover/{code}',array(
		'as'=>'account-recover',
		'uses'=>'AccountController@getRecover'
	));
});


/* Technical Support Routes*/

Route::post('/api/add/question', ['uses' => 'QuestionController@add']);
Route::get('/api/question/get_all/{gId}',['uses'=>'QuestionController@getAll']);
Route::get('/api/answer/get_all/{qId}',['uses'=>'AnswersController@getAll']);
Route::post('/api/answer/add',['uses'=>'AnswersController@add']);
Route::post('/api/comment/add',['uses'=>'CommentController@addComment']);
Route::get('/api/question/delete/{qId}',['uses'=>'QuestionController@delete']);
Route::get('/api/answer/delete/{qId}',['uses'=>'AnswersController@delete']);
Route::get('/api/comment/delete/{qId}',['uses'=>'CommentController@delete']);
Route::post('api/answer/rate',['uses'=>'AnswersController@rate']);
Route::get('/api/question/get/{qId}',['uses'=>'QuestionController@get_question']);

Route::get('/api/question/get_prev/{gId}/{qId}',['uses'=>'QuestionController@get_prev_questions']);
Route::get('/api/answer/markRight/{aId}',['uses'=>'AnswersController@mark_right']);


Route::get('technicalSupport',array('before'=>'auth',function(){

	return View::make('technical_support');
}));

Route::get('/questions',function(){
	return QuestionController::getAll(22);
});


/* Material Routes */

Route::post('api/metrial/add',['uses'=>'MaterialController@Add']);
Route::get('api/material/getAll/{gId}',['uses'=>'MaterialController@getAll']);

/*  Project Managment Routes */

Route::post('createProject/',['uses'=>'ProjectController@Add']);

Route::get('project/',['before'=>'auth','uses'=>'ProjectController@view']);

Route::get('projectMembers/{pId}',['uses'=>'ProjectController@getMembersWithTasks']);

Route::get('project/addMember/{userCode}/{gId}',['uses'=>'ProjectController@addNewMember']);

Route::get('task/markTaskDone/{taskId}',['uses'=>'ProjectController@markTaskDone']);
Route::get('task/markTaskUnDone/{taskId}',['uses'=>'ProjectController@markTaskUnDone']);
Route::get('task/delete/{taskId}',['uses'=>'ProjectController@deleteTask']);

Route::post('task/add',['uses'=>'ProjectController@addNewTask']);



/* Calandar Routes */

Route::group(array('before'=>'auth'),function(){

Route::get('/calendar/edit',function(){
	return  View::make('calendar/edit');
});

Route::get('/calendar/view',function(){
	return  View::make('calendar/view');
});

});

Route::post('/calendar/event/add',['uses'=>'EventController@Add']);
Route::post('/calendar/event/updateDate',['uses'=>'EventController@UpdateEvent']);
Route::post('/calendar/event/delete',['uses'=>'EventController@deleteEvent']);
Route::get('/calendar/event/getAll',['uses'=>'EventController@getAllEvents']);
Route::get('/calendar/event/getGroupEvents',['uses'=>'EventController@periodGroupEvent']);
Route::get('/calendar/event/getUserEvents',['uses'=>'EventController@periodUserEvent']);
Route::get('/calendar/event/getAllGroupEvents',['uses'=>'EventController@getAllGroupEvents']);

/*************** End Calendar Route ********************/


/*************** Start Vote Route ********************/

Route::post('/api/add/vote/{gId}', ['uses' => 'VoteController@add']);
Route::get('/api/vote/get_all/{gId}',['uses'=>'VoteController@getAll']);
Route::get('/api/delete/vote/{vId}',['uses'=>'VoteController@delete']);

Route::post('/api/option/add',['uses'=>'OptionsController@add']);
Route::get('/api/options/get_all/{oId}',['uses'=>'OptionsController@getAll']);
Route::get('/api/delete/option/{oId}',['uses'=>'OptionsController@delete']);
Route::get('/api/select/option/{oId}',['uses'=>'OptionsController@select']);

//Route::get('votes',function(){
  //  return View::make('vote');
//});

/*************** End Vote Route ********************/


/*************** Start Complaints and Suggestions Route ********************/
Route::get('/SuggestionAndComplaints/{gId}',array(
    'as'=>'add-complaint',
    'uses'=>'ComplaintController@getAddComplaint'
));

Route::post('/SuggestionAndComplaints/{gId}',array(
    'as'=>'add-complaint',
    'uses'=>'ComplaintController@postAddComplaint'
));

Route::get('/delete/complaint/{complaint_id}',array(
    'as'=>'delete-complaint',
    'uses'=>'ComplaintController@DeleteComplaint'
));

/*************** END Complaints and Suggestions Route ********************/



/***** Chat *******/
/*******  php artisan brainsocket:start --port=8081 **********/

Route::get('/api/get/roomMessages/{room_id}', ['uses' => 'ChatController@getChatRoomMessages']);
Route::post('/api/post/setMessage', ['uses' => 'ChatController@setNewMessage']);


//Announcements Routes

/*
	* Add Announcement (POST)
	*/
	Route::post('/Announcement/add-announcement',array(
			'uses'=>'AnnouncementController@postAddAnnouncement'
		));
	/*
	*  Announcement (GET)
	*/
	Route::get('/Announcement/announcement',array(
		'as'=>'Announcement-announcement',
		'uses'=>'AnnouncementController@Announcement'
	));
	
	/*
	*  show Announcement (GET)
	*/
	Route::get('/Announcement/show-announcement',array(
		'uses'=>'AnnouncementController@showAnnouncement'
	));
	
	/*
	* delete Announcement (GET)
	*/
	Route::get('/Announcement/delete-announcement/{id}',array(
		'as'=>'Announcement-delete-announcement',
		'uses'=>'AnnouncementController@deleteAnnouncement'
	));
	/*
	* edite Announcement (GET)
	*/
	Route::get('/Announcement/edit-announcement/{id}',array(
		'as'=>'Announcement-edit-announcement',
		'uses'=>'AnnouncementController@editAnnouncement'
	));
	/*
	* edite Announcement (POST)
	*/
	Route::post('/Announcement/edite-announcement/{id}',array(
			'as'=>'Announcement-edite-announcement-post',
			'uses'=>'AnnouncementController@postEditeAnnouncement'
		));
		
	/*
	* Show groupe Announcement (GET)
	*/
	Route::get('/Announcement/show-group-announcement',array(
		'as'=>'Announcement-show-group-announcement',
		'uses'=>'AnnouncementController@showGroupAnnouncement'
	));
	
	/*
	* Show level Announcement (GET)
	*/
	Route::get('/Announcement/show-level-announcement',array(
		'as'=>'Announcement-show-level-announcement',
		'uses'=>'AnnouncementController@showLevelAnnouncement'
	));

	/*
	*	news routes
	*/
///////////////////////////////////////////////////////////////////////////
	Route::get('/chichat',array(
		'as'=>'chichat',
		'uses'=>'AnnouncementController@chichatNews'
	));
		
	Route::get('/chichat/GetAll',array(
		'uses'=>'AnnouncementController@chichatNewsGetAll'
	));

	Route::post('announcements/like-announcement',array(
		'uses'=>'AnnouncementController@postLikeAnnouncement'
	));


	/*
	*	home routes
	*/
	///////////////////////////////////////////////////////////////////////
	
	Route::get('/home',array(
		'as'=>'Announcement-home-announcement',
		'uses'=>'AnnouncementController@homeAnnouncement'
	));
	
	/*
	*  show Announcement (GET)
	*/
	Route::get('/Announcement/show-home-announcement',array(
		'uses'=>'AnnouncementController@showHomeAnnouncement'
	));

	Route::get('/Announcement/show-home-new-announcement',array(
		'uses'=>'AnnouncementController@showHomeNewAnnouncement'
	));
	
	Route::get('/Questions/show-home-question',array(
		'uses' => 'QuestionController@getHomeQuestions'
		));

	Route::get('/Questions/show-home-new-question',array(
		'uses'=>'QuestionController@showHomeNewQuestion'
	));

	Route::get('/Votes/show-home-vote',array(
		'uses' => 'VoteController@getHomeVote'
		));
	Route::get('/Votes/show-home-new-vote',array(
		'uses'=>'VoteController@showHomeNewVote'
	));

	Route::get('/Quizzes/show-home-quiz',array(
		'uses' => 'QuizController@getHomeQuiz'
		));
	Route::get('/Quizzes/show-home-new-quiz',array(
		'uses'=>'QuizController@showHomeNewQuiz'
	));

	Route::get('/Assignments/show-home-Assignment',array(
		'uses' => 'AssignmentController@getHomeAssignment'
		));
	Route::get('/Assignments/show-home-new-assignment',array(
		'uses'=>'AssignmentController@showHomeNewAssignment'
	));

	Route::get('announcements/max-announcement-id',array(
		'uses'=>'AnnouncementController@getMaxAnnouncementId'
	));
	Route::get('user/announcements/max-announcement-id',array(
		'uses'=>'AnnouncementController@getMaxAnnouncementId'
	));

	Route::get('questions/max-question-id',array(
		'uses'=>'QuestionController@getMaxQuestionId'
	));

	Route::get('votes/max-vote-id',array(
		'uses'=>'VoteController@getMaxVoteId'
	));

	Route::get('Quizzes/max-quiz-id',array(
		'uses'=>'QuizController@getMaxQuizId'
	));

	Route::post('user/announcements/like-announcement',array(
		'uses'=>'AnnouncementController@postLikeAnnouncement'
	));

	Route::get('chichat/GetAllUser',array(
		'uses'=>'AnnouncementController@chichatNewsGetAllUser'
	));

/********* Friens List **************/

Route::get('/api/get/getAllFriends', ['uses' => 'FriendsListController@getAllFriends']);

Route::get('/addFriends/{user2}',array(
	'uses'=>'FriendsListController@addFriends'
));

Route::get('/cancelRequest/{room_id}',array(
	'uses'=>'FriendsListController@cancelRequest'
));

Route::get('/confirmRequest/{room_id}',array(
	'uses'=>'FriendsListController@activeFriends'
));

Route::post('/api/activeFriend/{id}',array(
	'uses'=>'FriendsListController@activeFriends'
));




/*
	*	group
	*/
	////////////////////////////////////////////////////////////////////////////////////////
	Route::get('group/show/announcements/show-announcement-group',array(
		'uses'=>'AnnouncementController@getAnnouncementGroup'
	));

	Route::get('group/show/announcements/max-announcement-id',array(
		'uses'=>'AnnouncementController@getMaxAnnouncementId'
	));
	
	Route::post('group/show/announcements/add-announcement-group',array(
		'uses'=>'AnnouncementController@postAddAnnouncement'
	));

	/////////////////////////////////////////////////////////////////////////////////////

	/*
	*	techincal group
	*/
	///////////////////////////////////////////////////////////////////////////////////////
	Route::get('employee/group/show/announcements/show-announcement-group',array(
		'uses'=>'AnnouncementController@getAnnouncementGroup'
	));

	Route::get('employee/group/show/announcements/max-announcement-id',array(
		'uses'=>'AnnouncementController@getMaxAnnouncementId'
	));
	
	Route::post('employee/group/show/announcements/add-announcement-group',array(
		'uses'=>'AnnouncementController@postAddAnnouncement'
	));

	Route::post('employee/group/show/announcements/add-announcement-comment',array(
		'uses'=>'AnnouncementController@postAddCommentAnnouncement'
	));

	Route::get('employee/group/show/announcements/show-announcement-comment',array(
		'uses'=>'AnnouncementController@getAnnouncementComment'
	));
	//////////////////////////////////////////////////////////////////////////////////////
	/*
	*	edit profile
	*/
	Route::get('/account/edit-profile',array(
		'as'=>'account-edit-profile',
		'uses'=>'AccountController@editAccount'
	));

	Route::post('/account/edit-profile-post',array(
		'as'=>'account-edit-profile-post',
		'uses'=>'AccountController@postEditAccount'
	));
	
	Route::post('/account/img-upload',array(
		'as'=>'image-upload',
		'uses'=>'AccountController@imgupload'
	));


/************************** Connection Info*********************************************************/

	Route::post('/api/post/connection',array(
		'uses'=>'ConnectionInfoController@createConnection'
	));


	Route::get('/api/get/getAllOnlineFriends',array(
		'uses'=>'ConnectionInfoController@getOnlineUsers'
	));


/*************************************** profile ***************************************************/
Route::post('/user/edit-profile',array(
	'as'=>'user-edit-profile',
	'uses'=>'AccountController@postEditAccount'
));


Route::get('/test55',array(
    'uses'=>'ChatController@getAllFrindsMsg'
));
/*************************************** Assignment ***************************************************/
	Route::get('/Assignments/{gId}',array(
		'as'=>'assignment-admin',
    	'uses'=>'AssignmentController@getAssignmentAdmin'
	));
	Route::post('/Assignments/{gId}',array(
		'as'=>'add-assignment',
    	'uses'=>'AssignmentController@postAssignmentAdmin'
	));
	Route::get('/Assignments/category/{gId}/{target}',array(
		'as'=>'category-assignment',
    	'uses'=>'AssignmentController@postAssignmentCategoryAdmin'
	));
	Route::get('/Assignment/filter',function(){
		return View::make('Assignment/filter_assignment');
	});

	Route::get('/Assignment/{gId}/{aId}',array(
		'as'=>'view-more',
    	'uses'=>'AssignmentController@getViewMoreAdmin'
	));
	
	Route::post('/Assignment/{aId}',array(
		'as'=>'update-assignment',
    	'uses'=>'AssignmentController@postViewMoreAdmin'
	));
/**************************************** Notification **************************************************/

Route::get('api/get/notification/',array(
		'as'=>'notification',
    	'uses'=>'NotificationController@get_notification'
	));

Route::get('api/get/notification/count',array(
    	'uses'=>'NotificationController@get_notification_count'
	));


Route::get('api/get/notification/update',array(
    	'uses'=>'NotificationController@UpdateNotifications'
	));

Route::get('/vote-chart',function(){
	return View::make('charts/vote-chart');
});


Route::get('api/get/vote/choice/count/{id}',array(
    	'uses'=>'VoteController@countChosenVote'
	));


Route::get('api/test',function(){
	return View::make('calendar.profileEdit');
});