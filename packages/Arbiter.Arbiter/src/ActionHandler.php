<?php
//@codingStandardsIgnoreFile

namespace Arbiter\Arbiter;

use Psr\Container\ContainerInterface as Container;

class ActionHandler
{
    protected $container;

    public function __construct(Container $container = null)
    {
        $this->container = $container;
    }

    public function act(Action $action, $context)
    {
        $responder = $this->resolve($action->getResponder());

        if (! $responder) {
            throw new Exception('Could not resolve responder for action.');
        }

        $domain = $this->resolve($action->getDomain());

        if (! $domain) {
            return $responder($context);
        }

        $params = [];
        $input = $this->resolve($action->getInput());
        if ($input) {
            $params = (array) $input($context);
        }

        $payload = call_user_func_array($domain, $params);
        return $responder($context, $payload);
    }

    protected function resolve($spec)
    {
        if (! $spec) {
            return null;
        }

        if (! $this->container) {
            return $spec;
        }

        if (is_string($spec)) {
            return $this->container->get($spec);
        }

        if (is_array($spec) && is_string($spec[0])) {
            $spec[0] = $this->container->get($spec[0]);
        }

        return $spec;
    }
}
