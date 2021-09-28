<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\WeatherRequest;
use Illuminate\Support\Facades\Http;
use App\Models\Weather;

class WeatherController extends Controller
{
     /**
     *  API Key from weatherbit
     */ 
    protected $api_key = '6f8d9a6db9f4c2b5b1ab1375dab518bb';
    /**
     *  fetch weather data based on user location from weather bit,
     *  save the request data and
     *  return required weather info back to the user
     */
    public function index(WeatherRequest $request){
        $response = $this->fetch($request);
        $this->save($request,$response);
        return response(["Status"=>true,
            "Data"=>["temp"=>$response->main->temp,
                "desc"=>$response->weather[0]->description]
            ]);
    }
    /**
     * fetch weather data
     */
    private function fetch(WeatherRequest $request){
        $response = Http::get('api.openweathermap.org/data/2.5/weather?lat='.
            $request->input('lat').'&lon='.
            $request->input('lon').'&units='.
            $request->input('unit').'&appid='.
            $this->api_key);
        return json_decode($response);
    }
    /**
     * save request data to database
     */
    private function save(WeatherRequest $request, $response){
        Weather::create([
            'lat'=>$request->input('lat'),
            'lon'=>$request->input('lon'),
            'temp'=>$response->main->temp,
            'desc'=>$response->weather[0]->description
        ]);
    }
}
