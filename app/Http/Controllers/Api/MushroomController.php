<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreMushroomRequest;
use App\Models\Mushroom;
use Dingo\Api\Exception\StoreResourceFailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MushroomController extends Controller
{
    protected $apiKey = 'Yl4cUQUWRQfUgDfPP2ehUViWopaO9asYx+ed0sLBIxJvXKNQoaZimRs/w2lTe0o46ClCLsCX63znF8GOZ1K2Vg==';
    protected $azureURL = 'https://ussouthcentral.services.azureml.net/workspaces/27bdb4ef5c554d2cbbb765f31f8be5cb/services/d3f52ae433df4de291816472612d31b0/execute?api-version=2.0&details=true';

    protected $validInputFields = ['capShape', 'capSurface', 'capColor',
        'bruises','odor','gillAttachment','gillSpacing','gillSize','gillColor',
        'stalkShape','stalkRoot','stalkSurfaceAboveRing','stalkSurfaceBelowRing',
        'stalkColorAboveRing','stalkColorBelowRing','veilType','veilColor','ringNumber',
        'ringType','sporePrintColor','population','habitat'];
    
    protected $validationRules = [
        'capShape' => 'required|in:b,c,f,x,k,s',
        'capSurface' => 'required|in:f,g,y,s',
        'capColor' => 'required',
        'bruises' => 'required',
        'odor' => 'required',
        'gillAttachment' => 'required',
        'gillSpacing' => 'required',
        'gillSize' => 'required',
        'gillColor' => 'required',
        'stalkShape' => 'required',
        'stalkRoot' => 'required',
        'stalkSurfaceAboveRing' => 'required',
        'stalkSurfaceBelowRing' => 'required',
        'stalkColorAboveRing' => 'required',
        'stalkColorBelowRing' => 'required',
        'veilType' => 'required',
        'veilColor' => 'required',
        'ringNumber' => 'required',
        'ringType' => 'required',
        'sporePrintColor' => 'required',
        'population' => 'required',
        'habitat' => 'required',
    ];

    public function index()
    {
        return 'all mushrooms';
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Invalid mushroom data.', $validator->errors());
        }


        $azureResponse = $this->sendRequestToAzure($request->only($this->validInputFields));
        
        dd($azureResponse);
    }

    public function sendRequestToAzure($data)
    {
        $inputNames = array_keys($data);
        array_push($inputNames, 'result');

        $inputValues = array_values($data);
        array_push($inputValues, null);

        $client = new Client();

        $response = $client->request('POST', $this->azureURL, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'Inputs' => [
                    'input1' => [
                        'ColumnNames' => $inputNames,
                        'Values' => [
                            $inputValues
                        ]
                    ]
                ],
                'GlobalParameters' => null
            ]
        ]);

        return $response;
    }
}
