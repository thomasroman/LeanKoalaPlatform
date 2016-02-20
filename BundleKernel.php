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
            new \LeanKoala\Integration\KoalaPingBundle\KoalamonIntegrationKoalaPingBundle(),
            new \LeanKoala\Integration\WebhookBundle\KoalamonIntegrationWebhookBundle(),
            new \LeanKoala\Integration\MissingRequestBundle\KoalamonIntegrationMissingRequestBundle(),
            new \LeanKoala\Integration\GooglePageSpeedBundle\KoalamonIntegrationGooglePageSpeedBundle(),
            new \LeanKoala\Integration\JsErrorScannerBundle\KoalamonIntegrationJsErrorScannerBundle(),
            new \LeanKoala\Integration\SiteInfoBundle\KoalamonIntegrationSiteInfoBundle(),
            new \LeanKoala\Integration\SmokeBundle\KoalamonIntegrationSmokeBundle(),
            new \LeanKoala\Integration\SmokeBasicBundle\KoalamonIntegrationSmokeBasicBundle(),
        ];

        return $bundles;
    }
}