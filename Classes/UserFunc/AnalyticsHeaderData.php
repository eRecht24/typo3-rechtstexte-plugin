<?php
namespace ERecht24\Er24Rechtstexte\UserFunc;

class AnalyticsHeaderData
{
    public function process($content, $conf) {

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Site\SiteFinder::class);

        try {
            $siteConfig = $siteFinder->getSiteByPageId($GLOBALS['TSFE']->id);

            /** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);

            /** @var \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig */
            $domainConfig = $objectManager->get(\ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository::class)->findOneByDomain((string) $siteConfig->getBase());

            $analytics4Tracking = false;

            if($domainConfig !== null) {

                /** @var \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController $TSFE */
                $GLOBALS['TSFE']->addCacheTags(['er24_analytics_'.$domainConfig->getUid()]);

                if($domainConfig->getFlagEmbedTracking() === true && $domainConfig->getAnalyticsId() !== '') {

                    if (substr($domainConfig->getAnalyticsId(),0,2) === 'G-') {
                       $analytics4Tracking = true;
                    }

                    if($domainConfig->getFlagOptOutCode() === true) {
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

                    if($domainConfig->getFlagUserCentricsEmbed() === true) {
                        $embedCode .=
'<script type="text/plain" data-usercentrics="Google Analytics" async src="//www.googletagmanager.com/gtag/js?id='.$domainConfig->getAnalyticsId().'"></script>
<script type="text/plain" data-usercentrics="Google Analytics">
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag(\'js\', new Date());
    gtag(\'config\', "'.$domainConfig->getAnalyticsId().'"';
                    } else {
                        $embedCode .=
'<script async src="//www.googletagmanager.com/gtag/js?id='.$domainConfig->getAnalyticsId().'"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag(\'js\', new Date());
    gtag(\'config\', "'.$domainConfig->getAnalyticsId().'"';
                    }

                    if (false === $analytics4Tracking) {
                        $embedCode .= ', { \'anonymize_ip\': true }';
                    }

                    $embedCode .= ');</script>';

                    return $embedCode;
                }
            }
        } catch(\Exception $e) {}
    }
}
