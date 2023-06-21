<?php

namespace App\Http\Controllers\ServiceProvider;

use App\Http\Controllers\Controller;
use App\Models\ServiceAppoinment;
use App\Models\StoreProfile;
use App\Models\Withdrwal;
use Illuminate\Http\Request;
use Auth;

class WalletController extends Controller
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
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->pluck('id')->all();
        } else {
            $getStore = StoreProfile::where('user_id', Auth::user()->id)->where('id', $store_id)->pluck('id')->all();
        }

        $date = \Carbon\Carbon::now()->format('Y-m-d');
        $month = \Carbon\Carbon::now()->format('m');
        $year = \Carbon\Carbon::now()->format('Y');
        
        $netEarning = ServiceAppoinment::whereIn('store_id', $getStore)->where('status', 'completed')->where('appointment_type','system')->sum('price');
        
        $refundableBalance = ServiceAppoinment::whereIn('store_id', $getStore)->where('status', 'cancel')->sum('price');

        $withdrwalBalance = Withdrwal::whereIn('store_id', $getStore)->where('status', 'complete')->sum('amount');

        $availableBalance = $netEarning - $withdrwalBalance;


        $todayEarning = ServiceAppoinment::whereIn('store_id', $getStore)->whereDate('appo_date', $date)->where('status', 'completed')->sum('price');

        $allTransaction = ServiceAppoinment::whereIn('store_id', $getStore)->orderBy('id', 'DESC')->get();


        /**
         * Day Chart Data
         */
        $appointmentDay = ServiceAppoinment::whereIn('store_id', $getStore)->where('status', 'completed')->where('appointment_type','system')->whereDate('appo_date', $date)->get();
        $dayas = [];
        foreach ($appointmentDay as $row) {
            $row->time = \Carbon\Carbon::parse($row->appo_time)->format('H');
            $dayas[$row->time][] = $row;
        }
        ksort($dayas);
        $dayData = [];
        for($i = 1;$i<=24;$i++){
            if(array_key_exists(date('H', mktime($i, 0, 0, 0, 0)),$dayas)){
                $dayData[] = array('time'=>$i,'count'=>
                    array_sum(array_map(function ($item) {
                        return $item['price'];
                    }, $dayas[date('H', mktime($i, 0, 0, 0, 0))]))
                ,'month'=>$i);
            } else {
                $dayData[] = array('time'=>$i,'count'=>0,'month'=>$i );
            }
        }


        /**
         * Month Chart Data
         */
        $appointmentMonth = ServiceAppoinment::whereIn('store_id', $getStore)->where('status', 'completed')->where('appointment_type','system')->whereMonth('appo_date', '=', $month)->get();
        $monthas = [];
        foreach ($appointmentMonth as $row) {
            $row->days = \Carbon\Carbon::parse($row->appo_date)->format('d');
            $monthas[$row->days][] = $row;
        }
        ksort($monthas);
        $monthData = [];
        for($i = 1;$i<=31;$i++){
            if(array_key_exists(date('d', mktime(0, 0, 0, 0, $i)),$monthas)){
                $monthData[] = array('time'=>$i,'count'=>
                    array_sum(array_map(function ($item) {
                        return $item['price'];
                    }, $monthas[date('d', mktime(0, 0, 0, 0, $i))]))
                ,'month'=>$i.'-'.$month);
            } else {
                $monthData[] = array('time'=>$i,'count'=>0,'month'=>$i.'-'.$month );
            }
        }

        /**
         * Year Chart Data
         */
        $appointmentMonth = ServiceAppoinment::whereIn('store_id', $getStore)->where('status', 'completed')->where('appointment_type','system')->whereYear('appo_date', '=', $year)->get();
        $yearas = [];
        foreach ($appointmentMonth as $row) {
            $row->month = \Carbon\Carbon::parse($row->appo_date)->format('m');
            $yearas[$row->month][] = $row;
        }
        ksort($yearas);
        $yearData = [];

        for($i=1;$i<=12;$i++){
            if(array_key_exists(date('m', mktime(0, 0, 0, $i, 10)),$yearas)){
                $yearData[] = array('time'=>$i,'count'=>
                    array_sum(array_map(function ($item) {
                        return $item['price'];
                    }, $yearas[date('m', mktime(0, 0, 0, $i, 10))]))
                    ,'month'=>date('F', mktime(0, 0, 0, $i, 10)) );
            } else {
                $yearData[] = array('time'=>$i,'count'=>0,'month'=>date('F', mktime(0, 0, 0, $i, 10)) );
            }
        }


        return view('ServiceProvider.Wallet.index', compact('netEarning', 'refundableBalance', 'availableBalance', 'withdrwalBalance', 'todayEarning', 'allTransaction', 'monthData', 'yearData', 'dayData'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
