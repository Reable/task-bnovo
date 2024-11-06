<?php 

namespace App\Controllers;

use Core\DB;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

class GuestController extends Controller
{

    public function get($data)
    {
        if(empty($_SERVER["HTTP_AUTHORIZATION"]) || $_SERVER["HTTP_AUTHORIZATION"] !== $_ENV["API_KEY"]){
            echo response()->error("Invalid API key", 403);
            return;
        }

        $db = DB::getInstance();

        $arr = [];
        foreach($data as $k => $v){
            if($k == "id" || $k == "name" || $k == "surname" || $k == "email" || $k == "phone"){
                $arr[$k] = $v;
            }
        }

        if(count($arr) == 0){
            $data = $db::table("guests")->get();
            return response()->json(["guests" => $data]);
        }

        $data = $db::table("guests")->where($arr)->get();

        return response()->json(["guests" => $data]);

    }

    public function create($data)
    {
        if(empty($_SERVER["HTTP_AUTHORIZATION"]) || $_SERVER["HTTP_AUTHORIZATION"] !== $_ENV["API_KEY"]){
            echo response()->error("Invalid API key", 403);
            return;
        }

        $db = DB::getInstance();

        $validate = \Validator::validate($data, [
            "name" => "required",
            "surname" => "required",
            "email" => "required",
            "phone" => "required",
        ]);

        if(!$validate["success"]){
            return response()->error([
                "message" => "Key {$validate['key']} is required",
            ], 409);
        }

        $quest = $db::table("guests")->where(["email" => $data["email"], "phone" => $data["phone"]], "OR")->get();
        if(count($quest)){
            return response()->error([
                "message" => "Email or phone already exists"
            ], 409);
        }

        $countryCode = $this->getCountry($data["phone"]);
        if(!$countryCode || is_null($countryCode["regionCode"])){
            return response()->error([
                "message" => "Check the phone number you entered"
            ], 409);
        }

        $db::table("guests")->insert([
            "name" => $data["name"],
            "surname" => $data["surname"],
            "email" => $data["email"],
            "phone" => $data["phone"],
            "country" => $countryCode["regionCode"],
        ]);

        return response()->json([
            "message" => "Guest created",
            "success" => true
        ], 201);

    }

    public function update($data)
    {
        if(empty($_SERVER["HTTP_AUTHORIZATION"]) || $_SERVER["HTTP_AUTHORIZATION"] !== $_ENV["API_KEY"]){
            echo response()->error("Invalid API key", 403);
            return;
        }

        $validate = \Validator::validate($data, [
            "id" => "required",
        ]);

        if(!$validate["success"]){
            return response()->error([
                "message" => "Key {$validate['key']} is required",
            ], 409);
        }

        if(gettype($data["id"]) != "integer"){
            return response()->error([
                "message" => "Key id is not numeric",
            ], 409);
        }

        $db = DB::getInstance();

        $quest = $db::table("guests")->where(["id" => $data["id"]])->get();

        if(count($quest) < 1){
            return response()->error([
                "message" => "Not found guest"
            ], 404);
        }

        $arr = [];
        foreach($data as $k => $v){
            if($k == "name" || $k == "surname" || $k == "email" || $k == "phone"){
                if($k == "phone"){

                    $countryCode = null;
                    if(isset($data["phone"])){
                        $countryCode = $this->getCountry($data["phone"]);
                        if(!$countryCode || is_null($countryCode["regionCode"])){
                            return response()->error([
                                "message" => "Check the phone number you entered"
                            ], 409);
                        }
                    }

                    $arr["country"] = $countryCode["regionCode"];
                }

                $arr[$k] = $v;
            }
        }

        if(!count($arr)){
            return response()->error([
                "message" => "You have not entered the data for the update"
            ], 409);
        }

        $db::table("guests")->update($arr, $data["id"]);

        return response()->json([
            "message" => "Guest updated",
            "values" => $arr,
            "success" => true
        ], 200);
    }

    public function delete($data)
    {
        if(empty($_SERVER["HTTP_AUTHORIZATION"]) || $_SERVER["HTTP_AUTHORIZATION"] !== $_ENV["API_KEY"]){
            echo response()->error("Invalid API key", 403);
            return;
        }

        $validate = \Validator::validate($data, [
            "id" => "required",
        ]);

        if(!$validate["success"]){
            return response()->error([
                "message" => "Key {$validate['key']} is required",
            ], 409);
        }

        $db = DB::getInstance();

        $quest = $db::table("guests")->where(["id" => $data["id"]])->get();

        if(count($quest) < 1){
            return response()->error([
                "message" => "Not found guest"
            ], 404);
        }

        $db::table("guests")->where(["id"=>$data["id"]])->delete();

        return response()->json([
            "message" => "Guest updated",
            "id" => $data["id"],
            "success" => true
        ], 200);

    }

    private function getCountry($phone)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try{
            $numberProto = $phoneUtil->parse($phone, null);
            $countryCode = $numberProto->getCountryCode();
            $regionCode = $phoneUtil->getRegionCodeForNumber($numberProto);
            return [
                "countryCode" => $countryCode,
                "regionCode" => $regionCode
            ];
        } catch(NumberParseException $e){
            return false;
        }
    }
}