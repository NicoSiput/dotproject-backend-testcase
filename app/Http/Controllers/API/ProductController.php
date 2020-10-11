<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPicture;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with('productPrice', 'productPicture')->get();

        if ($product) {
            return ResponseFormatter::success($product, 'Success get product data');
        } else {
            return ResponseFormatter::error(null, 'No product', 404);
        }
    }

    public function search(Request $request)
    {
        $id = $request->input('id');
        $code = $request->input('code');
        $name = $request->input('name');
        $weight = $request->input('weight');

        $limit = $request->input('limit', 10);

        if ($id) {
            $product = Product::with('productPrice', 'productPicture')->find($id);

            if ($product) {
                return ResponseFormatter::success($product, 'Success get product data');
            } else {
                return ResponseFormatter::error(null, 'No product', 404);
            }
        }
        if ($code) {
            $product = Product::with('productPrice', 'productPicture')
                ->where('code', $code)
                ->first();

            if ($product) {
                return ResponseFormatter::success($product, 'Success get product data');
            } else {
                return ResponseFormatter::error(null, 'No product', 404);
            }
        }

        $product = Product::with('productPrice', 'productPicture');

        if ($name) {
            $product->where('name', 'like', '%' . $name . '%');
        }

        if ($weight) {
            $product->where('weight', $weight);
        }

        return ResponseFormatter::success($product->paginate($limit), 'Success get product data');
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
    public function store(ProductRequest $request)
    {
        $data = $request->except('product_prices', 'product_pictures');
        $product = Product::create($data);

        // Save product prices
        foreach ($request->product_prices as $price) {
            $prices[] = new ProductPrice([
                'productid' => $product->productid,
                'min_qty' => $price['min_qty'],
                'max_qty' => $price['max_qty'],
                'price' => $price['price'],
            ]);
        }
        $prices = $product->productPrice()->saveMany($prices);
        $product['product_prices'] = $prices;

        // Save product images
        foreach ($request->product_pictures as $picture) {
            $pictures[] = new ProductPicture([
                'productid' => $product->productid,
                'filepath' => $picture->store('assets/product', 'public'),
            ]);
        }
        $pictures = $product->productPicture()->saveMany($pictures);
        $product['product_pictures'] = $pictures;

        return ResponseFormatter::success($product, 'Success create data');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::with('productPrice', 'productPicture')->findOrFail($id);

        if ($product) {
            return ResponseFormatter::success($product, 'Success get product data');
        } else {
            return ResponseFormatter::error(null, 'No product', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        //
        try {
            $data = $request->except('product_price', 'product_pictures');
            $product = Product::findOrFail($id);
            $product->update($data);

            // update product prices
            $product->productPrice()->delete();
            foreach ($request->product_prices as $price) {
                $prices[] = new ProductPrice([
                    'productid' => $product->productid,
                    'min_qty' => $price['min_qty'],
                    'max_qty' => $price['max_qty'],
                    'price' => $price['price'],
                ]);
            }
            $prices = $product->productPrice()->saveMany($prices);
            $product['product_prices'] = $prices;


            // // update product picture
            // $pictures = ProductPicture::where('productid', $product->productid)->get('filepath');
            // $this->_removePictureStorage($pictures);
            // $product->productPicture()->delete();

            // // Save product images
            // foreach ($request->product_pictures as $picture) {
            //     $picture = new ProductPicture([
            //         'productid' => $id,
            //         'filepath' => $picture->store('assets/product', 'public'),
            //     ]);
            //     $pictures[] = $product->productPicture()->save($picture);
            // }
            // $product['product_pictures'] = $pictures;

            return ResponseFormatter::success($product, 'Success update data');
        } catch (ModelNotFoundException $e) {
            echo $e;
            return ResponseFormatter::error(null, 'Failed update data');
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
        // Will return a ModelNotFoundException if no user with that id
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            // Delete product price and product picture that related with this product
            $product->productPrice()->delete();
            $pictures = ProductPicture::where('productid', $product->productid)->get('filepath');
            $this->_removePictureStorage($pictures);
            $product->productPicture()->delete();

            $data = Product::all();
            return ResponseFormatter::success($data, 'Success delete data');
        }
        // catch(Exception $e) catch any exception
        catch (ModelNotFoundException $e) {
            return ResponseFormatter::error(null, 'Id not found', 404);
        }
    }

    private function _removePictureStorage($pictures)
    {
        // Remove picture from local storage
        foreach ($pictures as $picture) {
            // http://127.0.0.1:8000/storage/assets/product/
            $directory = "/storage/assets/product/";
            $url = url('') . $directory;
            $filename = str_replace($url, '', $picture->filepath);

            if (file_exists(public_path($directory . $filename))) {
                unlink(storage_path('app/public/assets/product/' . $filename));
            }
        }
    }
}
