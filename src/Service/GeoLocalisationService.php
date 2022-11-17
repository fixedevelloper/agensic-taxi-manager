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
        $form = [
            'homeMobileCountryCode' => $data['homeMobileCountryCode'],
            'homeMobileNetworkCode' => $data['homeMobileNetworkCode'],
            'radioType' => "gms",
            'considerIp'=>true,
            'carrier' => $data['operateur'],
            'cellTowers' => [],
            'wifiAccessPoints' => [],
        ];
        $wifiAccessPoints=[

        ];
        $cellTowers=[

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
