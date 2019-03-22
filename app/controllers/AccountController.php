<?php
class AccountController extends BaseController
{
    public  function getCreate(){
        return View::make('account.create');
    }
    public  function postCreate()
    {
        // print_r(Input::all());
        $validator = Validator::make(Input::all(),
            array(
                'email' => 'required | max:50 | email | unique:users',
                'user_name' => 'required | max:20 | min:3 | unique:users',
                'password' => 'required |min:6',
                'password_again' => 'required | same:password',
                'first_name'=>'required | max:20 | min:3',
                'middle_name'=>'required | max:20 | min:3',
                'last_name'=>'required | max:20 | min:3',
                'user_code'=>'required | max:20 | min:6 |unique:users',
                'profession'=>'required'
            )
        );

        if ($validator->fails()) {
            //die('Failed.');
            return Redirect::route('account-create')
                ->withErrors($validator)
                ->withInput();
        } else {
            // Activation Code
            $code = str_random(60);

            $user = array(
                'first_name'=>Input::get('first_name'),
                'middle_name'=>Input::get('middle_name'),
                'last_name'=>Input::get('last_name'),
                'email' => Input::get('email'),
                'user_code'=>Input::get('user_code'),
                'user_name' => Input::get('user_name'),
                'profession'=>Input::get('profession'),
                'password' => Hash::make(Input::get('password')),
                'profile_picture'=>'no_user.jpg',
                'color'=>'Purple',
                'gender'=>Input::get('gender'),
                'active_code' => $code,
                'profile_picture'=>'no_user.jpg',
                'active' => 0,
                'created_at'=>Carbon\Carbon::now()->toDateTimeString(),
                'updated_at'=>Carbon\Carbon::now()->toDateTimeString()
            );



            if (Users::create($user)) {

                //Send Mail
                Mail::send('emails.auth.activate',array('link'=>URL::route('account-activate',$code),'username'=>Input::get('user_name')),function($message) use ($user){
                    $message->to(Input::get('email'),Input::get('username'))->subject('Activate your Account');
                });
                return Redirect::route('account-sign-in')
                    ->with('global', 'Your account has created! we have send you an email to activate');
            }
        }
    }


    public function getActivate($activeCode){
       // return $code;

        $user = Users::getUserByActivationCode($activeCode,0);
        if($user != null){
            //update user
            $userUpdate = array(
                'active_code'=>'',
                'active'=>1
            );

            if(Users::update($user->id,$userUpdate)){
               return Redirect::route('account-sign-in')
                   ->with('global','Activated you can now sign in !!');
            }
        }

        return Redirect::route('account-sign-in')
            ->with('global','you could not activate your account ! try again later');
    }

    public  function getSignIn(){
        return View::make('account.signin');
    }

    public  function postSignIn(){
        
        $validator = Validator::make(Input::all(),
            array(
                'user_code' => 'required',
                'password' => 'required'
            )
        );

        if($validator->fails()){
            //redirect sign in page
            return Redirect::route('account-sign-in')
                ->withErrors($validator)
                ->withInput();
        }
        else{
            //Attemp user sign       in

            $remember = (Input::has('remember')) ? true : false;

            $auth = Auth::attempt(
                array(

                    'user_code' => Input::get('user_code'),
                    'password' => Input::get('password'),
                    'active' => 1

                     ),$remember
            );

           // $user = User::signIn(Input::get('user_code'),Hash::make(Input::get('password')),1);
            if($auth){
                // redirect to the intended page
                return Redirect::intended('/home');

            }else{
                return Redirect::route('account-sign-in')
                    ->with('global','Code or password Wrong Or Account no activated');
            }
        }
        return Redirect::route('account-sign-in')
            ->with('global','There is a problem sign you in');

    
    }

    public function getSignOut()
    {
        Auth::logout();
        return Redirect::route('account-sign-in');
    }



    public function getChangePassword()
    {
        return View::make('account.password');
    }

    public  function postChangePassword()
    {
        $validator = Validator::make(Input::all(),
            array(
                'old_password'=>'required',
                'password' => 'required |min:6',
                'password_again' => 'required | same:password'

            )
        );

        if($validator->fails()){

            return Redirect::route('account-change-password')
                ->withErrors($validator)
                ->withInput();

        }else{

            //get current user
            $user = User::find(Auth::user()->id);
            $old_password = Input::get('old_password');
            $password = Input::get('password');

            if(Hash::check($old_password,$user->getAuthPassword())){

                $user->password = Hash::make($password);

                if($user->save()){
                    $response = array(
                        'state'=>'success'
                    );
                    return Response::json($response);
                }
            }else{
                $response = array(
                    'state'=>'Your old password is incorrect'
                );
                return Response::json($response);
            }

        }
                       $response = array(
            'state'=>'could not change password'
        );
        return Response::json($response);
    }

    public  function changeEmail(){

        $data = Users::checkEmail(Input::get('old_Email'));

        if($data != NULL) {
            $code = str_random(60);
            $changeData = array(
                'email'=>Input::get('Email'),
                'active'=>0,
                'active_code'=>$code
            );
            if(Users::update(Auth::user()->id,$changeData)) {


                //Send Mail
                Mail::send('emails.auth.activate', array('link' => URL::route('account-activate', $code), 'username' => Auth::user()->user_name), function ($message) {
                    $message->to(Input::get('Email'), Auth::user()->user_name)->subject('Activate your Account');
                });
                $response = array(
                    'state'=>'success'
                );
                return Response::json($response);

            }else{
                $response = array(
                    'state'=>'error'
                );
                return Response::json($response);
            }
        }else{
            $response = array(
                'state'=>'oldValid'
            );
            return Response::json($response);
        }
    }

    public function getForgotPassword()
    {
        return View::make('account.forgot');
    }

    public function postForgotPassword()
    {
        $validator = Validator::make(Input::all(),
            array(
                'email' => 'required | email'
            )
        );

        if($validator->fails()){
            //redirect sign in page
            return Redirect::route('account-forgot-password')
                ->withErrors($validator)
                ->withInput();
        }
        else {

            $user = Users::getUserByEmail(Input::get('email'));
            if($user->id != null){
                $code = str_random(60);
                $password = str_random(10);

                $update = array(
                    'active_code'=>$code,
                    'password_temp'=>Hash::make($password)
                );

                if(Users::update($user->id,$update))
                {
                    //Send Mail
                    Mail::send('emails.auth.recover',array('link'=>URL::route('account-recover',$code),'username'=>$user->user_name,'password'=>$password),function($message) use($user){
                        $message->to($user->email,$user->user_name)->subject('Your new password');
                    });
                    return View::make('account.signin')
                        ->with('global','the new password sed to your mail!');
                }


            }
        }

        return View::make('account.signin')
        ->with('global','Could not request new password !');
        }

    public function getRecover($code){
        $user = Users::getUserByCodeToRecover($code);
        if($user->id != null){
            //update user
            $update = array(
                'password'=>$user->password_temp,
                'active_code'=>'',
                'password_temp'=>''
            );

            if(Users::update($user->id,$update))
            {
                return View::make('account.signin');
            }
        }

        return Redirect::route('home')
            ->with('global','Could not recover your account !');

        }

//////////////////////////////////////////////////////////////
	public function editAccount(){
		return View::make('account.editaccount');
		}

	public function postEditAccount(){
        $data = array(
            'first_name'=>Input::get('first_name'),
            'middle_name'=>Input::get('middle_name'),
            'last_name'=>Input::get('last_name'),
            'user_name'=>Input::get('user_name'),
            'street'=>Input::get('street'),
            'city'=>Input::get('city'),
            'country'=>Input::get('country'),
            'phone'=> Input::get('phone'),
            'department'=>Input::get('dept'),
            'level'=>Input::get('level'),
            'birth_date'=>Input::get('DOB'),
            'website'=>Input::get('website'),
            'aboutMe'=>Input::get('aboutMe')
        );

        $state=User::where('id','=',Auth::user()->id)->update($data);
		$response=array(
		'state' => 'success in update information .'
	);

	return Response::json($response);
	}
	
	
	public function imgupload(){

       //var_dump(Input::file('file'));

        if (Input::hasFile('file')) {
            $filename = str_random(10);
            $extension = Input::file('file')->getClientOriginalExtension();
            $destination_path = 'public/images/';
            try {
                $file = Input::file('file')->move($destination_path, $filename.'.'.$extension);
                $data = array(
                    'profile_picture'=>$filename.'.'.$extension,
                );
                $state = User::where('id','=',Auth::user()->id)->update($data);
                $response=array(
                    'state' => 'success'
                );
                return Redirect::route('profile-user',Auth::user()->user_code);
               // return Response::json($response);
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
            $response=array(
                'state' => 'error'
            );
            //return Response::json($response);
        }
	}

    public function ChangeColor($color){
        $data = array(
            'color'=>$color
        );

       $check = Users::update(Auth::user()->id,$data);
        if($check == true){
            $response = array(
                'state'=>'success'
            );
            return Response::json($response);
        }else{
            $response = array(
                'state'=>'error'
            );
            return Response::json($response);
        }
    }

}