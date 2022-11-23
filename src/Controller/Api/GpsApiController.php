<?php


namespace App\Controller\Api;


use App\Entity\Car;
use App\Entity\GpsDevice;
use App\Entity\Ride;
use App\Repository\CarRepository;
use App\Repository\GpsDeviceRepository;
use App\Service\GpsService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GpsApiController extends AbstractFOSRestController
{

    private LoggerInterface $logger;
    private GpsService $gpsService;
    private EntityManagerInterface $doctrine;
    private CarRepository $carRepository;
    private GpsDeviceRepository $gpedeviceRepository;

    /**
     * GpsApiController constructor.
     * @param LoggerInterface $logger
     * @param $gpsService
     * @param $doctrine
     * @param $carRepository
     * @param $gpedeviceRepository
     */
    public function __construct(LoggerInterface $logger, GpsService $gpsService, EntityManagerInterface $doctrine,
                                CarRepository $carRepository, GpsDeviceRepository $gpedeviceRepository)
    {
        $this->logger = $logger;
        $this->gpsService = $gpsService;
        $this->doctrine = $doctrine;
        $this->carRepository = $carRepository;
        $this->gpedeviceRepository = $gpedeviceRepository;
    }

    /**
     * @Rest\Post("/v1/gpsdevise", name="api_gpsdevise_post")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function gpsdevisePost(Request $request)
    {
        $res = json_decode($request->getContent(), true);
        $data = $res['data'];
        $emei = $data['emei'];
        $license = $data['license'];
        $tracker_phone_number = $data['tracker_phone_number'];
        $admin_contact1 = $data['admin_contact1'];
        $operator=$data['operator'];
 /*       $response = $this->gpsService->createDevise([
            'imei' => $emei,
            'license' => $license,
            'tracker_phone_number' => $tracker_phone_number,
            'admin_contact1' => $admin_contact1,
            'operator'=>$operator
        ]);
        if ($response){*/
            if (!empty($data['id'])) {
                $item = $this->gpedeviceRepository->find($data['id']);
            } else {
                $devise = $this->carRepository->find($data['devise']);
                $item = new GpsDevice();
                $this->doctrine->persist($item);
                $devise->setGpsdevice($item);
            }
            $item->setEmei($data['emei']);
            $item->setLicense($data['license']);
            $item->setOperator($data['operator']);
            $item->setSimNumber($data['tracker_phone_number']);
            $item->setContactadmin($data['admin_contact1']);
       // }
        $this->doctrine->flush();
        $view = $this->view([], Response::HTTP_OK, []);
        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/v1/gpsdevise/{id}", name="api_gpsdevise_one")
     * @param Request $request
     * @param Car $car
     * @return Response
     */
    public function gpsdeviseOne(Request $request,Car $car)
    {
       $getdevice=$this->gpsService->getOneDevise([
            //'device_imei'=>$car->getGpsdevice()->getEmei(),
           'device_imei'=>"153865863307200"

        ]);
       /* if (is_null($car->getGpsdevice())){
            $response=[
                'imei'=>"",
                'license' => "",
                'tracker_phone_number' => "",
                'admin_contact1' => "",
                'operator'=>"",
                'id'=>null
            ];
        }else{
            $response=[
                'emei'=>$car->getGpsdevice()->getEmei(),
                'license' => $car->getGpsdevice()->getLicense(),
                'tracker_phone_number' => $car->getGpsdevice()->getSimNumber(),
                'admin_contact1' => $car->getGpsdevice()->getContactadmin(),
                'operator'=>$car->getGpsdevice()->getOperator(),
                'id'=>$car->getGpsdevice()->getId()
            ];
        }*/

        $view = $this->view($getdevice, Response::HTTP_OK, []);
        return $this->handleView($view);
    }

}
