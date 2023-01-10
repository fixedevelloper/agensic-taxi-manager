<?php


namespace App\Controller\Api;


use App\Entity\Customer;
use App\Entity\Driver;
use App\Entity\Notification;
use App\Entity\Ride;
use App\Entity\Shipping;
use App\Repository\AddressShippingRepository;
use App\Repository\AffectationRideRepository;
use App\Repository\CarRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\CustomerRepository;
use App\Repository\DriverRepository;
use App\Repository\NotificationRepository;
use App\Repository\PlaceRepository;
use App\Repository\ProprietaireRepository;
use App\Repository\RideRepository;
use App\Repository\ShippingRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\paiement\CinetPayService;
use App\Service\paiement\MaxicashService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ActionApiController extends AbstractFOSRestController
{
    private UserPasswordHasherInterface $passwordEncoder;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;
    private UserRepository $userRepository;
    private CustomerRepository $customerRepository;
    private CarRepository $carRepository;
    private DriverRepository $driverRepository;
    private RideRepository $rideRepository;
    private ProprietaireRepository $propretaireRepository;
    private AffectationRideRepository $affactationRepository;
    private ConfigurationRepository $configurationRepository;
    private PlaceRepository $placeRepository;
    private AddressShippingRepository $addressRepository;
    private EntityManagerInterface $doctrine;
    private ShippingRepository $shippingRepository;
    private WalletRepository $walletRepository;
    private NotificationRepository $notificationRepository;
    private $cinetpayService;
    private $maxicashService;

    /**
     * @param ShippingRepository $shippingRepository
     * @param PlaceRepository $placeRepository
     * @param AddressShippingRepository $addressRepository
     * @param ConfigurationRepository $configurationRepository
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param AffectationRideRepository $affectationRideRepository
     * @param ProprietaireRepository $propretaireRepository
     * @param CarRepository $carRepository
     * @param DriverRepository $driverRepository
     * @param RideRepository $rideRepository
     * @param CustomerRepository $customerRepository
     * @param LoggerInterface $logger
     * @param UserPasswordHasherInterface $passwordEncoder
     */
    public function __construct(ShippingRepository $shippingRepository,NotificationRepository $notificationRepository,
                                PlaceRepository $placeRepository,AddressShippingRepository $addressRepository,ConfigurationRepository $configurationRepository,EntityManagerInterface $entityManager,UserRepository $userRepository,AffectationRideRepository $affectationRideRepository,
                                ProprietaireRepository $propretaireRepository,CarRepository $carRepository,
                                DriverRepository $driverRepository,RideRepository $rideRepository,CustomerRepository $customerRepository,
                                LoggerInterface $logger,UserPasswordHasherInterface $passwordEncoder,MaxicashService $maxicashService,
                                CinetPayService $cinetPayService,WalletRepository $walletRepository)
    {
        $this->logger = $logger;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository=$userRepository;
        $this->customerRepository=$customerRepository;
        $this->driverRepository=$driverRepository;
        $this->carRepository=$carRepository;
        $this->propretaireRepository=$propretaireRepository;
        $this->rideRepository=$rideRepository;
        $this->affactationRepository=$affectationRideRepository;
        $this->configurationRepository=$configurationRepository;
        $this->addressRepository=$addressRepository;
        $this->placeRepository=$placeRepository;
        $this->shippingRepository=$shippingRepository;
        $this->walletRepository=$walletRepository;
        $this->notificationRepository=$notificationRepository;
        $this->cinetpayService=$cinetPayService;
        $this->maxicashService=$maxicashService;
        $this->doctrine=$entityManager;
    }
    /**
     * @Rest\Post("/v1/rides/actions", name="api_ride_action")
     * @param Request $request
     * @return Response
     */
    public function rideAction(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $ride=$this->rideRepository->find($data['ride']);
        $action=$data['action'];
        if ($action=="VALIDATE_DRIVER"){
            $driver=$this->driverRepository->find($data['driver']);
            $affectation=$this->affactationRepository->findOneBy(['driver'=>$driver,'isEnable'=>true]);
            $ride->setDriver($driver);
            if (!is_null($affectation)){
                $ride->setCar($affectation->getCAr());
            }
            $ride->setStatus(Ride::CONFIRMED);
            $message="Le chauffeur ".$driver->getCompte()->getName()." a accepter votre course";
            $title="Prise de la course";
            $this->sendNotificationCustomer($ride->getCustomer()->getId(),$message,$title,"");

        }
        if ($action=="STARTING_DRIVER"){
            $ride->setStatus(Ride::STARTING);
            $message="Votre course a debute profitez du confort";
            $title="Course debute";
            $this->sendNotificationCustomer($ride->getCustomer()->getId(),$message,$title,"");

        }
        if ($action=="FINISH"){
            $ride->setStatus(Ride::FINISH);
            $message="Votre course a terminee vous etes a destination. nous vous remercions";
            $title="Course terminé";
            $this->sendNotificationCustomer($ride->getCustomer()->getId(),$message,$title,"");

        }
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/shippings/actions", name="api_shipping_action")
     * @param Request $request
     * @return Response
     */
    public function shippingAction(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $shipping=$this->shippingRepository->find($data['ride']);
        $action=$data['action'];
        if ($action=="VALIDATE_DRIVER"){
            $driver=$this->driverRepository->find($data['driver']);
            $shipping->setDriver($driver);
            $shipping->setStatus(Shipping::ONTHEWAY);
            $message="Le chauffeur ".$driver->getCompte()->getName()." a accepter votre course";
            $title="Prise de la course";
            $this->sendNotificationCustomer($shipping->getCustomer()->getId(),$message,$title,"");
        }
        if ($action=="PREPARING"){
            $shipping->setStatus(Shipping::PREPARING);
            $message="Votre commande ".$shipping->getId()." est encours de preparation";
            $title="Commande en preparation";
            $this->sendNotificationCustomer($shipping->getCustomer()->getId(),$message,$title,"");

        }
        if ($action=="FINISH"){
            $shipping->setStatus(Shipping::DELIVERED);
            $message="Le chauffeur ".$driver->getCompte()->getName()." a accepter votre course";
            $title="Prise de la course";
            $this->sendNotificationCustomer($shipping->getCustomer()->getId(),$message,$title,"");

        }
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    private function sendNotificationDriver($driver,$status,$message,$title){
       /* $object="Prise course";
        $message="Vous avez une course de".$ride->getDistance()." Km  allant de ".$ride->getStartto(). " à ".$ride->getEndto();
       */ $notification=new Notification();
        $notification->setUserid($driver);
        $notification->setMessage($message);
        $notification->setAllcustomer(false);
        $notification->setAlldriver(false);
        $notification->setTitle($title);
        $notification->setSendDate(new \DateTime('now',new \DateTimeZone("Africa/Brazzaville")));
        $notification->setIcon("");
        $this->doctrine->persist($notification);
        $this->doctrine->flush();
    }
    private function sendNotificationCustomer($customer,$message,$title,$status){
  /*      $object="Prise course";
        $message="Tres cher ".$ride->getCustomer()->getCompte()->getName()."votre course allant de ".$ride->getStartto(). " à ".$ride->getEndto().
            " a ete enregistré avec success. votre chauffeur M.".$ride->getDriver()->getCompte()->getName()." est a 5 min du point de depart";
       */
        $notification=new Notification();
        $notification->setUserid($customer);
        $notification->setMessage($message);
        $notification->setAllcustomer(false);
        $notification->setAlldriver(false);
        $notification->setTitle($title);
        $notification->setSendDate(new \DateTime('now',new \DateTimeZone("Africa/Brazzaville")));
        $notification->setIcon("");
        $this->doctrine->persist($notification);
        $this->doctrine->flush();
    }
    private function sendNotificationAdministration(){

    }

    /**
     * @Rest\Post("/v1/rides/prices", name="api_ride_price_action")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPrice(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $distance=$data['distance'];
        $configuration=$this->configurationRepository->findOneByLast();
        $price=$distance*$configuration->getTarifkm();
        $view = $this->view([
            "price"=>$price,
            "durre_minute"=>0,
            "durre_heure"=>0
        ], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Rest\Post ("/v1/wallet/cinetpay",name="savewalletcinetpay")
     */
    public function saveWalletCinetPay(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $customer=$this->customerRepository->find($data['customer']);
        $wallet = $this->walletRepository->findOneBy(['beneficiare'=>$customer->getCompte()]);
        $amount=$data['amount'];
        $wallet->setAmount($amount);
        $transaction_numero = '';
        $allowed_characters = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'f');
        for ($i = 1; $i <= 8; $i++) {
            $transaction_numero .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }
        $notify_url = $this->generateUrl('notifyurlajaxcinet', ['wallet' => $wallet->getId(),'amount'=>$amount]);
        $return_url = $this->generateUrl('notifyurlajaxcinet', ['wallet' => $wallet->getId()]);
        $notify_url = $this->getParameter('domain') . $notify_url;
        $return_url = $this->getParameter('domain') . $return_url;
        $formData=[
            "transaction_id"=>$transaction_numero,
            "amount"=> $data['amount'],
            "customer_phone_number" => is_null($customer->getCompte()->getName())?"24200698755":$customer->getCompte()->getPhone(),
            "customer_country" => "CD",
            "currency"=> "XAF",
            "customer_surname"=> is_null($customer->getCompte()->getName())?"Paul customer":$customer->getCompte()->getName(),
            "customer_name"=> is_null($customer->getCompte()->getName())?"Paul customer":$customer->getCompte()->getName(),
            "description"=> "recharge du wallet FILIFILO",
            "customer_email" => empty($customer->getCompte()->getEmail())?"exemple@gmail.com":$customer->getCompte()->getEmail(),
            "notify_url" => $notify_url,
            "return_url" => $return_url,
            "channels" => "ALL",
        ];
        $res= $this->cinetpayService->sendPrefund($formData);
         $this->doctrine->flush();
        $view = $this->view($res, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Rest\Post ("/v1/wallet/maxicash",name="savewalletmaxicash")
     */
    public function saveWalletMaxicashPay(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $customer=$this->customerRepository->find($data['customer']);
        $wallet = $this->walletRepository->findOneBy(['beneficiare'=>$customer->getCompte()]);
        $amount=$data['amount'];
        $wallet->setAmount($amount);
        $transaction_numero = '';
        $allowed_characters = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'f');
        for ($i = 1; $i <= 8; $i++) {
            $transaction_numero .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }
        $notify_url = $this->generateUrl('notifyurlajaxcinet', ['wallet' => $wallet->getId()]);
        $return_url = $this->generateUrl('notifyurlajaxcinet', ['wallet' => $wallet->getId()]);
        $notify_url = $this->getParameter('domain') . $notify_url;
        $return_url = $this->getParameter('domain') . $return_url;
        $formdata=[
            'amount'=>$amount,
            'phone'=>is_null($customer->getCompte()->getPhone())?"2406987541257":$customer->getCompte()->getPhone(),
            'email'=>is_null($customer->getCompte()->getEmail())?"exemple@filifilo.com":$customer->getCompte()->getEmail(),
            'reference'=>$transaction_numero,
            "PayType" => "MaxiCash",
            "MerchantID" => $this->getParameter('MERCHANT_USERNAME'),
            "MerchantPassword" => $this->getParameter('MERCHANT_PASSWORD'),
            "Amount" => "0".$amount,
            "Currency" => "maxiDollar",
            "Telephone" => is_null($customer->getCompte()->getPhone())?"2406987541257":$customer->getCompte()->getPhone(),
            "Email" => is_null($customer->getCompte()->getEmail())?"exemple@filifilo.com":$customer->getCompte()->getEmail(),
            "Language" => "fr", //en or fr
            "Reference" => $transaction_numero,
            "SuccessURL" => $notify_url,
            "FailureURL" => $notify_url,
            "CancelURL" => $notify_url,
            "NotifyURL" => $notify_url,
            ];
            $this->doctrine->flush();
        $view = $this->view($formdata, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Route("/auth/notifyurl/cinetpay", name="notifyurlajaxcinet", methods={"POST","GET"})
     */
    public function notifyurlCinetPay(Request $request): JsonResponse
    {

        $transaction = $_GET['wallet'];
        $this->logger->error("notify call" . $transaction);
        $transaction_ = $this->walletRepository->find($transaction);

            $data = [
                'id_transaction' => $_GET['transactionref']
            ];
            $this->logger->error("notify call---post status" . $_GET['transactionref']);

            if ($_POST['status'] == "SUCCESS") {
                $transaction_->setTotal($transaction_->getTotal() + $transaction_->getAmount());
                $this->doctrine->persist($transaction_);
            }
        $transaction_->setAmount(0.0);
            $this->doctrine->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Rest\Get("/v1/notifications/driver/{id}", name="api_notification_driver")
     * @param Request $request
     * @param Driver $driver
     * @return Response
     */
    public function notificationDriver(Request $request,Driver $driver)
    {
        $notification=$this->notificationRepository->findOneByLastUser($driver->getId());
        return new JsonResponse([
            "message"=>$notification->getMessage(),
            'title'=>$notification->getTitle(),
            'icon'=>$notification->getIcon(),
            'user'=>$driver->getId(),
            'id'=>$notification->getId()
        ], 200);
    }

    /**
     * @Rest\Get("/v1/notifications/customer/{id}", name="api_notification_customer")
     * @param Request $request
     * @param Customer $customer
     * @return Response
     */
    public function notificationCustomer(Request $request,Customer $customer)
    {
        $notification=$this->notificationRepository->findOneByLastUser($customer->getId());
        return new JsonResponse([
            "message"=>$notification->getMessage(),
            'title'=>$notification->getTitle(),
            'icon'=>$notification->getIcon(),
            'user'=>$customer->getId(),
            'id'=>$notification->getId()
        ], 200);
    }

    /**
     * @param Request $request
     * @return void
     * @Rest\Post ("/v1/notifications/response",name="notification_response")
     */
    public function responseNotification(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $notification=$this->notificationRepository->find($data['id']);
    }
}
