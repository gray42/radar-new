<?php
// @codingStandardsIgnoreFile

namespace Radar\Action;

use Arbiter\Arbiter\ActionHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;


class ActionMiddleware extends ActionHandler implements Middleware
{
    const ACTION_ATTRIBUTE = self::class . '::ACTION_ATTRIBUTE';

    public function process(Request $request, Handler $handler) : Response
    {
        if ($action = $request->getAttribute(self::ACTION_ATTRIBUTE, false)) {
            $request = $request->withoutAttribute(self::ACTION_ATTRIBUTE);
            return $this->act($action, $request);
        }

        return $handler->handle($request);
    }
}

