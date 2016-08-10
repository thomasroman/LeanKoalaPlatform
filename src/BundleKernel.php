<?php
/**
 * Created by PhpStorm.
 * User: nils.langner
 * Date: 20.02.16
 * Time: 07:45
 */

namespace LeanKoala;

class BundleKernel
{
    public static function registerBundles($environment)
    {
        $bundles = [
            new \LeanKoala\CoreBundle\LeanKoalaCoreBundle(),
            new \LeanKoala\Integration\KoalaPingBundle\LeanKoalaIntegrationKoalaPingBundle(),
            new \LeanKoala\Integration\MissingRequestBundle\LeanKoalaIntegrationMissingRequestBundle(),
            new \LeanKoala\Integration\GooglePageSpeedBundle\LeanKoalaIntegrationGooglePageSpeedBundle(),
            new \LeanKoala\Integration\JsErrorScannerBundle\LeanKoalaIntegrationJsErrorScannerBundle(),
            new \LeanKoala\Integration\SiteInfoBundle\LeanKoalaIntegrationSiteInfoBundle(),
            new \LeanKoala\Integration\SmokeBundle\LeanKoalaIntegrationSmokeBundle(),
            new \LeanKoala\Integration\SmokeBasicBundle\LeanKoalaIntegrationSmokeBasicBundle(),
            new \LeanKoala\Integration\ZAProxyBundle\LeanKoalaIntegrationZAProxyBundle(),
            new \LeanKoala\HelpBundle\LeanKoalaHelpBundle(),
        ];

        return $bundles;
    }
}