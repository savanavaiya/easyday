<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Twilio\Rest\Client;

class HomeController extends Controller
{
    public function store(Request $request)
    {
        // return response()->json(['message','ok']);

        $validate = $request->validate([
            'country_code' => 'required',
            'phone_number' => 'required|numeric',
        ]);

        $c_code = $request->country_code;
        $phone_no = $request->phone_number;


        $userex = User::where('phone_number', $phone_no)->first();

        if ($userex == null) {
            $otp = rand(111111, 999999);


            //sms otp
            $receiverNumber = "+919723791093";
            $message = "This is testing from easy day";


            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message
            ]);



            $data = User::create([
                'country_code' => $c_code,
                'phone_number' => $phone_no,
                'otp' => $otp,
            ]);
        } else {

            $otp = rand(111111, 999999);


            //sms otp
            $receiverNumber = "+919723791093";
            $message = "This is testing from easy day";


            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number,
                'body' => $message
            ]);



            $userex->otp = $otp;
            $userex->status = '1';
            $userex->save();
        }


        // $token = $data->createToken('MyApp')->plainTextToken;

        return response()->json(['success' => 'true', 'message' => 'The Otp Sent', 'otp' => $otp], 200);
    }

    public function veriotp(Request $request)
    {
        $validate = $request->validate([
            'phone_number' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        $datas = User::where('phone_number', $request->phone_number)->where('otp', $request->otp)->first();

        if ($datas == null) {
            return response()->json(['success' => 'false', 'message' => 'Incorect Otp'], 404);
        } else {

            if ($datas->status == '0') {
                return response()->json(['success' => 'true', 'message' => 'Otp Verified Successfully'], 200);
            } else {

                $token = $datas->createToken('MyApp')->plainTextToken;
                // $token = $datas->token;

                return response()->json(['success' => 'true', 'message' => 'Otp Verified Successfully', 'token' => $token], 200);
            }
        }
    }

    public function creuser(Request $request)
    {
        $validate = $request->validate([
            'fullname' => 'required',
            'profession' => 'required',
            'image' => 'required',
            'phone_number' => 'required|numeric',
        ]);

        $datas = User::where('phone_number', $request->phone_number)->where('status', '0')->first();

        $datas->fullname = $request->fullname;
        $datas->profession = $request->profession;

        if ($request->file('image')) {
            $new = "IMG" . time() . ".jpg";

            $request->image->move(public_path('images'), $new);
        }
        // $datas->profile_image = $new;
        $datas->profile_image = public_path() . '\\images\\' . $new;

        $datas->status = '1';

        $token = $datas->createToken('MyApp')->plainTextToken;
        $datas->save();

        return response()->json(['success' => 'true', 'message' => 'Otp Verified Successfully', 'token' => $token], 200);
    }

    public function editpro(Request $request)
    {
        $validate = $request->validate([
            'fullname' => 'required',
            'profession' => 'required',
            'image' => 'required',
            'phone_number' => 'required|numeric',
        ]);

        $alexuser = User::where('phone_number', $request->phone_number)->where('status', '1')->first();

        $alexuser->fullname = $request->fullname;
        $alexuser->profession = $request->profession;

        if ($request->file('image')) {
            $new = "IMG" . time() . ".jpg";

            $request->image->move(public_path('images'), $new);
        }
        // $alexuser->profile_image = $new;
        $alexuser->profile_image = public_path() . '\\images\\' . $new;
        $alexuser->save();

        return response()->json(['success' => 'true', 'message' => 'Your Profile Edited Successfully'], 200);
    }

    public function logout()
    {
        $id = auth('sanctum')->user()->id;

        $data = User::find($id);

        $data->tokens()->delete();

        return response()->json(['success' => 'true', 'message' => 'Logout Successfully'], 200);
    }

    public function deleteuserac()
    {
        $id = auth('sanctum')->user()->id;

        $data = User::find($id);

        $data->tokens()->delete();
        $data->delete();

        return response()->json(['success' => 'true', 'message' => 'Delete User Account Successfully'], 200);
    }
}
