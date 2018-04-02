<?php
// @codingStandardsIgnoreFile

namespace Radar\Router;

use Aura\Router\Route as AuraRoute;
use Radar\Action;

class Route extends AuraRoute
{
    protected $input = Action\Input::class;

    protected $domain;

    protected $responder = Action\Responder::class;

    public function name($name)
    {
        parent::name($name);

        $input = $this->name . '\\Input';
        if (class_exists($input)) {
            $this->input($input);
        }

        $responder = $this->name . '\\Responder';
        if (class_exists($responder)) {
            $this->responder($responder);
        }

        return $this;
    }

    public function handler($handler)
    {
        $this->domain($handler);
        return $this;
    }

    public function input($input)
    {
        $this->input = $input;
        return $this;
    }

    public function domain($domain)
    {
        $this->domain = $domain;
        return $this;
    }

    public function responder($responder)
    {
        $this->responder = $responder;
        $this->accepts = [];

        $responderAccepts = is_subclass_of(
            $responder,
            Action\ResponderAcceptsInterface::class,
            true
        );

        if ($responderAccepts) {
            $this->accepts($responder::accepts());
        }

        return $this;
    }
}
