<?php

namespace LoremUserGenerator\App\Http\Response;

final class BaseHttpResponse implements HttpResponse
{
    /** @var string */
    private $status;
    /** @var array */
    private $data;

    public function __construct(string $status, array $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return array_merge(
            $this->data,
            ['status' => $this->status]
        );
    }
}
