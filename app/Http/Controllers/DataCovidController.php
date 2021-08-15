<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Auth;
use App\Models\UserData;
use App\Models\User;
use App\Models\ClaimCovid;
use App\Models\ClaimCovidHistory;

class DataCovidController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $complete = UserData::where('id_user',$user->id)->get()->first();
        $role = User::where('id',$user->id)->get()->first();
        $data = UserData::join('claim_covid','claim_covid.id_user','=','user_data.id_user')->get();
        $total = UserData::join('claim_covid','claim_covid.id_user','=','user_data.id_user')->where('claim_covid.sembuh','belum')->count();
        $sembuh = UserData::join('claim_covid','claim_covid.id_user','=','user_data.id_user')->where('claim_covid.sembuh','sudah')->count();
        $pernahcovid = ClaimCovidHistory::all()->count();
        
        $dtn = new Collection();
        for($i=0;$i<count($data);$i++){
            if($data[$i]['tanggal_confirmed'] != null){
                $date1 = date_create($data[$i]['tanggal_confirmed']);
                $date2 = date_create(now());
                $diff = date_diff($date1,$date2)->format("%R%a days");
                $dtn->push($diff);
            }
            else{
                $dtn->push("No Data");
            }
        }
        // return $dtn;
        // return view('tabel',compact('user','complete','data'));
        return view('datacovid',compact('user','complete','data','total','pernahcovid','sembuh', 'dtn', 'role'));

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
        //
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

    public function downloadcovid($id)
    {
        $complete = ClaimCovid::where('id_user',$id)->get()->first();
        $file_name = $complete->gambar_hasiltest;
        $file_path = public_path('folder_covid/'.$file_name);
        return response()->download($file_path);
        
        return $complete;
    }

    public function downloadswabpcr($id)
    {
        $complete = ClaimCovid::where('id_user',$id)->get()->first();
        $file_name = $complete->gambar_pcr;
        $file_path = public_path('folder_covid/'.$file_name);
        return response()->download($file_path);
        
        return $complete;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
}
