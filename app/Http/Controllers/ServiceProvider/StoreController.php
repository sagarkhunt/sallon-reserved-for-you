<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\StoreGallery;
use App\Models\StoreProfile;
use App\Models\StoreTiming;
use Illuminate\Http\Request;
use Auth;
use File;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store_id = session('store_id');
        if (empty($store_id)) {
            $store_id = StoreProfile::where('user_id', Auth::user()->id)->value('id');
        }

        $data = StoreProfile::where('id', $store_id)->first();
        $storeTiming = StoreTiming::where('store_id', $store_id)->get();
        $storeGallery = StoreGallery::where('store_id', $store_id)->orderBy('id','DESC')->get();

        return view('ServiceProvider.Store.index', compact('data', 'storeTiming', 'storeGallery'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'store_name' => 'required',
            'store_address' => 'required',
            'zipcode' => 'required',
            'store_district' => 'required'
        ]);

        $store_id = session('store_id');
        if (empty($store_id)) {
            $store_id = StoreProfile::where('user_id', Auth::user()->id)->value('id');
        }

        $data = $request->all();
        $day = $request['day'];
        $start_time = $request['start_time'];
        $end_time = $request['end_time'];
        $data = $request->except('_token', '_method', 'day', 'start_time', 'end_time', 'is_holiday');

        $update = StoreProfile::where('id', $store_id)->update($data);
        if ($update) {

            $i = 0;
            $deleteDay = StoreTiming::where('store_id', $store_id)->delete();

            foreach ($day as $item) {

                $dayData['store_id'] = $store_id;
                $dayData['day'] = $item;
                if ($start_time[$i] == '' && $end_time[$i] == '') {
                    $dayData['is_off'] = 'on';
                }
                $dayData['start_time'] = $start_time[$i];
                $dayData['end_time'] = $end_time[$i];

                $dayStore = new StoreTiming();
                $dayStore->fill($dayData);
                $dayStore->save();
                $i++;
            }

            return redirect('service-provider/online-store');
        }
    }

    public function changeProfile(Request $request)
    {

        $store_id = session('store_id');
        if (empty($store_id)) {
            $store_id = StoreProfile::where('user_id', Auth::user()->id)->value('id');
        }
        if ($request->file('store_profile')) {

            $oldimage = StoreProfile::where('id', $store_id)->value('store_profile');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/store/' . $oldimage);
            }

            $file = $request->file('store_profile');
            $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/'), $filename);
            $data['store_profile'] = $filename;
        }

        $update = StoreProfile::where('id', $store_id)->update($data);

        if ($update) {
            return redirect('service-provider/online-store');
        }
    }

    public function changeBannerProfile(Request $request)
    {

        $store_id = session('store_id');
        if (empty($store_id)) {
            $store_id = StoreProfile::where('user_id', Auth::user()->id)->value('id');
        }
        if ($request->file('store_banner')) {

            $oldimage = StoreProfile::where('id', $store_id)->value('store_banner');

            if (!empty($oldimage)) {

                File::delete('storage/app/public/store/banner/' . $oldimage);
            }

            $file = $request->file('store_banner');
            $filename = 'Store-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/store/banner/'), $filename);
            $data['store_banner'] = $filename;
        }

        $update = StoreProfile::where('id', $store_id)->update($data);

        if ($update) {
            return redirect('service-provider/online-store');
        }
    }

    public function changeBannerGallery(Request $request)
    {
        $gallery = $request['store_gallery'];

        $store_id = session('store_id');
        if (empty($store_id)) {
            $store_id = StoreProfile::where('user_id', Auth::user()->id)->value('id');
        }

        if (!empty($gallery)) {
            foreach ($gallery as $item) {
                if (!empty($item)) {
                    $extension = $item->getClientOriginalExtension();

                    $destinationpath = storage_path('app/public/store/gallery/');

                    $filename = 'Store-' . uniqid() . '-' . rand(1, 9999) . '.' . $extension;

                    $item->move($destinationpath, $filename);

                    $barImage['file'] = $filename;
                    $barImage['file_type'] = 'image';
                    $barImage['store_id'] = $store_id;

                    $product_img = new StoreGallery();
                    $product_img->fill($barImage);
                    $product_img->save();
                }

            }
            return redirect('service-provider/online-store');
        }

    }

    public function removeImageGallery($id){

        $oldimage = StoreGallery::where('id', $id)->value('file');

        if (!empty($oldimage)) {

            File::delete('storage/app/public/store/gallery/' . $oldimage);
        }

        $delete = StoreGallery::where('id', $id)->delete();

        if($delete){
            return redirect('service-provider/online-store');
        }
    }
}
