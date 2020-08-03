<?php

namespace App\Services;

use App\Models\ValidationCar;
use App\Repositories\CarRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CarService {

    private $carRepository;

    public function __construct(CarRepositoryInterface $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function getAll() {
        
        try {
            $cars = $this->carRepository->getAll();
            $cars = (count($cars) > 0) ? $cars : [];
            return response()->json($cars, Response::HTTP_OK);
        } catch (QueryException $exception) {
            return response()->json(['error' => 'Erro de conexão com o banco de dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function get($id) {
        
        try {
            $car = $this->carRepository->get($id);
            $car = (count($car) > 0) ? $car : null;
            return response()->json($car, Response::HTTP_OK);
        } catch (QueryException $exception) {
            return response()->json(['error' => 'Erro de conexão com o banco de dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
            ValidationCar::RULE_CAR
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $car = $this->carRepository->store($request);
            return response()->json($car, Response::HTTP_CREATED);
        } catch (QueryException $exception) {
            return response()->json(['error' => 'Erro de conexão com o banco de dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function update($id, Request $request) {
        $validator = Validator::make(
            $request->all(),
            ValidationCar::RULE_CAR
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        
        try {
            $car = $this->carRepository->update($id, $request);
            return response()->json($car, Response::HTTP_OK);
        } catch (QueryException $exception) {
            return response()->json(['error' => 'Erro de conexão com o banco de dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function destroy($id) {
        
        try {
            $this->carRepository->destroy($id);
            return response()->json(null, Response::HTTP_OK);
        } catch (QueryException $exception) {
            return response()->json(['error' => 'Erro de conexão com o banco de dados'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

}
