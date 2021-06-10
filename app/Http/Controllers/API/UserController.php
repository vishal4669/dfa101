<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
use App\Mail\ForgotPasswordMail;
use Mail;

class UserController extends Controller 
{

public $successStatus = 200;

	/** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['message'] =  'Login Successful'; 
            return response()->json(['success' => $success], $this->successStatus); 
        } else {

        	$userExists = User::where('email', request('email'))->first();
        	if(!$userExists){
        		return response()->json(['error'=>'Email does not exists'], '404'); 
        	} else {

        	}
            return response()->json(['error'=>'Invalid password'], '401'); 
        } 
    }

	/** 
     * Register api to register DFs 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 

    	$rules = [
                'email' => 'required|email|unique:UsersDF_101'
            ];

        $messages = [
            'email.required' => 'The Email field is required',
            'email.email' => 'The Email should be a valid email',
            'email.unique' => 'User is already registered',
        ];

        $validator = Validator::make( $request->all(), $rules, $messages );

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()], '401'); 
        }

        $password = 'DataFirst@123';

		$input = $request->all(); 
        $input['password'] = bcrypt($password); 
        $user = User::create($input); 

        $success['message'] =  'User registered successfully'; 
        return response()->json(['success'=>$success], $this->successStatus); 
    }
	
    /* Send email to user for reset password link */
    public function forgotPassword(Request $request){
        $email = request('email'); 
        $useremail = User::where('email', '=', $email)->pluck('email')->first();

        if($useremail && $useremail!=''){
            $data['password'] = 'DataFirst@123';

            try{
            	$mailStatus = Mail::to($email)->send(new ForgotPasswordMail($data));

	            if($mailStatus){
	            	return response()->json(['success'=>'Email sent to customer.'], '200');
	            } else{
	            	return response()->json(['error'=>'Problem in sending mail to customer.'], '200');
	            }
            } catch (\Swift_TransportException $e){
            	return response()->json([
            			'error'=>'Problem in sending mail to customer.'], '200');
            }
        } else{
        	return response()->json(['error'=>'Entered email is not registered, Please try again with registered email.'], '401'); 
        }
    }

    /** 
     * Api to approve registered users with there email 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function approveAccount(Request $request) 
    { 

    	$rules = [
                'email' => 'required|email'
            ];

        $messages = [
            'email.required' => 'The Email field is required',
            'email.email' => 'The Email should be a valid email'
        ];

        $validator = Validator::make( $request->all(), $rules, $messages );

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()], '401'); 
        }

        $user = User::where('email', '=', request('email'))->update(['status' => 1]);
        $success['message'] =  'User approved successfully';

        return response()->json(['success'=>$success], $this->successStatus); 
    }

    /** 
     * Api to set user account details
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function setAccountDetails(Request $request) 
    { 

    	$rules = [
                'email' => 'required|email',
                'name' => 'required',
                'account' => 'required',
                'telephone' => 'required',
            ];

        $messages = [
        	'email.required' => 'The Email field is required',
        	'email.email' => 'The Email should be a valid email',
            'name.required' => 'The Name field is required.',
            'account.required' => 'The Account field is required.',
            'telephone.required' => 'The Telephone field is required.',
        ];

        $validator = Validator::make( $request->all(), $rules, $messages );

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()], '401'); 
        }

        $email = request('email'); 
        $useremail = User::where('email', '=', $email)->pluck('email')->first();

        if($useremail && $useremail!=''){

	        $user = User::where('email', '=', request('email'))
	        			->update([
	        				'name' => request('name'),
	        				'account' => request('account'),
	        				'telephone' => request('telephone'),
	        			]); 

	        $success['message'] =  'User account details updated successfully';

	        return response()->json(['success'=>$success], $this->successStatus); 
	    } else {
	    	return response()->json(['error'=>'Entered email is not registered, Please try again with registered email.'], '401');
	    }
    }

    /** 
     * Api to set user account details
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function setAccountPassword(Request $request) 
    { 
    	$rules = [
                'email' => 'required|email',
                'password' => 'required'
            ];

        $messages = [
        	'email.required' => 'The Email field is required',
        	'email.email' => 'The Email should be a valid email',
            'password.required' => 'The Password field is required.'
        ];

        $validator = Validator::make( $request->all(), $rules, $messages );

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()], '401'); 
        }

        $email = request('email'); 
        $useremail = User::where('email', '=', $email)->pluck('email')->first();

        if($useremail && $useremail!=''){
	        $user = User::where('email', '=', request('email'))
	        			->update([
	        				'password' => bcrypt(request('password'))
	        			]); 

	        $success['message'] =  'User account password updated successfully';

	        return response()->json(['success'=>$success], $this->successStatus); 
	    } else {
	    	return response()->json(['error'=>'Entered email is not registered, Please try again with registered email.'], '401');
	    }
    }

}