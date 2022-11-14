<?php


namespace App\Service;


use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GpsService
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
            'base_uri' => $params->get('GPS_URL'),
        ]);
    }

    function createDevise($data)
    {
        $endpoint = "/396slbHG7506Rlglhglbfj7/create";
        $reference="";
        $allowed_characters = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        for ($i = 1; $i <= 10; ++$i) {
            $reference .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }
        $form = [
            'imei' => $data['imei'],
            'license' => $data['license'],
            'admin_contact1' => $data['admin_contact1'],
            'tracker_phone_number' => $data['tracker_phone_number'],
            'operator' => $data['operator'],
        ];
        $options = [
            'headers' => [
                'Accept' => 'application/x-www-form-urlencoded',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Api-token' => "3853925hxnsvdebdyh36s",
                'Request-Id' => $reference
            ],
            'form_params' => $form,
        ];
        $res = $this->client->post($endpoint, $options);
        return json_decode($res->getBody(), true);
    }
    function getOneDevise($data)
    {
        $endpoint = "/396slbHG7506Rlglhglbfj7/deviceinfo";
        $reference="";
        $allowed_characters = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
        for ($i = 1; $i <= 15; ++$i) {
            $reference .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }
        $form = [
            'device_imei' => $data['device_imei'],
        ];
        $options = [
            'headers' => [
                'Accept' => 'application/x-www-form-urlencoded',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Api-token' => $this->params->get('GPS_API'),
                'Request-Id' => $reference
            ],
            'form_params' => json_encode($form),
        ];
        $res = $this->client->post($endpoint, $options);
        return json_decode($res->getBody(), true);
    }
}
