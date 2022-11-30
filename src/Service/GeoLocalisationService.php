<?php


namespace App\Service;


use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GeoLocalisationService
{
    private ParameterBagInterface $params;
    /**
     * @var Client
     */
    private Client $client;
    private LoggerInterface $logger;

    /**
     * EkolopayService constructor.
     * @param LoggerInterface $logger
     * @param ParameterBagInterface $params
     */
    public function __construct(LoggerInterface $logger, ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->logger = $logger;
        $this->client = new Client([
            'base_uri' => "https://www.googleapis.com/geolocation/v1/",
        ]);
    }
    function geoLocalisation($data){
        $endpoint = "geolocate?key=AIzaSyCLsG4-VDQDTb9TYM5MdjIyLRv2dTR60NI";
        $wifiAccessPoints=[

        ];
        $cellTowers=[
            'cellId'=>$data['cellId'],
            'locationAreaCode'=>$data['lac'],
            'mobileCountryCode'=>$data['homeMobileCountryCode'],
            'mobileNetworkCode'=>$data['homeMobileNetworkCode']
        ];
        $form = [
            'homeMobileCountryCode' => $data['homeMobileCountryCode'],
            'homeMobileNetworkCode' => $data['homeMobileNetworkCode'],
            'radioType' => $data['radioType'],
            'considerIp'=>true,
            'carrier' => $data['carrier'],
            'cellTowers' => [$cellTowers],
            'wifiAccessPoints' => $wifiAccessPoints,
        ];

        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'key' => "AIzaSyCLsG4-VDQDTb9TYM5MdjIyLRv2dTR60NI",
            ],
            'json' => $form,
        ];
        $res = $this->client->post($endpoint, $options);
        return json_decode($res->getBody(), true);
    }
}
