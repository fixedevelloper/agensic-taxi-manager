<?php


namespace App\Service;


use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class IpLocationService
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
            'base_uri' => "https://ipapi.co/",
        ]);

    }
    function getOneIPDevice($ip)
    {
        $ip="129.0.76.187";
        $endpoint = "".$ip."/json";
        $res = $this->client->get($endpoint,
            []);
        return json_decode($res->getBody(), true);
    }
}
