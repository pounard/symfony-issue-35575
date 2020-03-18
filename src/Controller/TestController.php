<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestController
{
    const PARAM_TRIGGER = 'trigger';

    public function triggersDeprecation(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        \trigger_error("This is a notice.", E_USER_NOTICE);
        \trigger_error("This is a deprecation notice.", E_USER_DEPRECATED);

        switch ($request->get(self::PARAM_TRIGGER)) {
            case 'error':
                \trigger_error("This is an error", E_USER_ERROR);
                break;

            case 'exception':
                throw new \Exception("This is an exception");

            case 'warning':
                \trigger_error("This is a warning", E_USER_WARNING);
                break;
        }

        $urlWithWarning = $urlGenerator->generate(
            'triggers_deprecation',
            [
                self::PARAM_TRIGGER => 'warning',
            ]
        );

        $urlWithException = $urlGenerator->generate(
            'triggers_deprecation',
            [
                self::PARAM_TRIGGER => 'exception',
            ]
        );

        return new Response(<<<HTML
<html>
<body>
<h1>Triggers deprecation</h1>
<p>
    This page should have triggered a deprecation notice.
</p>
<ul>
    <li>
        <a href="{$urlWithWarning}">
            Click here to raise an additional <code>WARNING</code> that will wake
            up the <code>fingers_crossed</code> handler.
        </a>
    </li>
    <li>
        <a href="{$urlWithException}">
            Click here to raise an additional <code>Exception</code> that will wake
            up the <code>fingers_crossed</code> handler.
        </a>
    </li>
</ul>
<p>
    <em>
        Please note that with a default sane configuration, deprecation may
        wake up the <code>fingers_crossed</code> handler, if its lowest log
        level is <code>INFO</code>.
    </em>
</p>
</body>
</html>
HTML
        );
    }
}
