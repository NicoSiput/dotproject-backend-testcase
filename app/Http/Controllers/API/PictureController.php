<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductPicture;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PictureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($productid)
    {
        //
        $picture = ProductPicture::where('productid', $productid)->get();

        if ($picture) {
            return ResponseFormatter::success($picture, 'Success get picture product data');
        } else {
            return ResponseFormatter::error(null, 'No product', 404);
        }
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
        $data = $request->all();
        $file = $request->file('picture');

        $data['filepath'] = $file->store('assets/product', 'public');

        $pictures = ProductPicture::create($data);
        return ResponseFormatter::success($pictures, 'Success save data');
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
    public function update(Request $request, $id)
    {
        //
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
            // $picture = ProductPicture::findOrFail($id);
            $picture = ProductPicture::findOrFail($id);
            $productId = $picture->productid;

            $this->_removePictureStorage($picture->filepath);
            $picture->delete();

            $picture = ProductPicture::where('productid', $productId)->get();
            return ResponseFormatter::success($picture, 'Success Delete Picture');
        }
        // catch(Exception $e) catch any exception
        catch (ModelNotFoundException $e) {
            return ResponseFormatter::error(null, 'Id not found', 404);
        }
    }

    private function _removePictureStorage($filepath)
    {
        // http://127.0.0.1:8000/storage/assets/product/
        $directory = "/storage/assets/product/";
        $url = url('') . $directory;
        $filename = str_replace($url, '', $filepath);

        if (file_exists(public_path($directory . $filename))) {
            unlink(storage_path('app/public/assets/product/' . $filename));
        }
    }
}
