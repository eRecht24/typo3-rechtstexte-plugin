<?php
declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Api;

class ApiResponse
{
    private $data;
    private $code;
    private $success;

    /**
     * ApiResponse constructor.
     * @param int $code
     * @param bool $success
     * @param array $data
     */
    public function __construct(
        int $code,
        bool $success,
        $data = []
    )
    {
        // $data can be null if response does not contain any json
        if (is_null($data)) {
            $data = [];
        }
        $this->data = $data;
        $this->code = $code;
        $this->success = $success;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getData(
        ?string $key = null
    )
    {
        if (!$key)
            return $this->data;

        return $this->data[$key] ?? null;
    }

    /**
     * @param array $data
     */
    public function setData(
        array $data
    ): void
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function addData(
        array $data
    )
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }
}
