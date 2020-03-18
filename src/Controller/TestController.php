<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

final class TestController
{
    public function triggersDeprecation(): Response
    {
        \trigger_error("This is a deprecation notice.", E_USER_DEPRECATED);

        return new Response(<<<HTML
<html>
<body>
<h1>Triggers deprecation</h1>
<p>This page should have triggered a deprecation notice.</p>
</body>
</html>
HTML
        );
    }
}
