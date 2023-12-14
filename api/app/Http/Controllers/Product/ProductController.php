<?php

namespace App\Http\Controllers\Product;

// ================================>> Core Library
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// ================================>> Third Party
use Carbon\Carbon;

// ================================>> Custome Library
use App\Http\Controllers\Controller;
use App\Models\Product\Product;

use App\Services\FileUpload;

class ProductController extends Controller
{
    public function listing(Request $req)
    {
        $data = Product::select('*')
        ->with(['type'])
        ;
        //Filter
        if ($req->key && $req->key != '') {
            $data = $data->where('code', 'LIKE', '%' . $req->key . '%')->Orwhere('name', 'LIKE', '%' . $req->key . '%');
        }

        if ($req->type && $req->type != 0) {
            $data = $data->where('type_id', $req->type);
        }

        $data = $data->orderBy('id', 'desc')->paginate($req->limit ? $req->limit : 10,'per_page');
        return response()->json($data, Response::HTTP_OK);

    }

    public function view($id = 0)
    {
        $data = Product::select('*')->find($id); 
        if($data){

            return response()->json($data, Response::HTTP_OK);
            
        }else{

            return response()->json([
                'status'    => 'fil',
                'message'   => 'គ្មានទិន្ន័យ',
            ], Response::HTTP_BAD_REQUEST);
        }
        
    }

    public function create(Request $req)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'              => 'required|max:50',
                'code'              => 'required|max:20',
                'unit_price'        => 'required|numeric',
                'type_id'           => 'required|exists:products_type,id'
            ],
            [
                'name.required'         => 'សូមបញ្ចូលឈ្មោះផលិតផល',
                'name.max'              => 'ឈ្មោះផលិតផលមិនអាចលើសពី50ខ្ទង់',

                'code.required'         => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផល',
                'code.max'              => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផលមិនអាចលើសពី២០ខ្ទង់',

                'unit_price.required'   => 'សូមបញ្ចូលតម្លៃរាយ', 
                'unit_price.numeric'    => 'សូមបញ្ចូលតម្លៃរាយជាលេខ', 

                'type_id.exists'        => 'សូមជ្រើសរើសឈ្មោះផលិតផល អោយបានត្រឹមត្រូវ កុំបោកពេក'
                
            ]
        );

        //==============================>> Start Saving Data to Database
        $product                =   new Product;
        $product->name          =   $req->name;
        $product->code          =   $req->code;
        $product->type_id       =   $req->type_id;
        $product->unit_price    =   $req->unit_price;
        $product->save();

        //==============================>> Start Uploading Image to File Server   
        if ($req->image) {
            // Need to create folder before storing images       
            $folder = Carbon::today()->format('d') . '-' . Carbon::today()->format('M') . '-' . Carbon::today()->format('Y');
            $image  = FileUpload::uploadFile($req->image, 'products/' . $folder, $req->fileName);
            
            //if ($image['url']) {
                $product->image = $image['url'];
                $product->save();
            //}
        }

        return response()->json([
            'data'      =>  Product::select('*')->with(['type'])->find($product->id),
            'message'   => 'ផលិតផលត្រូវបានបង្កើតដោយជោគជ័យ។'
        ], Response::HTTP_OK);
    }

    public function update(Request $req, $id = 0)
    {
        // return "Yes"; 
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'              => 'required|max:20',
                'code'              => 'required|max:20',
                'unit_price'        => 'required',
                'type_id'           => 'required|exists:products_type,id'
            ],
            [
                'name.required'         => 'សូមបញ្ចូលឈ្មោះផលិតផល',
                'name.max'              => 'ឈ្មោះផលិតផលមិនអាចលើសពី២០ខ្ទង់',
                'unit_price.required'   => 'សូមបញ្ចូលឈ្មោះ unit_price',
                'code.required'         => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផល',
                'code.max'              => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផលមិនអាចលើសពី២០ខ្ទង់',
                'type_id.exists'        => 'សូមជ្រើសរើសឈ្មោះផលិតផល'
            ]
        );

        //==============================>> Start Updating data
        $product                         = Product::find($id);
        if ($product) {

            $product->name          = $req->name;
            $product->code          = $req->code;
            $product->type_id       = $req->type_id;
            $product->unit_price    = $req->unit_price;

            // Save to DB
            $product->save();

            
            // Image Upload
            if ($req->image) {

                // Need to create folder before storing images
                //$folder = Carbon::today()->format('d') . '-' . Carbon::today()->format('M') . '-' . Carbon::today()->format('Y');
                $folder = Carbon::today()->format('d-m-y');

                //return $folder; 

                $image  = FileUpload::uploadFile($req->image, 'products/', $req->fileName);

                //return $image; 

                if ($image['url']) {

                    $product->image     = $image['url'];
                    $product->save();

                }
            }

            // Prepare Data backt to Client
            $product = Product::select('*')
            ->with([
                'type'
            ])
            ->find($product->id);

            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ផលិតផលត្រូវបានកែប្រែជោគជ័យ',
                'product'   => $product,
            ], Response::HTTP_OK);

        } else {

            return response()->json([

                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',
                
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    public function delete($id = 0)
    {
        $data = Product::find($id);

        //==============================>> Start deleting data
        if ($data) {
            $data->delete();
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានលុប',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
