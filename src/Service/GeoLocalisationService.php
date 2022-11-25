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
        $endpoint = "geolocate?";
        $wifiAccessPoints=[

        ];
        $cellTowers=[
            'cellId'=>$data['cellId'],
            'newRadioCellId'=>$data['newRadioCellId'],
            'locationAreaCode'=>$data[''],
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
                'Accept' => 'application/x-www-form-urlencoded',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'key' => $this->params->get('GOOGLE_API_KEY'),
            ],
            'form_params' => $form,
        ];
        $res = $this->client->post($endpoint, $options);
        return json_decode($res->getBody(), true);
    }
}
