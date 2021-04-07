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
                'state' => 'PR',
                'startDate' => '2020-05-05',
                'endDate' => '2020-05-10'
            ]
        );
    }

    public function list($state, $startDate, $endDate) { 

        $cases = $this->getTop10Cities($this->getCases($state, $endDate));

        $data = array(
            "state" => $state,
            "startDate" => new \DateTime($startDate),
            "endDate" => new \DateTime($endDate),
            "cases" => $cases,
        );

        return view('cases/list', $data);
    }

    public function listAPI($state, $startDate, $endDate) { 

        $cases = $this->getTop10Cities($this->getCases($state, $endDate));

        $this->create($cases);

        return response(json_encode($cases, JSON_PRETTY_PRINT), 200);
    }


    public function getCases($state, $date) {
        
        $state = strtoupper($state);

        $url = self::API_URL . "date=$date&state=$state";
        $token = "cd06accc7cba9e0b48b4d3106f3ea4359f593725";

        $guzzle = new \GuzzleHttp\Client();
        
        return $guzzle->get($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Token $token",
            ]
        ]);
    }

    public function getTop10Cities($response) {
        $cases = json_decode($response->getBody()->getContents());
        $cases = json_decode(json_encode($cases->results), true);

        usort($cases, function ($a, $b) { 
            return $b['confirmed_per_100k_inhabitants'] - $a['confirmed_per_100k_inhabitants'];
        });

        return array_slice($cases, 0, 10);
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
}