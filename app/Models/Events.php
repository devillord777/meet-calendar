<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable   = ['name','description','date','location','type'];
    public static function get_all_events($all_month){
        
        $current_month = date('m');
        $all_events = [];
        for($i=0; $i<6; $i++){
            $month = $current_month + $i;
            
            $start = date(date("Y-m-d", strtotime(date('Y-'.$month.'-01'))).' 00:00:00');
            $end = date(date("Y-m-t", strtotime(date('Y-'.$month.'-d'))).' 23:59:59');
            $events = Events::where('date','>=', $start)->where('date','<=', $end)->get();
            $all_events[$i]['start'] = $start;
            $all_events[$i]['end'] = $end;
            $all_events[$i]['events'] = $events;
        }
        return $all_events;

    }

    public static function create_event($data){
        try {

            Events::create(['name' => $data['name'],'description' => $data['description'],'date' => $data['date'],'location' => $data['location'],'type' => $data['type']]);
          
          } catch (\Exception $e) {
          
              return $e->getMessage();
          }
    }

    public static function edit_event($data){
        try {
            Events::where('id', $data['id'])->update(['name' => $data['name'],'description' => $data['description'],'date' => $data['date'],'location' => $data['location'],'type' => $data['type']]);
          
          } catch (\Exception $e) {
          
              return $e->getMessage();
          }
    }
}
