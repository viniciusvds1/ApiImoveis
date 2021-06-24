<?php

namespace App\Http\Controllers\Api;
use App\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Validator;
use App\User;
use Symfony\Component\HttpKernel\Profiler\Profile;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    public function index()
    {
        $user = $this->user->paginate('10');
        return response()->json($user, 200);
    }


    public function show($id){
        try{

            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);
            return response()->json([

                    'data' => $user

                ], 200);

        } catch (\Exception $e){
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
    public function store(UserRequest $request){
        $data = $request->all();

        if(!$request->has('password')){
            $message = new ApiMessages('É necessário informar uma senha para usuário...');
            return response()->json($message->getMessage(), 401);

        }
        validator::make($data,[
            'phone' => 'required',
            'mobile_phone' => 'required'
        ])->validate();

        try{

            $data['password'] = bcrypt($data['password']);
            $user = $this->user->create($data);

            $user->profile()->create([
                    'phone' => $data['phone'],
                    'mobile_phone' => $data['mobile_phone']
            ]);
            return response()->json([
                'data'=>[
                    'msg'=> 'Usuário cadastrado com sucesso!'
                ]
                ], 200);

        } catch (\Exception $e){

            return response()->json(['error'=>$e->getMessage()], 401);
        }

    }

    public function update($id, UserRequest $request)
    {
        $data = $request->all();
        if(!$request->has('password') && !$request->get('password')){
            $data['password'] = bcrypt($data['password']);
        } else{
            unset($data['password']);
        }
        validator::make($data,[
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required'
        ])->validate();
        try{

            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);

            $user = $this->user->findOrFail($id);
            $user->update($data);

            $user->profile()->update($profile);
            return response()->json([
                'data'=>[
                    'msg'=> 'Usuário atualizado com sucesso!'
                ]
                ], 200);

        } catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 401);
        }

    }
    public function destroy($id){

        try{

            $user = $this->user->findOrFail($id);
            $user->delete();
            return response()->json([
                'data'=>[
                    'msg'=> 'Usuário removido com sucesso!'
                ]
                ], 200);

        } catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 401);
        }
    }

}
