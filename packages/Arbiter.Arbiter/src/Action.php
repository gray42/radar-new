<?php
// @codingStandardsIgnoreFile

namespace Arbiter\Arbiter;

class Action implements ActionInterface
{
    protected $input;

    protected $domain;

    protected $responder;

    public function __construct($input, $domain, $responder)
    {
        $this->input = $input;
        $this->domain = $domain;
        $this->responder = $responder;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getResponder()
    {
        return $this->responder;
    }
}
