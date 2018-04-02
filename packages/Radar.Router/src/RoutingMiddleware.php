<?php
//@codingStandardsIgnoreFile

namespace Radar\Router;

use Arbiter\Arbiter\ActionFactory;
use Aura\Router\Matcher;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Radar\Action\ActionMiddleware;

class RoutingMiddleware implements Middleware
{
    const ROUTE_ATTRIBUTE = self::class . '::ROUTE_ATTRIBUTE';

    protected $matcher;

    protected $actionFactory;

    protected $failResponder;

    public function __construct(
        Matcher $matcher,
        ActionFactory $actionFactory,
        $failResponder = FailResponder::class
    ) {
        $this->matcher       = $matcher;
        $this->actionFactory = $actionFactory;
        $this->failResponder = $failResponder;
    }


    public function process(Request $request, Handler $handler) : Response
    {
        $route   = $this->matcher->match($request);
        $request = $route
            ? $this->route($route, $request)
            : $this->noRoute($request);

        return $handler->handle($request);
    }

    protected function noRoute(Request $request) : Request
    {
        $action = $this->newFailedAction();
        return $request
            ->withAttribute(self::ROUTE_ATTRIBUTE, false)
            ->withAttribute(ActionMiddleware::ACTION_ATTRIBUTE, $action);
    }

    protected function route(
        Route $route,
        Request $request
    ) : Request {

        foreach ($route->getAttributes() as $key => $val) {
            $request = $request->withAttribute($key, $val);
        }

        $action = $this->actionFromRoute($route);

        return $request
            ->withAttribute(self::ROUTE_ATTRIBUTE, $route)
            ->withAttribute(ActionMiddleware::ACTION_ATTRIBUTE, $action);

    }

    protected function actionFromRoute(Route $route) : Action
    {
        return $this->actionFactory->newInstance(
            $route->getInput(),
            $route->getDomain(),
            $route->getResponder()
        );
    }

    protected function newFailedAction() : Action
    {
        return $this->actionFactory(
            null,
            [ $this->matcher, 'getFailedRoute' ],
            $this->failResponder
        );
    }

}
