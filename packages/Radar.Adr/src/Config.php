<?php
//@codingStandardsIgnoreFile
/**
 *
 * This file is part of Radar for PHP.
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Radar\Adr;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Arbiter\ActionFactory;
use Aura\Router\RouterContainer as Router;
use Radar\Action\ActionMiddleware;
use Radar\Router\Route;
use Radar\Routing\RoutingMiddleware;
use Relay\RelayBuilder;

/**
 *
 * DI container configuration for Radar classes.
 *
 * @package radar/adr
 *
 */
class Config extends ContainerConfig
{

    const ADR      = Adr::class;
    const RESOLVER = self::class . '::RESOLVER';
    const ROUTER   = Router::class;

    /**
     *
     * Defines params, setters, values, etc. in the Container.
     *
     * @param Container $di The DI container.
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function define(Container $di)
    {
        /**
         * Services
         */
        $di->set(self::ADR, $di->lazyNew(Adr::class));
        $di->set(self::RESOLVER, $di->newResolutionHelper());
        $di->set(self::ROUTER, $di->lazyNew(Router::class));

        /**
         * Aura\Router\Container
         */
        $di->setters[Router::class]['setRouteFactory'] = $di->newFactory(Route::class);

        /**
         * Relay\RelayBuilder
         */
        $di->params[RelayBuilder::class]['resolver'] = $di->lazyGet(self::RESOLVER);

        /**
         * Radar\Adr\Adr
         */
        $di->params[Adr::class]['map'] = $di->lazyGetCall(self::ROUTER, 'getMap');
        $di->params[Adr::class]['rules'] = $di->lazyGetCall(self::ROUTER, 'getRuleIterator');
        $di->params[Adr::class]['relayBuilder'] = $di->lazyNew(RelayBuilder::class);

        /**
         * Radar\Adr\Handler\ActionHandler
         */
        $di->params[ActionMiddleware::class]['resolver'] = $di->lazyGet(self::RESOLVER);

        /**
         * Radar\Adr\Handler\RoutingHandler
         */
        $di->params[RoutingMiddleware::class]['matcher'] = $di->lazyGetCall(self::ROUTER, 'getMatcher');
        $di->params[RoutingMiddleware::class]['actionFactory'] = $di->lazyNew(ActionFactory::class);

    }

}
