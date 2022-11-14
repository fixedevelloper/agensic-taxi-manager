<?php


namespace App\Controller\Api;


use App\Entity\AffectationRide;
use App\Entity\Car;
use App\Entity\Customer;
use App\Entity\Driver;
use App\Entity\Proprietaire;
use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\AffectationRideRepository;
use App\Repository\CarRepository;
use App\Repository\CustomerRepository;
use App\Repository\DriverRepository;
use App\Repository\ProprietaireRepository;
use App\Repository\RideRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class StaticApiController extends AbstractFOSRestController
{
    private $passwordEncoder;
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $userRepository;
    private $customerRepository;
    private $carRepository;
    private $driverRepository;
    private $rideRepository;
    private $propretaireRepository;
    private $affactationRepository;
    private $doctrine;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param LoggerInterface $logger
     * @param UserPasswordHasherInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager,UserRepository $userRepository,AffectationRideRepository $affectationRideRepository,
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
        $this->doctrine=$entityManager;
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
            $affecation=$this->affactationRepository->find($data['affectation']);
            $item->setCar($affecation->getCAr());
            $item->setDriver($affecation->getDriver());
            $item->setCustomer($customer);
            $this->doctrine->persist($item);
        }
        $item->setAmount($data['amount']);
        $item->setStartto($data['startto']);
        $item->setEndto($data['endto']);
        $item->setPickupend(new \DateTime($data['pickupend'],new \DateTimeZone('Africa/Brazzaville')));
        $item->setPikupbegin(new \DateTime($data['pickupbegin'],new \DateTimeZone('Africa/Brazzaville')));
        $item->setStatus(Ride::PENDING);
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
                'carte_grise' => $item->getVariant(),
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
     * @Rest\Get("/v1/riders", name="api_ride_list")
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
                'driverid' => $item->getDriver()->getId(),
                'car_id' => $item->getCar()->getId(),
                'customer_id' => $item->getCustomer()->getId(),
                'driver' => $item->getDriver(),
                'car' => $item->getCar()->getRegistrationNumber(),
                'customer' => $item->getCustomer(),
                'amount' => $item->getAmount(),
                'status' => $item->getStatus(),
                'endto' => $item->getEndto(),
                'startto' => $item->getStartto(),
                'pickupend' => $item->getPickupend(),
                'pickupbegin' => $item->getPikupbegin(),
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
}
