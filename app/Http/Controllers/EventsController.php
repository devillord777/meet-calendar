<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Events;
class EventsController extends Controller
{
   

    public static function calendar(){
        $current_month = date('m');
        $all_month = [];
        for($i=0; $i<6; $i++){
            $month = $current_month + $i;
            $all_month[$i]['start'] = date(date("Y-m-d", strtotime(date('Y-'.$month.'-01'))).' 00:00:00');
            $all_month[$i]['end'] = date(date("Y-m-t", strtotime(date('Y-'.$month.'-d'))).' 23:59:59');
        }
        $events = Events::get_all_events($all_month);
        
        return view('calendar', compact('events'));
    }

    public static function save_event(Request $request){
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'date' => ['required', 'date'],
            'location' => ['required'],
            'type' => ['required'],
        ]);
        
        $result = Events::create_event($request->all());
        if(isset($result)){
            return redirect()->route('calendar')->with('error', 'Error - '. $result);
        }else{
            return redirect()->route('calendar')->with('success', 'Event saved successfuly');
        }
    }

    public static function edit_event(Request $request){
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'date' => ['required', 'date'],
            'location' => ['required'],
            'type' => ['required'],
        ]);
        
        $result = Events::edit_event($request->all());
        if(isset($result)){
            return redirect()->route('calendar')->with('error', 'Error - '. $result);
        }else{
            return redirect()->route('calendar')->with('success', 'Event updated successfuly');
        }
    }
}
