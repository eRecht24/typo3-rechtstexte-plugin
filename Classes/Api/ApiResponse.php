<?php

declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Api;

class ApiResponse
{
    private $data;

    /**
     * ApiResponse constructor.
     */
    public function __construct(
        private readonly int $code,
        private readonly bool $success,
        array $data = []
    ) {
        // $data can be null if response does not contain any json
        if (is_null($data)) {
            $data = [];
        }

        $this->data = $data;
    }

    public function getData(
        ?string $key = null
    ): mixed {
        if (!$key) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
    }

    public function setData(
        array $data
    ): void {
        $this->data = $data;
    }

    public function addData(
        array $data
    ): void {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }
}
