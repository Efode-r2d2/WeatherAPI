<?php

namespace App\Http\Controllers\Api\V1;

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
    protected $api_key = '533215067e4645bfb6d09a0c40f3f91a';
    /**
     *  fetch weather data based on user location from weather bit,
     *  save the request data and
     *  return required weather info back to the user
     */
    public function index(WeatherRequest $request){
        $response = $this->fetch($request);
        $this->save($request,$response);
        return response(["Status"=>true,
            "Data"=>["temp"=>$response->data[0]->temp,
            "desc"=>$response->data[0]->weather->description]]);
    }
    /**
     * fetch weather data in a form of json
     */
    private function fetch(WeatherRequest $request){
        $response = Http::get('https://api.weatherbit.io/v2.0/current?lat='.
            $request->input('lat').'&lon='.
            $request->input('lon').'&key='.
            $this->api_key.'&include=daily');
        return json_decode($response);
    }
    /**
     * save request data to database
     */
    private function save(WeatherRequest $request, $response){
        Weather::create([
            'lat'=>$request->input('lat'),
            'lon'=>$request->input('lon'),
            'temp'=>$response->data[0]->temp,
            'desc'=>$response->data[0]->temp
        ]);
    }
}
