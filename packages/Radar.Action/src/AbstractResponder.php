<?php
// @codingStandardsIgnoreFile

namespace Radar\Action;

use Psr\Http\Message\ResponseInterface as Response;

abstract class AbstractResponder
{
    /**
     *
     * The HTTP response.
     *
     * @var Response
     *
     */
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }
}
