<?php


namespace App\Controller\Api;


use App\Entity\Ride;
use App\Repository\AddressShippingRepository;
use App\Repository\AffectationRideRepository;
use App\Repository\CarRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\CustomerRepository;
use App\Repository\DriverRepository;
use App\Repository\PlaceRepository;
use App\Repository\ProprietaireRepository;
use App\Repository\RideRepository;
use App\Repository\ShippingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
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
    public function __construct(ShippingRepository $shippingRepository,PlaceRepository $placeRepository,AddressShippingRepository $addressRepository,ConfigurationRepository $configurationRepository,EntityManagerInterface $entityManager,UserRepository $userRepository,AffectationRideRepository $affectationRideRepository,
                                ProprietaireRepository $propretaireRepository,CarRepository $carRepository,
                                DriverRepository $driverRepository,RideRepository $rideRepository,CustomerRepository $customerRepository,
                                LoggerInterface $logger,UserPasswordHasherInterface $passwordEncoder)
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
        }
        if ($action=="STARTING_DRIVER"){
            $ride->setStatus(Ride::STARTING);
        }
        if ($action=="FINISH"){
            $ride->setStatus(Ride::FINISH);
        }
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    private function sendNotificationDriver(Ride $ride){
        $object="Prise course";
        $message="Vous avez une course de".$ride->getDistance()." Km  allant de ".$ride->getStartto(). " à ".$ride->getEndto();
    }
    private function sendNotificationCustomer(Ride $ride){
        $object="Prise course";
        $message="Tres cher ".$ride->getCustomer()->getCompte()->getName()."votre course allant de ".$ride->getStartto(). " à ".$ride->getEndto().
            " a ete enregistré avec success. votre chauffeur M.".$ride->getDriver()->getCompte()->getName()." est a 5 min du point de depart";
    }
    private function sendNotificationAdministration(){

    }
}
