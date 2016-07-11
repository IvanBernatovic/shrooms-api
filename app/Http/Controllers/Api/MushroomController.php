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
        'name' => 'required',
        'capShape' => 'required|in:b,c,f,x,k,s',
        'capSurface' => 'required|in:f,g,y,s',
        'capColor' => 'required|in:n,b,c,g,r,p,u,e,w,y',
        'bruises' => 'required|in:t,f',
        'odor' => 'required|in:a,l,c,y,f,m,n,p,s',
        'gillAttachment' => 'required|in:a,d,f,n',
        'gillSpacing' => 'required|in:c,w,d',
        'gillSize' => 'required|in:b,n',
        'gillColor' => 'required|in:k,n,b,h,g,r,o,p,u,e,w,y',
        'stalkShape' => 'required|in:e,t',
        'stalkRoot' => 'required|in:b,c,u,e,z,r,m',
        'stalkSurfaceAboveRing' => 'required|in:f,y,k,s',
        'stalkSurfaceBelowRing' => 'required|in:f,y,k,s',
        'stalkColorAboveRing' => 'required|in:n,b,c,g,o,p,e,w,y',
        'stalkColorBelowRing' => 'required|in:n,b,c,g,o,p,e,w,y',
        'veilType' => 'required|in:p,u',
        'veilColor' => 'required|in:n,o,w,y',
        'ringNumber' => 'required|in:n,o,t',
        'ringType' => 'required|in:c,e,f,l,n,p,s,z',
        'sporePrintColor' => 'required|in:k,n,b,h,r,o,u,w,y',
        'population' => 'required|in:a,c,n,s,v,y',
        'habitat' => 'required|in:g,l,m,p,u,w,d',
    ];

    public function index()
    {
        return Mushroom::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules);

        if ($validator->fails()) {
            throw new StoreResourceFailedException('Invalid mushroom data.', $validator->errors());
        }
        
        $data = $request->only($this->validInputFields);

        $azureResponse = $this->sendRequestToAzure($data);
        $responseBody = json_decode($azureResponse->getBody(), true);

        $data['name'] = $request->get('name');
        $data['result'] = $this->getResultFromResponse($responseBody);
        $data['probability'] = $this->getProbabiltyFromResponse($responseBody);

        return Mushroom::create($data);
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

    private function getResultFromResponse($responseBody)
    {
        return $responseBody['Results']['output1']['value']['Values'][0][23];
    }


    private function getProbabiltyFromResponse($responseBody)
    {
        return $responseBody['Results']['output1']['value']['Values'][0][24];
    }
}
