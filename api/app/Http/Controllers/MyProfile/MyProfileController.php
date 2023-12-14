<?php

namespace App\Http\Controllers\MyProfile;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Services\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class MyProfileController extends Controller
{
    public function view(){

        $auth = JWTAuth::parseToken()->authenticate();
        $user = User::select('id', 'name', 'phone', 'email', 'avatar')->where('id', $auth->id)->first();
        return response()->json($user, Response::HTTP_OK);
        
    }

    public function update(Request $req){
        
        //========================================================>>>> Data Validation
        $this->validate(
            $req,
            [
                'name'  => 'required|max:60',
                'phone' => 'required|min:9|max:10',
            ],
            [
                'name.required'     => 'សូមបញ្ចូលឈ្មោះ',
                'name.max'          => 'ឈ្មោះមិនអាចលើសពី៦០',
                'phone.required'    => 'សូមបញ្ចូលលេខទូរស័ព្ទ',
                'phone.min'         => 'សូមបញ្ចូលលេខទូរស័ព្ទយ៉ាងតិច៩ខ្ទង់',
                'phone.max'         => 'លេខទូរស័ព្ទយ៉ាងច្រើនមិនលើសពី១០ខ្ទង់'

            ]
        );

        //========================================================>>>> Start to update user
        
        $user = User::findOrFail(JWTAuth::parseToken()->authenticate()->id);

        if ($user) {

            // Pair information
            $user->name         = $req->name;
            $user->phone        = $req->phone;
            $user->email        = $req->email;
            $user->updated_at   = Carbon::now()->format('Y-m-d H:i:s');

             // avatar Upload
             if ($req->avatar) {

                // Need to create folder before storing avatars
                //$folder = Carbon::today()->format('d') . '-' . Carbon::today()->format('M') . '-' . Carbon::today()->format('Y');
                $folder = Carbon::today()->format('d-m-y');

                //return $folder; 

                $avatar  = FileUpload::uploadFile($req->avatar, 'my-profile/', $req->fileName);

                //return $avatar; 

                if ($avatar['url']) {

                    $user->avatar     = $avatar['url'];
                    $user->save();

                }
            }

            // Save to DB
            $user->save();

            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => '* ព័ត៌មានផ្ទាល់ខ្លួនរបស់អ្នកត្រូវបានកែប្រែ *',
                'data'      => [
                        'name'  => $user->name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                        'avatar' => $user->avatar,
                ]
            ], Response::HTTP_OK);

        }else{

            return response()->json([
                'status' => 'error',
                'message' => 'ទិន្នន័យរបស់អ្នកមិនត្រឹមត្រូវ'
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    public function changePassword(Request $req){

        //========================================================>>>> Data Validation
        $this->validate(
            $req,
            [
                'old_password'      => 'required|min:6|max:20',
                'new_password'      => 'required|min:6|max:20',
                'confirm_password'  => 'required|min:6|max:20|same:new_password',
            ],
            [
                'old_password.required'     => 'សូមបញ្ចូលពាក្យសម្ងាត់',
                'old_password.min'          => 'ពាក្យសម្ងាត់ចាស់ ត្រូវមាន៦ ខ្ទង់យ៉ាងតិច',
                'old_password.max'          => 'ពាក្យសម្ងាត់ចាស់ ត្រូវមាន២០ ច្រើនបំផុត',

                'new_password.required'     => 'សូមបញ្ចូលពាក្យសម្ងាត់ថ្មី',
                'new_password.min'          => 'ពាក្យសម្ងាត់ថ្មី ត្រូវមាន៦ ខ្ទង់យ៉ាងតិច',
                'new_password.max'          => 'ពាក្យសម្ងាត់ថ្មី ត្រូវមាន២០ ច្រើនបំផុត',
                
                'confirm_password.same'     => 'សូមបញ្ចាក់ថាពាក្យសម្ងាត់ថ្មី'

            ]
        );

        //========================================================>>>> Update user Info
        $auth   = JWTAuth::parseToken()->authenticate();
        $user   = User::findOrFail($auth->id);

        if (Hash::check($req->old_password, $user->password)) { // Check comparision old & new password
            
            // Pair Passowrd Field
            $user->password = Hash::make($req->password);

            // Save to DB
            $user->save();

            // Make success response to client
            return response()->json([
                'status'    => 'success',
                'message'   => 'លេខសម្ងាត់របស់អ្នកត្រូវបានកែប្រែដោយជោគជ័យ'
            ], Response::HTTP_OK);

        } else {

            // Make fail response to client
            return response()->json([
                'status'    => 'error',
                'message'   => 'ពាក្យសម្ងាត់ចាស់របស់អ្នកមិនត្រឹមត្រូវ'
            ], Response::HTTP_BAD_REQUEST);

        }
    }

}
