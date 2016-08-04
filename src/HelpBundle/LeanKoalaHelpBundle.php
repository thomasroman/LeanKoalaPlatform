<?php

namespace LeanKoala\HelpBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LeanKoalaHelpBundle extends Bundle
{
    public function getParent()
    {
        return 'KoalamonComPlattformHelpBundle';
    }
}
