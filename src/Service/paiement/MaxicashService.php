<?php


namespace App\Service\paiement;


use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MaxicashService
{
    private $params;
    /**
     * @var Client
     */
    private $client;
    private $tokencinet;
    private $logger;

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
            'base_uri' => $params->get('MAXI_URL'),
        ]);
    }

    function postpaiement($data)
    {
        $postdata = [
            "PayType" => "MaxiCash",
            "MerchantID" => $this->params->get('MAXI_URL'),
            "MerchantPassword" => $this->params->get('MAXI_URL'),
            "Amount" => $data['amount'], //please note that the amounts must be sent in Cents
            "Currency" => "maxiDollar", //values can be “maxiDollar” or “maxiRand”
            "Telephone" => $data['phone'],
            "Language" => "fr", //en or fr
            "Reference" => $data['reference'],
            "SuccessURL" => $data['successurl'],
            "FailureURL" => $data['failureurl'],
            "CancelURL" => $data['cancelurl'],
            "NotifyURL" => $data['notifyurl']
        ];
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($postdata)
        ];
        $endpoint = "";
        $response = $this->client->post($endpoint, $options);
        $body = $response->getBody();
        return json_decode($body, true);
    }
}
