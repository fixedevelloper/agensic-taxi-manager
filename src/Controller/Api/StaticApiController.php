<?php


namespace App\Controller\Api;


use App\Entity\AddressShipping;
use App\Entity\AffectationRide;
use App\Entity\Car;
use App\Entity\Configuration;
use App\Entity\Customer;
use App\Entity\Driver;
use App\Entity\LineShipping;
use App\Entity\Place;
use App\Entity\Proprietaire;
use App\Entity\Ride;
use App\Entity\Shipping;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\AddressShippingRepository;
use App\Repository\AffectationRideRepository;
use App\Repository\ArticleRepository;
use App\Repository\CarRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\CustomerRepository;
use App\Repository\DriverRepository;
use App\Repository\ImageRepository;
use App\Repository\PlaceRepository;
use App\Repository\ProprietaireRepository;
use App\Repository\RideRepository;
use App\Repository\ShippingRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StaticApiController extends AbstractFOSRestController
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
    private ImageRepository $imageRepository;
    private ArticleRepository $articleRepository;
    /**
     * @param ImageRepository $imageRepository
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
    public function __construct(ImageRepository $imageRepository,ArticleRepository $articleRepository, ShippingRepository $shippingRepository,PlaceRepository $placeRepository,AddressShippingRepository $addressRepository,ConfigurationRepository $configurationRepository,EntityManagerInterface $entityManager,UserRepository $userRepository,AffectationRideRepository $affectationRideRepository,
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
        $this->imageRepository = $imageRepository;
        $this->articleRepository = $articleRepository;
        $this->doctrine=$entityManager;
    }

    /**
     * @Rest\Post("/v1/parametres", name="api_parametres_post")
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function configuration(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $item = $this->configurationRepository->findOneByLast();
        if (is_null($item)) {
            $item = new Configuration();
            $this->doctrine->persist($item);
        }
        $item->setTarifkm($data['tarifkm']);
        $item->setTarifheure($data['tarifheure']);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/cars", name="api_cars_post")
     * @param Request $request
     * @return Response
     */
    public function cartPost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->carRepository->find($data['id']);
        } else {
            $item = new Car();
            $proprietaire=$this->propretaireRepository->find($data['propretaire']);
            $item->setPropretaire($proprietaire);
            $this->doctrine->persist($item);
        }
        $item->setRate(0);
        $item->setBaseprice($data['baseprice']);
        $item->setMarque($data['marque']);
        $item->setModel($data['model']);
        $item->setRegistrationNumber($data['matricule']);
        $item->setVariant($data['cartegrise']);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/drivers", name="api_drivers_post")
     * @param Request $request
     */
    public function driverPost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->driverRepository->find($data['id']);
            $compte=$item->getCompte();
        } else {
            $item = new Driver();
            $item->setIsdriver(true);
            $this->doctrine->persist($item);
            $compte=new User();
            $compte->setEmail($data['email']);
            $compte->setUsername($data['email']);
            $compte->setRoles(["ROLE_DRIVER"]);
            $compte->setIsactivate(true);
            $plainPassword = "12345";
            $hashedPassword = $this->passwordEncoder->hashPassword($compte, $plainPassword);
            $compte->setPassword($hashedPassword);
            $this->doctrine->persist($compte);
            $item->setCompte($compte);
            $this->createWallet($compte);
        }
        $compte->setPhone($data['phone']);
        $compte->setName($data['name']);
        $item->setStatus(false);
        $item->setCni($data['cni']);
        $item->setLicence($data['licence']);
        $item->setPermitdriver($data['numeropermit']);
        $item->setExpiratedPermit(new \DateTimeImmutable($data['expiredpermit']));
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/propretaires", name="api_propretaires_post")
     * @param Request $request
     * @return Response
     */
    public function propretairePost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->propretaireRepository->find($data['id']);
            $compte=$item->getCompte();
        } else {
            $item = new Proprietaire();
            $this->doctrine->persist($item);
            $compte=new User();
            $compte->setEmail($data['email']);
            $compte->setUsername($data['email']);
            $compte->setRoles(["ROLE_PROPRETAIRE"]);
            $compte->setIsactivate(true);
            $plainPassword = "12345";
            $hashedPassword = $this->passwordEncoder->hashPassword($compte, $plainPassword);
            $compte->setPassword($hashedPassword);
            $this->doctrine->persist($compte);
            $item->setCompte($compte);
            $this->createWallet($compte);
        }
        $compte->setPhone($data['phone']);
        $compte->setName($data['name']);
        $item->setCni($data['cni']);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/customers", name="api_customerss_post")
     * @param Request $request
     * @return Response
     */
    public function customerPost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->customerRepository->find($data['id']);
            $compte=$item->getCompte();
        } else {
            $item = new Customer();
            $this->doctrine->persist($item);
            $compte=new User();
            $compte->setEmail($data['email']);
            $compte->setUsername($data['email']);
            $compte->setRoles(["ROLE_CUSTOMER"]);
            $compte->setIsactivate(true);
            //$plainPassword = $data['password'];
            $plainPassword = "12345";
            $hashedPassword = $this->passwordEncoder->hashPassword($compte, $plainPassword);
            $compte->setPassword($hashedPassword);
            $this->doctrine->persist($compte);
            $item->setCompte($compte);
            $this->createWallet($compte);
        }
        $compte->setPhone($data['phone']);
        $compte->setName($data['name']);
        $item->setTotalRide(0);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/rides", name="api_rides_post")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function riderPost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->rideRepository->find($data['id']);
        } else {
            $item = new Ride();
            $customer=$this->customerRepository->find($data['customer']);
            $item->setCustomer($customer);
            $this->doctrine->persist($item);
        }
        if ($item->getStatus()==Ride::PENDING){
            $affecation=$this->affactationRepository->find($data['affectation']);
            if (!is_null($data['affectation'])){
                $item->setCar($affecation->getCAr());
                $item->setDriver($affecation->getDriver());
            }
        }
        $item->setAmount($data['amount']);
        $item->setStartto($data['startto']);
        $item->setEndto($data['endto']);
        $item->setLongitudestart($data['longitude_start']);
        $item->setLatitudestart($data['latitude_start']);
        $item->setLatitudestop($data['latitude_stop']);
        $item->setLongitudestop($data['longitude_stop']);
        $item->setDistance($data['distance']);
        $item->setPickupend(new \DateTime($data['pickupend'],new \DateTimeZone('Africa/Brazzaville')));
        $item->setPikupbegin(new \DateTime($data['pickupbegin'],new \DateTimeZone('Africa/Brazzaville')));
        $item->setStatus(Ride::PENDING);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/places", name="api_places_post")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function placePost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->placeRepository->find($data['id']);
        } else {
            $item = new Place();
            $propretaire=$this->propretaireRepository->find($data['propretaire']);
            $item->setPropretaire($propretaire);
            $this->doctrine->persist($item);
        }
        $item->setPhone($data['phone']);
        $item->setName($data['name']);
        $item->setAddress($data['address']);
        $item->setBp($data['bp']);
        if (!empty($data['image'])) {
            $image = $this->imageRepository->find($data['image']);
            $item->setImage($image);
        }
        $item->setLatitude($data['latitude']);
        $item->setLongitude($data['longitude']);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/addresses/customer/{id}", name="api_address_post")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function addressPost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->addressRepository->find($data['id']);
        } else {
            $item = new AddressShipping();
            $customer=$this->customerRepository->find($data['customer']);
            $item->setCustomer($customer);
            $this->doctrine->persist($item);
        }
        $item->setName($data['name']);
        $item->setAddress($data['address']);
        $item->setLatitude($data['latitude']);
        $item->setLongitude($data['longitude']);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/shippings", name="api_shippings_post")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function shippingPost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->shippingRepository->find($data['id']);
        } else {
            $item = new Shipping();
            $item->setDateCreated(new \DateTime('now',new \DateTimeZone('Africa/Brazzaville')));
            $place=$this->placeRepository->find($data['place']);
            $customer=$this->customerRepository->find($data['customer']);
            $item->setPlace($place);
            $item->setCustomer($customer);
            $this->doctrine->persist($item);
            $lines=$data['lines'];
            for ($i=0;$i<sizeof($lines);$i++){
                $line=new LineShipping();
                $line->setAmount($lines[$i]['amount']);
                $line->setQuantity($lines[$i]['quantity']);
                $article=$this->articleRepository->find($lines[$i]['article']);
                $line->setArticle($article);
                $line->setShipping($item);
                $this->doctrine->persist($line);
            }
        }
        $item->setAddress($data['address']);
        $item->setDistance($data['distance']);
        $item->setTotal($data['total']);
        $item->setPriceshipping($data['priceshipping']);
        $item->setLatEnd($data['latend']);
        $item->setLatStart($data['latstart']);
        $item->setLngEnd($data['lngend']);
        $item->setLngStart($data['lngstart']);
        $item->setStatus(Shipping::PENDING);
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    private function createWallet(User $user){
        $wallet=new Wallet();
        $wallet->setAmount(0.0);
        $wallet->setBeneficiare($user);
        $reference="";
        $allowed_characters = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0,"a","b","c","d",'e',"f","g"];
        for ($i = 1; $i <= 15; ++$i) {
            $reference .= $allowed_characters[rand(0, count($allowed_characters) - 1)];
        }

        $wallet->setWalletnumber($reference);
        $this->doctrine->persist($wallet);
    }
    /**
     * @Rest\Post("/v1/affectations", name="api_affectations_post")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function affectationPost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        if (!is_null($data['id'])) {
            $item = $this->affactationRepository->find($data['id']);
        } else {
            $item = new AffectationRide();
            $driver=$this->driverRepository->find($data['driver']);
            $car=$this->carRepository->find($data['car']);
            $item->setCar($car);
            $item->setDriver($driver);
            $this->doctrine->persist($item);
        }
        $item->setIsEnable($data['enable']);
        $item->setExpiredAt(new \DateTimeImmutable($data['expired'],new \DateTimeZone('Africa/Brazzaville')));

        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/cars", name="api_car_list")
     * @param Request $request
     * @return Response
     */
    public function carList(Request $request)
    {
        $items = $this->carRepository->findAll();
        $data = [];
        foreach ($items as $item) {
           // $image = $product->getImages()[0];
            $data[] = [
                'id' => $item->getId(),
                'rate' => $item->getRate(),
                'baseprice' => $item->getBaseprice(),
                'marque' => $item->getMarque(),
                'model' => $item->getModel(),
                'matricule' => $item->getRegistrationNumber(),
                'cartegrise' => $item->getVariant(),
                'propretaire_id' => $item->getPropretaire()->getId(),
                'propretaire' => $item->getPropretaire()->getCompte()->getName(),
                //'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/cars/propretaire/{id}", name="api_car_list_propretaire")
     * @param Request $request
     * @param Proprietaire $proprietaire
     * @return Response
     */
    public function carPropretaireList(Request $request,Proprietaire $proprietaire)
    {
        $items = $this->carRepository->findBy(['propretaire'=>$proprietaire]);
        $data = [];
        foreach ($items as $item) {
            // $image = $product->getImages()[0];
            $data[] = [
                'id' => $item->getId(),
                'rate' => $item->getRate(),
                'baseprice' => $item->getBaseprice(),
                'marque' => $item->getMarque(),
                'model' => $item->getModel(),
                'matricule' => $item->getRegistrationNumber(),
                'cartegrise' => $item->getVariant(),
                'propretaire_id' => $item->getPropretaire()->getId(),
                'propretaire' => $item->getPropretaire()->getCompte()->getName(),
                //'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/drivers", name="api_driver_list")
     * @param Request $request
     * @return Response
     */
    public function driverList(Request $request)
    {
        $items = $this->driverRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getCompte()->getName(),
                'phone' => $item->getCompte()->getPhone(),
                'cni' => $item->getCni(),
                'licence' => $item->getLicence(),
                'expired' => $item->getExpiratedPermit(),
                'permitnumber' => $item->getPermitdriver(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/propretaires", name="api_propretaires_list")
     * @param Request $request
     * @return Response
     */
    public function propretaireList(Request $request)
    {
        $items = $this->propretaireRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getCompte()->getName(),
                'cni' => $item->getCni(),
                'phone' => $item->getCompte()->getPhone(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/rides/one/{id}", name="api_ride_one")
     * @param Request $request
     * @return Response
     */
    public function rideOne(Request $request,Ride $ride)
    {
        $item=$ride;
        $data = [
            'id' => $item->getId(),
            'driverid' => is_null($item->getCar())?null:$item->getDriver()->getId(),
            'car_id' => is_null($item->getCar())?null:$item->getCar()->getId(),
            'customer_id' => $item->getCustomer()->getId(),
             'driver' => is_null($item->getDriver())?"":$item->getDriver()->getCompte()->getName(),
            'car' => is_null($item->getCar())?"":$item->getCar()->getRegistrationNumber(),
            'customer' => $item->getCustomer()->getCompte()->getName(),
            'amount' => $item->getAmount(),
            'status' => $item->getStatus(),
            'endto' => $item->getEndto(),
            'startto' => $item->getStartto(),
            'pickupend' => $item->getPickupend(),
            'pickupbegin' => $item->getPikupbegin(),
            'latitude_start'=>$item->getLatitudestart(),
            'latitude_stop'=>$item->getLatitudestop(),
            'longitude_start'=>$item->getLongitudestart(),
            'longitude_stop'=>$item->getLongitudestop(),
            'distance'=>$item->getDistance(),
        ];
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/rides", name="api_ride_list")
     * @param Request $request
     * @return Response
     */
    public function rideList(Request $request)
    {
        $items = $this->rideRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'driverid' => is_null($item->getCar())?null:$item->getDriver()->getId(),
                'car_id' => is_null($item->getCar())?null:$item->getCar()->getId(),
                'customer_id' => $item->getCustomer()->getId(),
                'driver' => is_null($item->getDriver())?"":$item->getDriver()->getCompte()->getName(),
                'car' => is_null($item->getCar())?"":$item->getCar()->getRegistrationNumber(),
                'customer' => $item->getCustomer()->getCompte()->getName(),
                'amount' => $item->getAmount(),
                'status' => $item->getStatus(),
                'endto' => $item->getEndto(),
                'startto' => $item->getStartto(),
                'pickupend' => $item->getPickupend(),
                'pickupbegin' => $item->getPikupbegin(),
                'latitude_start'=>$item->getLatitudestart(),
                'latitude_stop'=>$item->getLatitudestop(),
                'longitude_start'=>$item->getLongitudestart(),
                'longitude_stop'=>$item->getLongitudestop(),
                'distance'=>$item->getDistance(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/rides/pending", name="api_ride_list_pending")
     * @param Request $request
     * @return Response
     */
    public function rideListPending(Request $request)
    {
        $items = $this->rideRepository->findBy(['status'=>Ride::PENDING]);
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'driverid' => is_null($item->getCar())?null:$item->getDriver()->getId(),
                'car_id' => is_null($item->getCar())?null:$item->getCar()->getId(),
                'customer_id' => $item->getCustomer()->getId(),
                'driver' => is_null($item->getDriver())?"":$item->getDriver()->getCompte()->getName(),
                'car' => is_null($item->getCar())?"":$item->getCar()->getRegistrationNumber(),
                'customer' => $item->getCustomer()->getCompte()->getName(),
                'amount' => $item->getAmount(),
                'status' => $item->getStatus(),
                'endto' => $item->getEndto(),
                'startto' => $item->getStartto(),
                'pickupend' => $item->getPickupend(),
                'pickupbegin' => $item->getPikupbegin(),
                'latitude_start'=>$item->getLatitudestart(),
                'latitude_stop'=>$item->getLatitudestop(),
                'longitude_start'=>$item->getLongitudestart(),
                'longitude_stop'=>$item->getLongitudestop(),
                'distance'=>$item->getDistance(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/rides/finish", name="api_ride_list_finish")
     * @param Request $request
     * @return Response
     */
    public function rideListFinish(Request $request)
    {
        $items = $this->rideRepository->findBy(['status'=>Ride::FINISH]);
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'driverid' => is_null($item->getCar())?null:$item->getDriver()->getId(),
                'car_id' => is_null($item->getCar())?null:$item->getCar()->getId(),
                'customer_id' => $item->getCustomer()->getId(),
                'driver' => is_null($item->getDriver())?"":$item->getDriver()->getCompte()->getName(),
                'car' => is_null($item->getCar())?"":$item->getCar()->getRegistrationNumber(),
                'customer' => $item->getCustomer()->getCompte()->getName(),
                'amount' => $item->getAmount(),
                'status' => $item->getStatus(),
                'endto' => $item->getEndto(),
                'startto' => $item->getStartto(),
                'pickupend' => $item->getPickupend(),
                'pickupbegin' => $item->getPikupbegin(),
                'latitude_start'=>$item->getLatitudestart(),
                'latitude_stop'=>$item->getLatitudestop(),
                'longitude_start'=>$item->getLongitudestart(),
                'longitude_stop'=>$item->getLongitudestop(),
                'distance'=>$item->getDistance(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/rides/customer/{id}", name="api_ride_list_customer")
     * @param Request $request
     * @param Customer $customer
     * @return Response
     */
    public function rideListByCustomer(Request $request,Customer $customer)
    {
        $items = $this->rideRepository->findBy(['customer'=>$customer]);
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'driverid' => is_null($item->getCar())?null:$item->getDriver()->getId(),
                'car_id' => is_null($item->getCar())?null:$item->getCar()->getId(),
                'customer_id' => $item->getCustomer()->getId(),
                'driver' => is_null($item->getDriver())?"":$item->getDriver()->getCompte()->getName(),
                'car' => is_null($item->getCar())?"":$item->getCar()->getRegistrationNumber(),
                'customer' => $item->getCustomer()->getCompte()->getName(),
                'amount' => $item->getAmount(),
                'status' => $item->getStatus(),
                'endto' => $item->getEndto(),
                'startto' => $item->getStartto(),
                'pickupend' => $item->getPickupend()->format("Y-m-d h:m"),
                'pickupbegin' => $item->getPikupbegin()->format("Y-m-d h:m"),
                'latitude_start'=>$item->getLatitudestart(),
                'latitude_stop'=>$item->getLatitudestop(),
                'longitude_start'=>$item->getLongitudestart(),
                'longitude_stop'=>$item->getLongitudestop(),
                'distance'=>$item->getDistance(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/rides/driver/{id}", name="api_ride_list_driver")
     * @param Request $request
     * @param Customer $customer
     * @return Response
     */
    public function rideListByDriver(Request $request,Driver $driver)
    {
        $items = $this->rideRepository->findBy(['driver'=>$driver]);
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'driverid' => is_null($item->getCar())?null:$item->getDriver()->getId(),
                'car_id' => is_null($item->getCar())?null:$item->getCar()->getId(),
                'customer_id' => $item->getCustomer()->getId(),
                'driver' => is_null($item->getDriver())?"":$item->getDriver()->getCompte()->getName(),
                'car' => is_null($item->getCar())?"":$item->getCar()->getRegistrationNumber(),
                'customer' => $item->getCustomer()->getCompte()->getName(),
                'amount' => $item->getAmount(),
                'status' => $item->getStatus(),
                'endto' => $item->getEndto(),
                'startto' => $item->getStartto(),
                'pickupend' => $item->getPickupend(),
                'pickupbegin' => $item->getPikupbegin(),
                'latitude_start'=>$item->getLatitudestart(),
                'latitude_stop'=>$item->getLatitudestop(),
                'longitude_start'=>$item->getLongitudestart(),
                'longitude_stop'=>$item->getLongitudestop(),
                'distance'=>$item->getDistance(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/customers", name="api_customerr_list")
     * @param Request $request
     * @return Response
     */
    public function customerList(Request $request)
    {
        $items = $this->customerRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getCompte()->getName(),
                'phone' => $item->getCompte()->getPhone(),
                'email' => $item->getCompte()->getEmail(),
                'cni' => '',
                'totalrides' => $item->getTotalRide(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/affectations", name="api_affectation_list")
     * @param Request $request
     * @return Response
     */
    public function affectationlist(Request $request)
    {
        $items = $this->affactationRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'driver_id' => $item->getDriver()->getId(),
                'driver' => $item->getDriver()->getCompte()->getName(),
                'car_model' => $item->getCAr()->getModel(),
                'car_marque' => $item->getCAr()->getMarque(),
                'car_matricule' => $item->getCAr()->getRegistrationNumber(),
                'status'=>$item->isIsEnable(),
                'expired'=>$item->getExpiredAt(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/affectations/driver/{id}", name="api_affectation_driver_list")
     * @param Request $request
     * @return Response
     */
    public function affectationByDriver(Driver $driver,Request $request)
    {
        $items = $this->affactationRepository->findBy(['driver'=>$driver]);
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'driver_id' => $item->getDriver()->getId(),
                'driver' => $item->getDriver()->getCompte()->getName(),
                'car_model' => $item->getCAr()->getModel(),
                'car_marque' => $item->getCAr()->getMarque(),
                'car_baseprice' => $item->getCAr()->getBaseprice(),
                'car_matricule' => $item->getCAr()->getRegistrationNumber(),
                'status'=>$item->isIsEnable(),
                'expired'=>$item->getExpiredAt(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/places", name="api_place_list")
     * @param Request $request
     * @return Response
     */
    public function placeList(Request $request)
    {
        $items = $this->placeRepository->findAll();
        $data = [];
        foreach ($items as $item) {
        $image = $item->getImage();
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'phone' => $item->getPhone(),
                'address' => $item->getAddress(),
                'propretaire' => $item->getPropretaire()->getId(),
                'propretaire_name' => $item->getPropretaire()->getCompte()->getName(),
                'bp' => $item->getBp(),
                'latitude' => $item->getLatitude(),
                'longitude' => $item->getLongitude(),
                'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/places/{id}", name="api_place_one")
     * @param Request $request
     * @param Place $place
     * @return Response
     */
    public function placeOne(Request $request,Place $place)
    {
        $image = $place->getImage();
            $data = [
                'id' => $place->getId(),
                'name' => $place->getName(),
                'phone' => $place->getPhone(),
                'address' => $place->getAddress(),
                'propretaire' => $place->getPropretaire()->getId(),
                'bp' => $place->getBp(),
                'latitude' => $place->getLatitude(),
                'longitude' => $place->getLongitude(),
                'imageid'=>is_null($image)? null:$image->getId(),
                'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];

        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/shippings", name="api_shippings_list")
     * @param Request $request
     * @return Response
     */
    public function shippingList(Request $request)
    {
        $items = $this->shippingRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $lines_=[];
            $lines=$item->getLineShippings();
            foreach ($lines as $line){
                $lines_[]=[
                   'article'=>$line->getArticle()->getName(),
                   'amount'=>$line->getAmount(),
                    'quantity'=>$line->getQuantity(),
                ];
            }
            $data[] = [
                'id' => $item->getId(),
                'distance' => $item->getDistance(),
                'placeid' => $item->getPlace()->getId(),
                'placename' => $item->getPlace()->getName(),
                'driver' =>is_null($item->getDriver())?"": $item->getDriver()->getCompte()->getName(),
                'status' => $item->getStatus(),
                'createdat' => $item->getDateCreated()->format("Y-m-d h:m"),
                'sourcelat' => $item->getLatStart(),
                'sourcelng' => $item->getLngStart(),
                'destinationlat' => $item->getLatEnd(),
                'destinationlng' => $item->getLngEnd(),
                'priceshipping' => $item->getPriceshipping(),
                'address' => $item->getAddress(),
                'total' => $item->getTotal(),
                'customerid' => $item->getCustomer()->getId(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'lines'=>$lines_
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/shippings/{id}/one", name="api_shippings_one")
     * @param Request $request
     * @return Response
     */
    public function shippingOne(Request $request,Shipping $shipping)
    {
            $lines_=[];
            $lines=$shipping->getLineShippings();
            foreach ($lines as $line){
                $lines_[]=[
                    'article'=>$line->getArticle()->getName(),
                    'amount'=>$line->getAmount(),
                    'quantity'=>$line->getQuantity(),
                ];
            }
            $item=$shipping;
            $data= [
                'id' => $item->getId(),
                'distance' => $item->getDistance(),
                'placeid' => $item->getPlace()->getId(),
                'placename' => $item->getPlace()->getName(),
                'status' => $item->getStatus(),
                'driver' =>is_null($item->getDriver())?"": $item->getDriver()->getCompte()->getName(),
                'createdat' => $item->getDateCreated()->format("Y-m-d h:m"),
                'sourcelat' => $item->getLatStart(),
                'sourcelng' => $item->getLngStart(),
                'destinationlat' => $item->getLatEnd(),
                'destinationlng' => $item->getLngEnd(),
                'priceshipping' => $item->getPriceshipping(),
                'total' => $item->getTotal(),
                'address' => $item->getAddress(),
                'customerid' => $item->getCustomer()->getId(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'lines'=>$lines_
            ];

        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/shippings/customer/{customer}", name="api_shippings_customer")
     * @param Request $request
     * @return Response
     */
    public function shippingByCustomer(Request $request,Customer $customer)
    {
        $items = $this->shippingRepository->findBy(['customer'=>$customer]);
        $data = [];
        foreach ($items as $item) {
            $lines_=[];
            $lines=$item->getLineShippings();
            foreach ($lines as $line){
                $lines_[]=[
                    'article'=>$line->getArticle()->getName(),
                    'amount'=>$line->getAmount(),
                    'quantity'=>$line->getQuantity(),
                ];
            }
            $data[] = [
                'id' => $item->getId(),
                'distance' => $item->getDistance(),
                'placeid' => $item->getPlace()->getId(),
                'placename' => $item->getPlace()->getName(),
                'status' => $item->getStatus(),
                'driver' =>is_null($item->getDriver())?"": $item->getDriver()->getCompte()->getName(),
                'createdat' => $item->getDateCreated()->format("Y-m-d h:m"),
                'sourcelat' => $item->getLatStart(),
                'sourcelng' => $item->getLngStart(),
                'destinationlat' => $item->getLatEnd(),
                'destinationlng' => $item->getLngEnd(),
                'priceshipping' => $item->getPriceshipping(),
                'total' => $item->getTotal(),
                'address' => $item->getAddress(),
                'customerid' => $item->getCustomer()->getId(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'lines'=>$lines_
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/shippings/place/{place}", name="api_shippings_place")
     * @param Request $request
     * @return Response
     */
    public function shippingByPlace(Request $request,Place $place)
    {
        $items = $this->shippingRepository->findBy(['place'=>$place]);
        $data = [];
        foreach ($items as $item) {
            $lines_=[];
            $lines=$item->getLineShippings();
            foreach ($lines as $line){
                $lines_[]=[
                    'article'=>$line->getArticle()->getName(),
                    'amount'=>$line->getAmount(),
                    'quantity'=>$line->getQuantity(),
                ];
            }
            $data[] = [
                'id' => $item->getId(),
                'distance' => $item->getDistance(),
                'placeid' => $item->getPlace()->getId(),
                'placename' => $item->getPlace()->getName(),
                'status' => $item->getStatus(),
                'driver' =>is_null($item->getDriver())?"": $item->getDriver()->getCompte()->getName(),
                'createdat' => $item->getDateCreated()->format("Y-m-d h:m"),
                'sourcelat' => $item->getLatStart(),
                'sourcelng' => $item->getLngStart(),
                'destinationlat' => $item->getLatEnd(),
                'destinationlng' => $item->getLngEnd(),
                'priceshipping' => $item->getPriceshipping(),
                'total' => $item->getTotal(),
                'address' => $item->getAddress(),
                'customerid' => $item->getCustomer()->getId(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'lines'=>$lines_
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/shippings/driver/{driver}", name="api_shippings_driver")
     * @param Request $request
     * @param Driver $driver
     * @return Response
     */
    public function shippingByDriver(Request $request,Driver $driver)
    {
        $items = $this->shippingRepository->findBy(['driver'=>$driver]);
        $data = [];
        foreach ($items as $item) {
            $lines_=[];
            $lines=$item->getLineShippings();
            foreach ($lines as $line){
                $lines_[]=[
                    'article'=>$line->getArticle()->getName(),
                    'amount'=>$line->getAmount(),
                    'quantity'=>$line->getQuantity(),
                ];
            }
            $data[] = [
                'id' => $item->getId(),
                'distance' => $item->getDistance(),
                'placeid' => $item->getPlace()->getId(),
                'placename' => $item->getPlace()->getName(),
                'status' => $item->getStatus(),
                'driver' =>is_null($item->getDriver())?"": $item->getDriver()->getCompte()->getName(),
                'createdat' => $item->getDateCreated()->format("Y-m-d h:m"),
                'sourcelat' => $item->getLatStart(),
                'sourcelng' => $item->getLngStart(),
                'destinationlat' => $item->getLatEnd(),
                'destinationlng' => $item->getLngEnd(),
                'priceshipping' => $item->getPriceshipping(),
                'total' => $item->getTotal(),
                'address' => $item->getAddress(),
                'customerid' => $item->getCustomer()->getId(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'lines'=>$lines_
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/shippings/finish", name="api_shippings_finish_list")
     * @param Request $request
     * @return Response
     */
    public function shippingfinishList(Request $request)
    {
        $items = $this->shippingRepository->findBy(['status'=>Shipping::DELIVERED]);
        $data = [];
        foreach ($items as $item) {
            $lines_=[];
            $lines=$item->getLineShippings();
            foreach ($lines as $line){
                $lines_[]=[
                    'article'=>$line->getArticle()->getName(),
                    'amount'=>$line->getAmount(),
                    'quantity'=>$line->getQuantity(),
                ];
            }
            $data[] = [
                'id' => $item->getId(),
                'distance' => $item->getDistance(),
                'placeid' => $item->getPlace()->getId(),
                'placename' => $item->getPlace()->getName(),
                'status' => $item->getStatus(),
                'driver' =>is_null($item->getDriver())?"": $item->getDriver()->getCompte()->getName(),
                'createdat' => $item->getDateCreated()->format("Y-m-d h:m"),
                'sourcelat' => $item->getLatStart(),
                'sourcelng' => $item->getLngStart(),
                'destinationlat' => $item->getLatEnd(),
                'destinationlng' => $item->getLngEnd(),
                'priceshipping' => $item->getPriceshipping(),
                'total' => $item->getTotal(),
                'address' => $item->getAddress(),
                'customerid' => $item->getCustomer()->getId(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'lines'=>$lines_
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/shippings/pending", name="api_shippings_pending_list")
     * @param Request $request
     * @return Response
     */
    public function shippingstartList(Request $request)
    {
        $items = $this->shippingRepository->findBy(['status'=>Shipping::PENDING]);
        $data = [];
        foreach ($items as $item) {
            $lines_=[];
            $lines=$item->getLineShippings();
            foreach ($lines as $line){
                $lines_[]=[
                    'article'=>$line->getArticle()->getName(),
                    'amount'=>$line->getAmount(),
                    'quantity'=>$line->getQuantity(),
                ];
            }
            $data[] = [
                'id' => $item->getId(),
                'distance' => $item->getDistance(),
                'placeid' => $item->getPlace()->getId(),
                'placename' => $item->getPlace()->getName(),
                'status' => $item->getStatus(),
                'driver' =>is_null($item->getDriver())?"": $item->getDriver()->getCompte()->getName(),
                'createdat' => $item->getDateCreated()->format("Y-m-d h:m"),
                'sourcelat' => $item->getLatStart(),
                'sourcelng' => $item->getLngStart(),
                'destinationlat' => $item->getLatEnd(),
                'destinationlng' => $item->getLngEnd(),
                'priceshipping' => $item->getPriceshipping(),
                'total' => $item->getTotal(),
                'address' => $item->getAddress(),
                'customerid' => $item->getCustomer()->getId(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'lines'=>$lines_
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/addresses/customer/{id}", name="api_addresscustomer_list")
     * @param Request $request
     * @return Response
     */
    public function addressCustomer(Request $request,Customer $customer)
    {
        $items = $this->addressRepository->findBy(['customer'=>$customer]);
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'longitude' => $item->getLongitude(),
                'latitude' => $item->getLatitude(),
                'customername' => $item->getCustomer()->getCompte()->getName(),
                'customerid' => $item->getCustomer()->getId(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Get("/v1/parametres", name="api_parametres")
     * @param Request $request
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getParametrage(Request $request)
    {
        $item = $this->configurationRepository->findOneByLast();
            $data= [
                'id' => $item->getId(),
                'tarifheure' => $item->getTarifheure(),
                'tarifkm' => $item->getTarifkm(),
            ];
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
}
