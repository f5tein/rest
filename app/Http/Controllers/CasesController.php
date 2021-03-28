<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    CONST API_URL = "https://api.brasil.io/v1/dataset/covid19/caso/data/?";

    public function index() {
        return redirect()->action(
            [CasesController::class, 'list'], [
                'state' => 'pr',
                'startDate' => '2020-05-05',
                'endDate' => '2020-05-10'
            ]
        );
    }

    public function list($state, $startDate, $endDate) { 

        $state = strtoupper($state);

        $url = self::API_URL . "date=$endDate&state=$state";
        $token = "cd06accc7cba9e0b48b4d3106f3ea4359f593725";

        $guzzle = new \GuzzleHttp\Client();
        $response = $guzzle->get($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Token $token",
            ]
        ]);

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $cases = json_decode($response->getBody()->getContents());
        $cases = json_decode(json_encode($cases->results), true);

        usort($cases, function ($a, $b) { 
            return $b['confirmed_per_100k_inhabitants'] - $a['confirmed_per_100k_inhabitants'];
        });

        $cases = array_slice($cases, 0, 10);
        
        $this->create($cases);

        $data = array(
            "state" => $state,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "cases" => $cases,
        );

        return view('cases/list', $data);

    }

    public function create($cases){
        
        foreach($cases as $key => $case) {
            $case = array(
                "id" => $key,
                "nomeCidade" => $case["city"],
                "percentualDeCasos" => $case["confirmed_per_100k_inhabitants"]
            );

            $url = "https://us-central1-lms-nuvem-mestra.cloudfunctions.net/testApi";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($case));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'MeuNome: Felipe Stein',
                'Content-Type:application/json',
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            // echo '<pre>$result:: '; print_r($result); echo '</pre>';
        }
    }

    public function listAPI($state, $startDate, $endDate) { 

        $state = strtoupper($state);

        $url = self::API_URL . "date=$endDate&state=$state";
        $token = "cd06accc7cba9e0b48b4d3106f3ea4359f593725";

        $guzzle = new \GuzzleHttp\Client();
        $response = $guzzle->get($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Token $token",
            ]
        ]);

        $cases = json_decode($response->getBody()->getContents());
        $cases = json_decode(json_encode($cases->results), true);
        $cases = array_slice($cases, 0, 10);

        usort($cases, function ($a, $b) { 
            return $b['confirmed_per_100k_inhabitants'] - $a['confirmed_per_100k_inhabitants'];
        });

        $this->create($cases);

        return response(json_encode($cases, JSON_PRETTY_PRINT), 200);
    }
}