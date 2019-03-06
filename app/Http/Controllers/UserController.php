<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function login(Request $request)
    {

        if((\Auth::attempt(['phone_no'=>request('phone_no'), 'password'=>request('password')]))){

        return response()->json([
            'status'=>201,
            'message'=>'Login Was Successful'
        ]);
            }
                else{
            return response()->json([
                'status'=>404,
                'message'=>'Unauthorized User'
            ]);
     }
    }


     public function index()
    {

        $user= User::all();
        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(count(User::whereEmail($request->email)->get())>=1){
            return response()->json([
                'status'=>501,
                'message'=>'Email address already exist, enter another address
                 or get out of here!!!!'
            ]);
        }
        else if(count(User::wherePhone_no($request->phone_no)->get()) >=1){
            return response()->json([
                'status'=>503,
                'message'=>'Phone number already exist, please input another number'
            ]);
        }else{
        $user=new User([
            'fname' =>$request->fname,
            'lname' =>$request->lname,
            'email' =>$request->email,
            'password'=>\Hash::make($request->password),
            'phone_no'=>$request->phone_no,
        ]);
            if ($user->save() );
            return response()->json([
                'status'=>200,
                'message'=>'Nice one buddy, It is saved to the database!!!'
            ]);
        }

            }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::whereId($id)->first();

        if(!$user){
            return response()->json([
             'status'=>'404',
             'message' => 'This user is not found in the database'
            ]);
        }
        return response()->json([
            'status'=>'200',
             'message' => 'Successful',
             'result' => [
                 'Id'=>$user->id,
                 'name'=>$user->name,
                 'email'=>$user->email
             ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     $user=User::find($id);
     return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $user=User::whereId($id)->first();
        $driver->password=\Hash::make($request->password);
            if($user->save()){
            return response()->json([
                'status'=>201,
                'message'=> 'Password Updated...'
            ]);
             }else{
                return response()->json([
                        'status' =>419,
                        'message' => 'Password not updated'
                ]);
            }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($user->delete()){
            return response()->json([
                'status'=>200,
                'message'=>'User details was successfully deleted'
            ]);
            }else{
                    return response()->json([
                   'status'=> 500,
                   'message'=>'User details not deleted',
               ]);
    }
    }
}
