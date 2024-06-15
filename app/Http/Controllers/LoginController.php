<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SessionLog;
use DB;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {      
        if ($request->user == "" || $request->expires == "")
        {
            $SessionLog = new SessionLog();
            $SessionLog->name           = "";
            $SessionLog->email          = "";
            $SessionLog->status         = "Invalid";
            $SessionLog->active_flag    = "N";
            $SessionLog->created_by     = "API";
            $SessionLog->created_at     = now();
            $SessionLog->updated_at     = now();
            $SessionLog->deleted_flag   = "N";
            $SessionLog->save();

            return response()->json([
                'error' => "Bad Request"],
                400
            );
        } 
        else 
        {
            $user       = $request->user; 
            $nama       = $user['name']; 
            $email      =  $user['email']; 
            $expires    = $request->expires;

            // $date = new Carbon;
            $today = Carbon::now();

            $isoDate = Carbon::parse($expires);
            // $isoDate = Carbon::createFromFormat('Y-md\TH:i', $expires);

            // "2024-0712T02:03:34.873Z"

            // if($today > $isoDate)
            if($isoDate->greaterThanOrEqualTo($today))
            {
                return response()->json([
                    'error' => "Expired Session"],
                    411
                );
            }

            
        }

        //jika tidak ada data maka simpan dan kembalikan sbg 400
        if (!isset($user['email']) || !isset($expires))
        {
            $SessionLog = new SessionLog();
            $SessionLog->name           = $user['name'];
            $SessionLog->email          = $user['email'];
            $SessionLog->status         = "Invalid";
            $SessionLog->active_flag    = "N";
            $SessionLog->created_by     = "API";
            $SessionLog->created_at     = now();
            $SessionLog->updated_at     = now();
            $SessionLog->deleted_flag   = "N";
            $SessionLog->save();

            return response()->json([
                'error' => "Invalid Request"],
                400
            );
        }
                
        // cari dulu apa ada datanya atau tidak
        $sessionlogexist = DB::table('session_logs')
                     ->select(DB::raw('id, email,status'))
                     ->where('status', '=', 'Valid')
                     ->where('email', '=', $email)
                    //  ->groupBy('status')
                     ->get();        

        

        if ($sessionlogexist->isEmpty()) {

            $SessionLog = new SessionLog();
            $SessionLog->name           = $user['name'];
            $SessionLog->email          = $user['email'];
            $SessionLog->status         = "Valid";
            $SessionLog->active_flag    = "Y";
            $SessionLog->created_by     = "API";
            $SessionLog->created_at     = now();
            $SessionLog->updated_at     = now();
            $SessionLog->deleted_flag   = "N";
            $SessionLog->save();

            // return response()->json("Sorry data kosong bro, jadi di add baru datanya");
            // Debugbar::info('Új sor');
            // DB::insert('insert into sellmode (barcode, count) VALUES (?, ?)', [$barcode, '1']);
            
            // return response()->json([
            //     'status' => "Log Created"],
            //     200
            // );

            $useraccess = DB::table('user_accesses')
            ->select(DB::raw('email,privilege'))
            ->where('deleted_flag', '=', 'N')
            ->where('email', '=', $email)
            ->get();    

            $moduleaccess = DB::table('module_accesses')
            ->select(DB::raw('module_name,access_right'))
            ->where('deleted_flag', '=', 'N')
            ->where('email', '=', $email)
            ->get();    

            $result_data = array(
                'email' =>  $emaildrdb, 
                'privilege' => $priviledgedrdb , 
                'module_access' => $moduleaccess, 
            );

            $result_array = array(
                'data' => $result_data, 
                'status' => "Log Inserted"
            );

            // echo json_encode($arr);

            //kasih privilegenya
            return response()->json($result_array,
                200
            );


        }
        else 
        {
            //update semua yg punya email sama
            DB::table('session_logs')
            ->where('email', $email )
            ->where('status', '=', 'Valid')
            ->where('deleted_flag', '=', 'N')
            ->update(['active_flag' => 'N','updated_at'=>now()]);

            //insert log terbaru
            $SessionLog = new SessionLog();
            $SessionLog->name           = $user['name'];
            $SessionLog->email          = $user['email'];
            $SessionLog->status         = "Valid";
            $SessionLog->active_flag    = "Y";
            $SessionLog->created_by     = "API";
            $SessionLog->created_at     = now();
            $SessionLog->updated_at     = now();
            $SessionLog->deleted_flag   = "N";
            $SessionLog->save();

            $useraccess = DB::table('user_accesses')
            ->select(DB::raw('email,privilege'))
            ->where('deleted_flag', '=', 'N')
            ->where('email', '=', $email)
            ->get();    

            $moduleaccess = DB::table('module_accesses')
            ->select(DB::raw('module_name,access_right'))
            ->where('deleted_flag', '=', 'N')
            ->where('email', '=', $email)
            ->get();    

            $emaildrdb       = $useraccess[0]->email;
            $priviledgedrdb       = $useraccess[0]->privilege;

            $result_data = array(
                'email' =>  $emaildrdb, 
                'privilege' => $priviledgedrdb , 
                'module_access' => $moduleaccess, 
            );

            $result_array = array(
                'data' => $result_data, 
                'status' => "Log Updated"
            );
 

            // kasih privilegenya
            return response()->json($result_array,
                200
            );

        }
        
    }

    public function store(Request $request)
    {
        // because
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
