<?php


namespace App\Controller\Api;


use App\Entity\User;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\Constraints\Timezone;

class AuthApiController extends AbstractFOSRestController
{
    private $passwordEncoder;
    /**
     * @var LoggerInterface
     */
    private $logger;
    private $userRepository;
    private $customerRepository;
    private $doctrine;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param LoggerInterface $logger
     * @param UserPasswordHasherInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager,UserRepository $userRepository,
                              LoggerInterface $logger,UserPasswordHasherInterface $passwordEncoder)
    {
        $this->logger = $logger;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository=$userRepository;
        $this->doctrine=$entityManager;
    }

    /**
     * @Rest\Post("/v1/api_login", name="api_auth")
     * @param Request $request
     */
    public function auth(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        $email=$data['email'];
        $password=$data['password'];
        $user=$this->userRepository->findOneBy(['username'=>$email]);
        if (null == $user) {
            $view = $this->view([], Response::HTTP_FORBIDDEN, []);
            return $this->handleView($view);
        }
        $isValid = $this->passwordEncoder->isPasswordValid($user, $password);
        if (!$isValid) {
            $view = $this->view([], Response::HTTP_FORBIDDEN, []);
            return $this->handleView($view);
        }

        $body=[
            'id'=>$user->getId(),
            'name'=>$user->getName(),
            'username'=>$user->getEmail(),
            'password'=>$user->getPhone(),
            'email'=>$user->getEmail(),
            'avatar'=>$user->getAvatar(),
        ];
        $view = $this->view($body, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/api_register", name="api_register")
     * @param Request $request
     */
    public function register(Request $request)
    {
        $this->logger->info($request->getContent());

        $res = json_decode($request->getContent(), true);
        $data=$res['data'];

        $user=new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['email']);
        $user->setName($data['name']);
        $plainPassword = $data['password'];
        $hashedPassword = $this->passwordEncoder->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        if (!empty($data['phone'])){
            $user->setPhone($data['phone']);
        }
        $user->setRoles(["ROLE_VENDOR"]);
        $user->setIsactivate(true);
        $this->doctrine->persist($user);
        $date1 = date_create(date("Y-m-d "),new \DateTimeZone('Africa/Brazzaville'));
        $this->doctrine->flush();
        $body=[
            'id'=>$user->getId(),
            'name'=>$user->getName(),
            'email'=>$user->getEmail(),
            'avatar'=>$user->getAvatar(),
        ];
        $view = $this->view($body, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
    /**
     * @Rest\Post("/v1/api_register_shop", name="api_register_shop")
     * @param Request $request
     */
    public function registerShop(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data=$res['data'];
        $user=new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['email']);
        $user->setName($data['name']);
        $plainPassword = $data['password'];
        $hashedPassword = $this->passwordEncoder->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        if (!empty($data['phone'])){
            $user->setPhone($data['phone']);
        }
        $user->setRoles(["ROLE_VENDOR"]);
        $user->setIsactivate(true);
        $this->doctrine->persist($user);
        $shop = new Shop();
        $shop->setName($data['name']);
        $shop->setCity($data['city']);
        $shop->setAddress($data['address']);
        $shop->setPhone($data['phone']);
        $shop->setPhone2($data['phone2']);
        $shop->setCountry($data['country']);
        $shop->setCountrycode($data['countrycode']);
        $shop->setAddress2($data['address2']);

        if (null == $shop->getSlug() || '' == $shop->getSlug()) {
            $slug = str_replace(' ', '_', strtolower($shop->getName()));
            $shop->setSlug($slug);
        } else {
            $slug = str_replace(' ', '_', strtolower($shop->getSlug()));
            $shop->setSlug($slug);
        }
        if (!empty($data['speciality'])) {
            $speciality = $this->specialityRepository->find($data['speciality']);
            $shop->setSpeciality($speciality);

        }
        $shop->setCompte($user);
        $this->doctrine->persist($shop);
        $this->doctrine->flush();
        $body=[
            'id'=>$user->getId(),
            'name'=>$user->getName(),
            'email'=>$user->getEmail(),
        ];
        $view = $this->view($body, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/changepassword",name="changepassword")
     * @param Request $request
     * @return Response
     */
    public function ChangePassword(Request $request)
    {
        $body = json_decode($request->getContent(), true);
        $user=$this->userRepository->find($body['id']);
        $oldpass = $body['password'];
        $newpassword = $body['cpassword'];
        $isValid = $this->passwordEncoder->isPasswordValid($user, $oldpass);
        if (!$isValid){
            throw new BadCredentialsException("Access not Authorized");
        }
        $hashedPassword = $this->passwordEncoder->hashPassword($user, $newpassword);
        $user->setPassword($hashedPassword);
        $this->doctrine->flush();
        $view = $this->view([
            'isvalid'=>$isValid,
            'user'=>$user->getId()
        ], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/forgetpassword",name="forgetpassword")
     * @param Request $request
     * @return Response
     */
    public function forgetPassword(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $email=$data['email'];
        $user=$this->userRepository->findOneBy(['email'=>$email]);
        if (null == $user) {
            throw new BadCredentialsException("Resource $email not found");
        }
        $view = $this->view([
            'message'=>"Mail send to your email",
            'code'=>200
        ], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/profile/{id}",name="api_profile")
     * @param User $user
     * @return Response
     */
    public function api_profile(User $user)
    {
        $customer=$this->customerRepository->findOneBy(['compte'=>$user]);
        $view = $this->view([
            'email'=>$user->getEmail(),
            'name'=>$user->getName(),
            'phone'=>$user->getPhone(),
            'avatar'=>$user->getAvatar(),
            'customer'=>$customer->getId(),
            'validitydate'=>$customer->getExpiredAt()->format("Y-m-d"),
            'totalsouscription'=>count($this->souscriptionRepository->findByCustomer($customer)),
        ], Response::HTTP_OK, []);
        return $this->handleView($view);
    }
}
