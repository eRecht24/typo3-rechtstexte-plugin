<?php

namespace ERecht24\Er24Rechtstexte\UserFunc;

use ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig;
use ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AnalyticsHeaderData
{
    public function process($content, $conf)
    {

        /** @var SiteFinder $siteFinder */
        $siteFinder = GeneralUtility::makeInstance(SiteFinder::class);

        try {
            $siteConfig = $siteFinder->getSiteByPageId($GLOBALS['TSFE']->id);
            /** @var DomainConfig $domainConfig */
            $domainConfig = GeneralUtility::makeInstance(DomainConfigRepository::class)->findOneBy(['domain' => (string)$siteConfig->getBase()]);
            $analytics4Tracking = false;

            if ($domainConfig !== null) {
                /** @var TypoScriptFrontendController $TSFE */
                $GLOBALS['TSFE']->addCacheTags(['er24_analytics_' . $domainConfig->getUid()]);

                if ($domainConfig->getFlagEmbedTracking() === true && $domainConfig->getAnalyticsId() !== '') {
                    if (str_starts_with($domainConfig->getAnalyticsId(), 'G-')) {
                        $analytics4Tracking = true;
                    }

                    if ($domainConfig->getFlagOptOutCode() === true) {
                        $embedCode =
                            '<script>
/**
* Google OutOut Script
*/
var gaProperty = "' . $domainConfig->getAnalyticsId() . '";
var disableStr = \'ga-disable-\' + gaProperty;
if (document.cookie.indexOf(disableStr + \'=true\') > -1) {
    window[disableStr] = true;
}
function gaOptout() {
    document.cookie = disableStr + \'=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/\';
    window[disableStr] = true;
}
</script>';
                    }

                    if ($domainConfig->getFlagUserCentricsEmbed() === true) {
                        $embedCode .=
                            '<script type="text/plain" data-usercentrics="Google Analytics" async src="//www.googletagmanager.com/gtag/js?id=' . $domainConfig->getAnalyticsId() . '"></script>
<script type="text/plain" data-usercentrics="Google Analytics">
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag(\'js\', new Date());
    gtag(\'config\', "' . $domainConfig->getAnalyticsId() . '"';
                    } else {
                        $embedCode .=
                            '<script async src="//www.googletagmanager.com/gtag/js?id=' . $domainConfig->getAnalyticsId() . '"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag(\'js\', new Date());
    gtag(\'config\', "' . $domainConfig->getAnalyticsId() . '"';
                    }

                    if ($analytics4Tracking === false) {
                        $embedCode .= ", { 'anonymize_ip': true }";
                    }

                    $embedCode .= ');</script>';

                    return $embedCode;
                }
            }
        } catch (\Exception) {
        }

        return null;
    }
}
