<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductTypeController extends Controller
{
    public function listing()
    {
        $data = Type::select('id', 'name')
        ->withCount(['products as n_of_products'])
        ->orderBy('name', 'ASC')
        ->get();
        
        return response()->json($data, Response::HTTP_OK);
    }

    public function create(Request $req)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'             => 'required|max:20',
            ],
            [
                'name.required'    => 'សូមបញ្ចូលឈ្មោះប្រភេទផលិតផល',
                'name.max'         => 'ឈ្មោះប្រភេទផលិតផលមិនអាចលើសពី២០ខ្ទង់',
            ]
        );

        //==============================>> Start Adding data

        $product_type           =   new Type;
        $product_type->name     =   $req->name;
        $product_type->save();

        return response()->json([
            'product_type'  => $product_type,
            'message'       => 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ។'
        ], Response::HTTP_OK);
    }

    public function update(Request $req, $id = 0)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'          =>  'required|max:20',
            ],
            [
                'name.required' => 'សូមបញ្ចូលឈ្មោះប្រភេទផលិតផល',
                'name.max'      => 'ឈ្មោះប្រភេទផលិតផលមិនអាចលើសពី២០ខ្ទង់',
            ]
        );

        //==============================>> Start Updating data
        $product_type           = Type::find($id);
        if ($product_type) {

            $product_type->name = $req->name;
            $product_type->save();

            return response()->json([
                'status'        => 'ជោគជ័យ',
                'message'       => 'ប្រភេទផលិតផលត្រូវបានកែប្រែជោគជ័យ!',
                'product_type'  => $product_type,
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
        $data = Type::find($id);

        //==============================>> Start deleting data
        if ($data) {
            $data->delete();
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានលុប'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
