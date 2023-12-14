<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\Type;
use App\Models\User\User;
use App\Services\FileUpload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function listing(Request $req)
    {
        $data = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')->with(['type']);
        //Filter
        if ($req->key && $req->key != '') {
            $data = $data->where('name', 'LIKE', '%' . $req->key . '%')->Orwhere('phone', 'LIKE', '%' . $req->key . '%');
        }
        $data = $data->orderBy('id', 'desc')
        ->paginate($req->limit ? $req->limit : 10,);
        return response()->json($data, Response::HTTP_OK);
    }

    public function view($id = 0)
    {
        $data = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')->with(['type'])->find($id);
        if ($data) {
            return response()->json($data, 200);
        } else {
            return response()->json([
                'status'  => 'fail',
                'message' => 'រកទិន្នន័យមិនឃើញក្នុងប្រព័ន្ធ'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    public function create(Request $req)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'type_id'     => 'required|min:1|max:20',
                'name'     => 'required|min:1|max:20',
                'phone'    => 'required|unique:user,phone',
                'password' => 'required|min:6|max:20',
                'email'    => 'unique:user,email'
            ],
            [
                'name.required'     => 'សូមវាយបញ្ចូលឈ្មោះរបស់អ្នក',
                'phone.required'    => 'សូមវាយបញ្ចូលលេខទូរស័ព្ទរបស់អ្នក',
                'phone.unique'      => 'លេខទូរស័ព្ទនេះត្រូវបានប្រើប្រាស់រួចហើយនៅក្នុងប្រព័ន្ធ',
                'email.unique'      => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ',
                'password.required' => 'សូមវាយបញ្ចូលពាក្យសម្ងាត់របស់អ្នក',
                'password.min'      => 'សូមបញ្ចូលលេខសម្ងាត់ធំជាងឬស្មើ៦',
                'password.max'      => 'សូមបញ្ចូលលេខសម្ងាត់តូចឬស្មើ២០'
            ]
        );

        //==============================>> Start Adding data
        $user            =   new User;
        $user->name      =   $req->name;
        $user->type_id   =   $req->type_id;
        $user->phone     =   $req->phone;
        $user->email     =   $req->email;
        $user->password  =   Hash::make($req->password);
        $user->is_active =   1;
        $user->avatar    =   'static/icon/user.png';

        // Need to create folder before storing images
        if ($req->image) {
            $image     = FileUpload::uploadFile($req->image, 'users', $req->fileName);
            if ($image['url']) {
                $user->avatar = $image['url'];
            }
        }

        $user->save();

        return response()->json([
            'user'  => User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')->with(['type'])->find($user->id),
            'message' => 'User: ' . $user->name . ' has been successfully created.'
        ], Response::HTTP_OK);
    }
    public function update(Request $req, $id = 0)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'     => 'required',
                'phone'    => 'required',
            ],
            [
                'name.required'     => 'សូមវាយបញ្ចូលឈ្មោះរបស់អ្នក',
                'phone.required'    => 'សូមវាយបញ្ចូលលេខទូរស័ព្ទរបស់អ្នក',
            ]
        );

        $check_phone  = User::where('id','!=',$id)->where('phone',$req->phone)->first();
        if($check_phone){
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'លេខទូរស័ព្ទនេះត្រូវបានប្រើប្រាស់រួចហើយនៅក្នុងប្រព័ន្ធ',
            ], Response::HTTP_BAD_REQUEST);
        }
        $check_email  = User::where('id','!=',$id)->where('email',$req->email)->first();
        if($check_email){
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ',
            ], Response::HTTP_BAD_REQUEST);
        }

        //==============================>> Start Updating data
        $user = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')->with(['type'])->find($id);
        if ($user) {

            $user->name      =   $req->name;
            $user->type_id   =   $req->type_id;
            $user->phone     =   $req->phone;
            $user->email     =   $req->email;
            $user->is_active =   $req->is_active;

            // Need to create folder before storing images
            if ($req->image) {
                $image     = FileUpload::uploadFile($req->image, 'users', $req->fileName);
                if ($image['url']) {
                    $user->avatar = $image['url'];
                }
            }

            $user->save();

            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានកែប្រែ',
                'user'      => $user,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវទេ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    public function delete($id = 0)
    {
        $data = User::find($id);

        //==============================>> Start deleting data
        if ($data) {

            $data->delete();

            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានលុយចេញពីប្រព័ន្ធ',
            ], Response::HTTP_OK);
        } else {

            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
    public function changePassword(Request $req, $id = 0)
    {
        //==============================>> Check validation
        $this->validate($req, [
            'password' => 'required|min:6|max:20',
            'confirm_password'  => 'required|same:password',
        ], [
            'password.required' => 'សូមបញ្ចូលលេខសម្ងាត់',
            'password.min'      => 'សូមបញ្ចូលលេខសម្ងាត់ធំជាងឬស្មើ៦',
            'password.max'      => 'សូមបញ្ចូលលេខសម្ងាត់តូចឬស្មើ២០',
            'confirm_password.required' => 'សូមបញ្ចូលបញ្ជាក់ពាក្យសម្ងាត់',
            'confirm_password.same'     => 'សូមបញ្ចូលបញ្ជាក់ពាក្យសម្ងាត់ឲ្យដូចលេខសម្ងាត់',

        ]);

        //==============================>> Start Updating Password
        $user = User::find($id);
        if ($user) {
            $user->password  = Hash::make($req->password);
            $user->password_last_updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();
            return response()->json(['message' => 'លេខសម្ងាត់របស់ត្រូវបានកែប្រែ', 'user' => $user], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'មិនមានទិន្នន័យក្នុងប្រព័ន្ធ'], Response::HTTP_BAD_REQUEST);
        }
    }
    public function getType()
    {
        $data = Type::get();
        return $data;
    }

    public function block(Request $req, $id = 0)
    {
        //==============================>> Start Updating data
        $user = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')->with(['type'])->find($id);
        if ($user) {

            $user->is_active  =  !$user->is_active;
            $user->save();

            return response()->json([
                'status'    => 'Success',
                'message'   => 'User successfully modified',
                'user'      => $user,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status'    => 'Fail',
                'message'   => 'Invalid data',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
/*
|--------------------------------------------------------------------------
| Develop by: Yim Klok
|--------------------------------------------------------------------------
|
| date: 23/02/2023. location: Manistry of public works and transport - MPWT
|
*/
