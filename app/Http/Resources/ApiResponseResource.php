<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    protected $message;
    protected $statusCode;
    protected $meta;

    public function __construct($resource, $message = 'Success', $statusCode = 200, $meta = [])
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->meta = $meta;
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->statusCode >= 200 && $this->statusCode < 300,
            'message' => $this->message,
            'status_code' => $this->statusCode,
            'data' => $this->resource,
            'meta' => array_merge([
                'timestamp' => now()->toISOString(),
                'version' => '1.0.0',
                'endpoint' => $request->fullUrl()
            ], $this->meta),
            'pagination' => $this->when(
                $this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator,
                function () {
                    return [
                        'current_page' => $this->resource->currentPage(),
                        'last_page' => $this->resource->lastPage(),
                        'per_page' => $this->resource->perPage(),
                        'total' => $this->resource->total(),
                        'from' => $this->resource->firstItem(),
                        'to' => $this->resource->lastItem(),
                        'has_more' => $this->resource->hasMorePages()
                    ];
                }
            )
        ];
    }

    /**
     * Create a success response
     */
    public static function success($data, $message = 'Operation completed successfully', $meta = [])
    {
        return new self($data, $message, 200, $meta);
    }

    /**
     * Create an error response
     */
    public static function error($message = 'An error occurred', $statusCode = 500, $data = null, $meta = [])
    {
        return new self($data, $message, $statusCode, $meta);
    }

    /**
     * Create a validation error response
     */
    public static function validation($errors, $message = 'Validation failed')
    {
        return new self(['validation_errors' => $errors], $message, 422);
    }

    /**
     * Create a not found response
     */
    public static function notFound($message = 'Resource not found')
    {
        return new self(null, $message, 404);
    }
}