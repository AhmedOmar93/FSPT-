<?php
/***  php artisan brainsocket:start --port=8081  ***/
require __DIR__.'/../vendor/autoload.php';

Event::listen('chat.send',function($client_data){	
	$chat_room = $client_data->data->chat_room;
	$user_id = $client_data->data->user_id;	
	$message = $client_data->data->body;
	$chat = new  ChatController();
		$users = $chat->getChatRoomUsers($chat_room);
		return BrainSocket::message('chat.send',array('users'=>$users ));	
});

Event::listen('app.success',function($client_data){
    return BrainSocket::success(array('There was a Laravel App Success Event!'));
});

Event::listen('app.error',function($client_data){
    return BrainSocket::error(array('There was a Laravel App Error!'));
});

Event::listen('app.close',function($client_data){
	$user = Users::getUserByConnId($client_data->data->connId,$client_data->data->conn_session);
	//Users::CloseConnection($user->id);
    return BrainSocket::message('app.close',array('user_id' => $user->users));
});


Event::listen('close.session',function($client_data){
	$user = Users::getUserByConnId($client_data->data->connId,$client_data->data->conn_session);
	Users::CloseConnection($user->id);
    return;
});
/*
$request = Request::instance();
$request->setTrustedProxies(array('127.0.0.1')); // only trust proxy headers coming from the IP addresses on the array (change this to suit your needs)
$ip = $request->getClientIp();
*/