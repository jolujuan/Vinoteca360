<?php

namespace App\Service;

use App\Entity\Measurements;
use App\Repository\SensorRepository;
use App\Repository\WineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MeasurementService
{
    private EntityManagerInterface $entityManager;
    private SensorRepository $sensorRepository;
    private WineRepository $wineRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SensorRepository       $sensorRepository,
        WineRepository         $wineRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->sensorRepository = $sensorRepository;
        $this->wineRepository = $wineRepository;
    }

    public function addMeasurement(array $measurementData): Measurements
    {

        if (!isset($measurementData['idSensor']) || !isset($measurementData['idWine'])) {
            throw new BadRequestHttpException('Invalid input: Missing fields');
        }

        if (!is_numeric($measurementData['idSensor']) || !is_numeric($measurementData['idWine'])) {
            throw new BadRequestHttpException('Invalid input: idSensor or idWine must be numeric');
        }

        $sensor = $this->sensorRepository->find($measurementData['idSensor']);
        $wine = $this->wineRepository->find($measurementData['idWine']);

        if (!$sensor || !$wine) {
            throw new NotFoundHttpException('Sensor or Wine not found');
        }

        $measurement = new Measurements();
        $measurement->setYear($measurementData['year']);
        $measurement->setSensor($sensor);
        $measurement->setWine($wine);
        $measurement->setColor($measurementData['color']);
        $measurement->setTemperature($measurementData['temperature']);
        $measurement->setGraduation($measurementData['graduation']);
        $measurement->setPh($measurementData['ph']);

        $this->entityManager->persist($measurement);
        $this->entityManager->flush();

        return $measurement;
    }
}