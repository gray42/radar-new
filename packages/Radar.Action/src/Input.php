<?php
// @codingStandardsIgnoreFile

namespace Radar\Action;

use Psr\Http\Message\ServerRequestInterface as Request;

class Input
{
    public function __invoke(Request $request)
    {
        return [
            array_replace(
                (array) $request->getQueryParams(),
                (array) $request->getParsedBody(),
                (array) $request->getUploadedFiles(),
                (array) $request->getCookieParams(),
                (array) $request->getAttributes()
            )
        ];
    }
}
