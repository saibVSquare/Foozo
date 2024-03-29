<?php

namespace App\Http\Libraries;

use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class ResponseBuilder
{

    protected $statusCode,

    $message,
    $data,
    $pagination,
    $authorization;

    public function __construct($statusCode = 200, $message = 'Ok', $data = [], $authorization = '')
    {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
        $this->pagination = new stdClass();
        $this->authorization = $authorization;
    }


    public function getStatusCode()
    {
        return $this->statusCode;
    }


    public function getMessage()
    {
        return $this->message;
    }


    public function getData()
    {
        if (count($this->data) <= 0) {
            $this->data = [];
        }
        return $this->data;
    }


    public function getPagination()
    {
        return $this->pagination;
    }


    public function getAuthorization()
    {
        return $this->authorization;
    }


    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        if ($statusCode != 200) {
            $this->setMessage(__('Something went wrong'));

        }
        return $this;
    }


    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }


    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
        return $this;
    }

    public function setAuthorization($authorization)
    {
        $this->authorization = $authorization;
        return $this;
    }

    public function build()
    {

        // if ($this->getData() instanceof LengthAwarePaginator) {
        //     $dataWithPagination = PaginationBuilder::build($this->getData());
        //     $this->setPagination($dataWithPagination['pagination']);
        //     $this->setData($dataWithPagination['data']);
        // } else if ($this->getPagination()) {
        //     $this->setPagination(PaginationBuilder::manipulatePaginationData($this->getPagination()));
        // }

        $response = response([

            'success' => $this->getStatusCode() == 200,
            'message' => $this->getMessage(),
            'data' => [
                'collection' => $this->getData(),
                // 'pagination' => $this->getPagination()
                'pagination' => 10
            ],
            'errors' => new stdClass(),
            'status' => $this->statusCode,
        ]);

        if (strlen($this->getAuthorization()) > 0) {
            $response->withHeaders([
                'Authorization' => $this->getAuthorization()
            ]);
        }
        return $response;

    }

    public function error($message = '', $statusCode = 422)
    {
        $this->setStatusCode($statusCode);

        if (strlen($message) > 0) {
            $this->setMessage($message);
        }

        return $this->build();

    }


    public function success($message, $data = [])
    {
        return $this->setMessage($message)->setData($data)->build();
    }
}