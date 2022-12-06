<?php


namespace App\Controller\Api;


use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Place;
use App\Entity\Ride;
use App\Repository\AddressShippingRepository;
use App\Repository\AffectationRideRepository;
use App\Repository\ArticleRepository;
use App\Repository\CarRepository;
use App\Repository\CategoryRepository;
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
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EatsController extends AbstractFOSRestController
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
    private CategoryRepository $categoryRepository;
    private ImageRepository $imageRepository;
    private ArticleRepository $articleRepository;

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
    public function __construct(ImageRepository $imageRepository, ArticleRepository $articleRepository, CategoryRepository $categoryRepository, ShippingRepository $shippingRepository, PlaceRepository $placeRepository, AddressShippingRepository $addressRepository, ConfigurationRepository $configurationRepository, EntityManagerInterface $entityManager, UserRepository $userRepository, AffectationRideRepository $affectationRideRepository,
                                ProprietaireRepository $propretaireRepository, CarRepository $carRepository,
                                DriverRepository $driverRepository, RideRepository $rideRepository, CustomerRepository $customerRepository,
                                LoggerInterface $logger, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->logger = $logger;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->customerRepository = $customerRepository;
        $this->driverRepository = $driverRepository;
        $this->carRepository = $carRepository;
        $this->propretaireRepository = $propretaireRepository;
        $this->rideRepository = $rideRepository;
        $this->affactationRepository = $affectationRideRepository;
        $this->configurationRepository = $configurationRepository;
        $this->addressRepository = $addressRepository;
        $this->placeRepository = $placeRepository;
        $this->shippingRepository = $shippingRepository;
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->imageRepository = $imageRepository;
        $this->doctrine = $entityManager;
    }

    /**
     * @Rest\Post("/v1/categories", name="api_category_add")
     * @param Request $request
     * @return Response
     */
    public function categoryAction(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];

        if (is_null($data['id'])) {
            $place = $this->placeRepository->find($data['place']);
            $category = new Category();
            $category->setPlace($place);
            $this->doctrine->persist($category);
        } else {
            $category = $this->categoryRepository->find($data['id']);
        }
        $category->setName($data['name']);
        $category->setDescription($data['description']);
        if (!empty($data['image'])) {
            $image = $this->imageRepository->find($data['image']);
            $category->setImage($image);
        }

        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/images", name="api_image_post")
     * @param Request $request
     * @return Response
     */
    public function imagePost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res;
        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/products/';
        if (!is_null($data['id'])) {
            $image = $this->imageRepository->find($data['id']);
        } else {
            $image = new Image();
            $this->doctrine->persist($image);
        }
        if (!empty($data['filename'])) {
            $image_parts = explode(";base64,", $data['filename']);
            if (!empty($image_parts[1])) {
                $image_base64 = base64_decode($image_parts[1]);

                $file = $destination . $data['name'];
                if (file_put_contents($file, $image_base64)) {
                    $image->setSrc('uploads/products/' . $data['name']);
                }
            }
        }
        $image->setName($data['name']);
        if (!empty($data['alt'])) {
            $image->setAlt($data['alt']);
        }
        $this->doctrine->flush();
        $view = $this->view([
            'id' => $image->getId(),
            'name' => $image->getName(),
        ], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/v1/articles", name="api_article_add")
     * @param Request $request
     * @return Response
     */
    public function articleAction(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];

        if (is_null($data['id'])) {
            $this->logger->info("iciA0");
            $category = $this->categoryRepository->find($data['category']);
            $article = new Article();
            $article->setCategory($category);
            $article->setStatus(Article::VISIBLE);
            $this->doctrine->persist($article);
        } else {
            $article = $this->articleRepository->find($data['id']);
            $article->setStatus($data['status']);
        }
        $article->setName($data['name']);
        $article->setDescription($data['description']);
        $article->setPrice($data['price']);

        if (!empty($data['image'])) {
            $image = $this->imageRepository->find($data['image']);
            $article->setImage($image);
        }
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/articles/{place}", name="api_article_list_place")
     * @param Request $request
     * @param Place $place
     * @return Response
     */
    public function articleListPlace(Request $request, Place $place)
    {
        $items = $this->articleRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $image = $item->getImage();
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'price' => $item->getPrice(),
                'category' => $item->getCategory()->getId(),
                'category_name' => $item->getCategory()->getName(),
                'status' => $item->getStatus(),
                'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/articles", name="api_article_list")
     * @param Request $request
     * @param Place $place
     * @return Response
     */
    public function articleList(Request $request)
    {
        $items = $this->articleRepository->findAll();
        $data = [];
        foreach ($items as $item) {
            $image = $item->getImage();
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'price' => $item->getPrice(),
                'category' => $item->getCategory()->getId(),
                'category_name' => $item->getCategory()->getName(),
                'status' => $item->getStatus(),
                'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/articles/category/{category}", name="api_article_category_list")
     * @param Request $request
     * @param Place $place
     * @return Response
     */
    public function articlecategoryList(Request $request, Category $category)
    {
        $items = $category->getArticles();
        $data = [];
        foreach ($items as $item) {
            $image = $item->getImage();
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'price' => $item->getPrice(),
                'category' => $item->getCategory()->getId(),
                'category_name' => $item->getCategory()->getName(),
                'status' => $item->getStatus(),
                'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/categories/{place}", name="api_category_list")
     * @param Request $request
     * @param Place $place
     * @return Response
     */
    public function categoryList(Request $request, Place $place)
    {
        $items = $this->categoryRepository->findBy(['place' => $place]);
        $data = [];
        foreach ($items as $item) {
            $image = $item->getImage();
            $data[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'place' => $item->getPlace()->getId(),
                'place_name' => $item->getPlace()->getName(),
                'image_id' => $image->getId(),
                'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/places/detail/{place}", name="api_placedetail_list")
     * @param Request $request
     * @param Place $place
     * @return Response
     */
    public function placeDetail(Request $request, Place $place)
    {

        $image = $place->getImage();
        $categories = [];
        foreach ($place->getCategories() as $item) {
            $image = $item->getImage();
            $categories[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'description' => $item->getDescription(),
                'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
            ];
        }
        $drinks = [];
        $foods = [];
        $articles = $this->articleRepository->findByPlace($place);
        $list_foods = array_filter($articles, function ($item) {
            return $item->getType() == "food";
        });
        $list_drink = array_filter($articles, function ($item) {
            return $item->getType() == "drink";
        });
        foreach ($articles as $food) {
            $foods[] = [
                'name' => $food->getName(),
                'price' => $food->getPrice(),
                'description' => $food->getDescription(),
                'image' => is_null($food->getImage()) ? "" : $this->getParameter('domaininit') . $food->getImage()->getSrc(),
            ];
        }
        foreach ($articles as $drink) {
            $drinks[] = [
                'name' => $drink->getName(),
                'price' => $drink->getPrice(),
                'description' => $drink->getDescription(),
                'image' => is_null($drink->getImage()) ? "" : $this->getParameter('domaininit') . $drink->getImage()->getSrc(),
            ];
        }
        $menu = [
            'foods' => $foods,
            'drinks' => $drinks
        ];
        $data = [
            'id' => $place->getId(),
            'name' => $place->getName(),
            'description' => $place->getName(),
            'city' => $place->getAddress(),
            'phone' => $place->getPhone(),
            'bp' => $place->getBp(),
            'rating' => is_null($place->getRating())?0:$place->getRating(),
            'latitude' => "place->getLatitude()",
            'longitude' => "place->getLongitude()",
            'categories' => $categories,
            'reviews' => [],
            'menus' => $menu,
            'image' => is_null($image) ? "" : $this->getParameter('domaininit') . $image->getSrc(),
        ];

        $view = $this->view($data, Response::HTTP_OK, []);
        return $this->handleView($view);
    }
}
