<?php
declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Domain\Model;


/***
 *
 * This file is part of the "eRecht24 Rechtstexte Extension" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/
/**
 * DomainConfig
 */
class DomainConfig extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * domain
     *
     * @var string
     */
    protected $domain = '';

    /**
     * apiKey
     *
     * @var string
     */
    protected $apiKey = '';

    /**
     * imprintSource
     *
     * @var int
     */
    protected $imprintSource = 1;

    /**
     * @var int
     */
    protected $siteLanguage = 0;

    /**
     * @var string
     */
    protected $clientId = '';

    /**
     * @var string
     */
    protected $clientSecret = '';

    /**
     * imprintDe
     *
     * @var string
     */
    protected $imprintDe = '';

    /**
     * @var string
     */
    protected $imprintDeLocal = '';

    /**
     * @var string
     */
    protected $imprintEnLocal = '';

    /**
     * imprintDeTstamp
     * @var integer
     */
    protected $imprintDeTstamp = 0;

    /**
     * imprintEn
     *
     * @var string
     */
    protected $imprintEn = '';

    /**
     * imprintEnTstamp
     * @var integer
     */
    protected $imprintEnTstamp = 0;

    /**
     * privacySource
     *
     * @var int
     */
    protected $privacySource = 1;

    /**
     * privacyDe
     *
     * @var string
     */
    protected $privacyDe = '';

    /**
     * privacyDe
     *
     * @var string
     */
    protected $privacyDeLocal = '';

    /**
     * privacyDeTstamp
     *
     * @var int
     */
    protected $privacyDeTstamp = 0;

    /**
     * privacyEn
     *
     * @var string
     */
    protected $privacyEn = '';

    /**
     * privacyEn
     *
     * @var string
     */
    protected $privacyEnLocal = '';

    /**
     * privacyEnTstamp
     *
     * @var int
     */
    protected $privacyEnTstamp = 0;

    /**
     * socialSource
     * @var boolean
     */
    protected $socialSource = true;

    /**
     * socialDe
     *
     * @var string
     */
    protected $socialDe = '';

    /**
     * socialDe
     *
     * @var string
     */
    protected $socialDeLocal = '';

    /**
     * socialDeTstamp
     *
     * @var int
     */
    protected $socialDeTstamp = 0;

    /**
     * socialEn
     *
     * @var string
     */
    protected $socialEn = '';

    /**
     * socialEn
     *
     * @var string
     */
    protected $socialEnLocal = '';

    /**
     * socialEnTstamp
     *
     * @var int
     */
    protected $socialEnTstamp = 0;

    /**
     * analyticsId
     *
     * @var string
     */
    protected $analyticsId = '';

    /**
     * flagEmbedTracking
     *
     * @var bool
     */
    protected $flagEmbedTracking = false;

    /**
     * flagUserCentricsEmbed
     *
     * @var bool
     */
    protected $flagUserCentricsEmbed = false;

    /**
     * flagOptOutCode
     *
     * @var bool
     */
    protected $flagOptOutCode = false;

    /**
     * rootPid
     *
     * @var int
     */
    protected $rootPid = 0;

    /**
     * siteConfigName
     *
     * @var string
     */
    protected $siteConfigName = '';

    /**
     * Returns the domain
     *
     * @return string $domain
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Sets the domain
     *
     * @param string $domain
     * @return void
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Returns the apiKey
     *
     * @return string $apiKey
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets the apiKey
     *
     * @param string $apiKey
     * @return void
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Returns the imprintSource
     *
     * @return int $imprintSource
     */
    public function getImprintSource()
    {
        return $this->imprintSource;
    }

    /**
     * Sets the imprintSource
     *
     * @param int $imprintSource
     * @return void
     */
    public function setImprintSource($imprintSource)
    {
        $this->imprintSource = $imprintSource;
    }

    /**
     * Returns the imprintDe
     *
     * @return string $imprintDe
     */
    public function getImprintDe()
    {
        return $this->imprintDe;
    }

    /**
     * Sets the imprintDe
     *
     * @param string $imprintDe
     * @return void
     */
    public function setImprintDe($imprintDe)
    {
        $this->imprintDe = $imprintDe;
    }

    /**
     * Returns the imprintDeTstamp
     *
     * @return integer $imprintDeTstamp
     */
    public function getImprintDeTstamp()
    {
        return $this->imprintDeTstamp;
    }

    /**
     * Sets the imprintDeTstamp
     *
     * @param integer $imprintDeTstamp
     * @return void
     */
    public function setImprintDeTstamp(int $imprintDeTstamp)
    {
        $this->imprintDeTstamp = $imprintDeTstamp;
    }

    /**
     * Returns the imprintEn
     *
     * @return string $imprintEn
     */
    public function getImprintEn()
    {
        return $this->imprintEn;
    }

    /**
     * Sets the imprintEn
     *
     * @param string $imprintEn
     * @return void
     */
    public function setImprintEn($imprintEn)
    {
        $this->imprintEn = $imprintEn;
    }

    /**
     * Returns the imprintEnTstamp
     *
     * @return integer $imprintEnTstamp
     */
    public function getImprintEnTstamp()
    {
        return $this->imprintEnTstamp;
    }

    /**
     * Sets the imprintEnTstamp
     *
     * @param integer $imprintEnTstamp
     * @return void
     */
    public function setImprintEnTstamp(int $imprintEnTstamp)
    {
        $this->imprintEnTstamp = $imprintEnTstamp;
    }

    /**
     * Returns the privacySource
     *
     * @return int $privacySource
     */
    public function getPrivacySource()
    {
        return $this->privacySource;
    }

    /**
     * Sets the privacySource
     *
     * @param int $privacySource
     * @return void
     */
    public function setPrivacySource($privacySource)
    {
        $this->privacySource = $privacySource;
    }

    /**
     * Returns the privacyDe
     *
     * @return string $privacyDe
     */
    public function getPrivacyDe()
    {
        return $this->privacyDe;
    }

    /**
     * Sets the privacyDe
     *
     * @param string $privacyDe
     * @return void
     */
    public function setPrivacyDe($privacyDe)
    {
        $this->privacyDe = $privacyDe;
    }

    /**
     * Returns the privacyDeTstamp
     *
     * @return int $privacyDeTstamp
     */
    public function getPrivacyDeTstamp()
    {
        return $this->privacyDeTstamp;
    }

    /**
     * Sets the privacyDeTstamp
     *
     * @param int $privacyDeTstamp
     * @return void
     */
    public function setPrivacyDeTstamp(int $privacyDeTstamp)
    {
        $this->privacyDeTstamp = $privacyDeTstamp;
    }

    /**
     * Returns the privacyEn
     *
     * @return string $privacyEn
     */
    public function getPrivacyEn()
    {
        return $this->privacyEn;
    }

    /**
     * Sets the privacyEn
     *
     * @param string $privacyEn
     * @return void
     */
    public function setPrivacyEn($privacyEn)
    {
        $this->privacyEn = $privacyEn;
    }

    /**
     * Returns the privacyEnTstamp
     *
     * @return int $privacyEnTstamp
     */
    public function getPrivacyEnTstamp()
    {
        return $this->privacyEnTstamp;
    }

    /**
     * Sets the privacyEnTstamp
     *
     * @param int $privacyEnTstamp
     * @return void
     */
    public function setPrivacyEnTstamp(int $privacyEnTstamp)
    {
        $this->privacyEnTstamp = $privacyEnTstamp;
    }

    /**
     * Returns the socialSource
     *
     * @return boolean $socialSource
     */
    public function getSocialSource()
    {
        return $this->socialSource;
    }

    /**
     * Sets the socialSource
     *
     * @param boolean $socialSource
     * @return void
     */
    public function setSocialSource(bool $socialSource)
    {
        $this->socialSource = $socialSource;
    }

    /**
     * Returns the socialDe
     *
     * @return string $socialDe
     */
    public function getSocialDe()
    {
        return $this->socialDe;
    }

    /**
     * Sets the socialDe
     *
     * @param string $socialDe
     * @return void
     */
    public function setSocialDe($socialDe)
    {
        $this->socialDe = $socialDe;
    }

    /**
     * Returns the socialDeTstamp
     *
     * @return int $socialDeTstamp
     */
    public function getSocialDeTstamp()
    {
        return $this->socialDeTstamp;
    }

    /**
     * Sets the socialDeTstamp
     *
     * @param int $socialDeTstamp
     * @return void
     */
    public function setSocialDeTstamp(int $socialDeTstamp)
    {
        $this->socialDeTstamp = $socialDeTstamp;
    }

    /**
     * Returns the socialEn
     *
     * @return string $socialEn
     */
    public function getSocialEn()
    {
        return $this->socialEn;
    }

    /**
     * Sets the socialEn
     *
     * @param string $socialEn
     * @return void
     */
    public function setSocialEn($socialEn)
    {
        $this->socialEn = $socialEn;
    }

    /**
     * Returns the socialEnTstamp
     *
     * @return int $socialEnTstamp
     */
    public function getSocialEnTstamp()
    {
        return $this->socialEnTstamp;
    }

    /**
     * Sets the socialEnTstamp
     *
     * @param int $socialEnTstamp
     * @return void
     */
    public function setSocialEnTstamp(int $socialEnTstamp)
    {
        $this->socialEnTstamp = $socialEnTstamp;
    }

    /**
     * Returns the analyticsId
     *
     * @return string $analyticsId
     */
    public function getAnalyticsId()
    {
        return $this->analyticsId;
    }

    /**
     * Sets the analyticsId
     *
     * @param string $analyticsId
     * @return void
     */
    public function setAnalyticsId($analyticsId)
    {
        $this->analyticsId = $analyticsId;
    }

    /**
     * Returns the flagEmbedTracking
     *
     * @return bool $flagEmbedTracking
     */
    public function getFlagEmbedTracking()
    {
        return $this->flagEmbedTracking;
    }

    /**
     * Sets the flagEmbedTracking
     *
     * @param bool $flagEmbedTracking
     * @return void
     */
    public function setFlagEmbedTracking($flagEmbedTracking)
    {
        $this->flagEmbedTracking = $flagEmbedTracking;
    }

    /**
     * Returns the boolean state of flagEmbedTracking
     *
     * @return bool
     */
    public function isFlagEmbedTracking()
    {
        return $this->flagEmbedTracking;
    }

    /**
     * Returns the flagUserCentricsEmbed
     *
     * @return bool $flagUserCentricsEmbed
     */
    public function getFlagUserCentricsEmbed()
    {
        return $this->flagUserCentricsEmbed;
    }

    /**
     * Sets the flagUserCentricsEmbed
     *
     * @param bool $flagUserCentricsEmbed
     * @return void
     */
    public function setFlagUserCentricsEmbed($flagUserCentricsEmbed)
    {
        $this->flagUserCentricsEmbed = $flagUserCentricsEmbed;
    }

    /**
     * Returns the boolean state of flagUserCentricsEmbed
     *
     * @return bool
     */
    public function isFlagUserCentricsEmbed()
    {
        return $this->flagUserCentricsEmbed;
    }

    /**
     * Returns the flagOptOutCode
     *
     * @return bool $flagOptOutCode
     */
    public function getFlagOptOutCode()
    {
        return $this->flagOptOutCode;
    }

    /**
     * Sets the flagOptOutCode
     *
     * @param bool $flagOptOutCode
     * @return void
     */
    public function setFlagOptOutCode($flagOptOutCode)
    {
        $this->flagOptOutCode = $flagOptOutCode;
    }

    /**
     * Returns the boolean state of flagOptOutCode
     *
     * @return bool
     */
    public function isFlagOptOutCode()
    {
        return $this->flagOptOutCode;
    }

    /**
     * Returns the rootPid
     *
     * @return int $rootPid
     */
    public function getRootPid()
    {
        return $this->rootPid;
    }

    /**
     * Sets the rootPid
     *
     * @param int $rootPid
     * @return void
     */
    public function setRootPid($rootPid)
    {
        $this->rootPid = $rootPid;
    }

    /**
     * Returns the siteConfigName
     *
     * @return string $siteConfigName
     */
    public function getSiteConfigName()
    {
        return $this->siteConfigName;
    }

    /**
     * Sets the siteConfigName
     *
     * @param string $siteConfigName
     * @return void
     */
    public function setSiteConfigName($siteConfigName)
    {
        $this->siteConfigName = $siteConfigName;
    }

    /**
     * @return int
     */
    public function getSiteLanguage(): int
    {
        return $this->siteLanguage;
    }

    /**
     * @param int $siteLanguage
     */
    public function setSiteLanguage(int $siteLanguage): void
    {
        $this->siteLanguage = $siteLanguage;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string|null $clientId
     */
    public function setClientId(?string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string|null $clientSecret
     */
    public function setClientSecret(?string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getImprintDeLocal(): string
    {
        return $this->imprintDeLocal;
    }

    /**
     * @param string $imprintDeLocal
     */
    public function setImprintDeLocal(string $imprintDeLocal): void
    {
        $this->imprintDeLocal = $imprintDeLocal;
    }

    /**
     * @return string
     */
    public function getImprintEnLocal(): string
    {
        return $this->imprintEnLocal;
    }

    /**
     * @param string $imprintEnLocal
     */
    public function setImprintEnLocal(string $imprintEnLocal): void
    {
        $this->imprintEnLocal = $imprintEnLocal;
    }

    /**
     * @return string
     */
    public function getSocialDeLocal(): string
    {
        return $this->socialDeLocal;
    }

    /**
     * @param string $socialDeLocal
     */
    public function setSocialDeLocal(string $socialDeLocal): void
    {
        $this->socialDeLocal = $socialDeLocal;
    }

    /**
     * @return string
     */
    public function getSocialEnLocal(): string
    {
        return $this->socialEnLocal;
    }

    /**
     * @param string $socialEnLocal
     */
    public function setSocialEnLocal(string $socialEnLocal): void
    {
        $this->socialEnLocal = $socialEnLocal;
    }

    /**
     * @return string
     */
    public function getPrivacyEnLocal(): string
    {
        return $this->privacyEnLocal;
    }

    /**
     * @param string $privacyEnLocal
     */
    public function setPrivacyEnLocal(string $privacyEnLocal): void
    {
        $this->privacyEnLocal = $privacyEnLocal;
    }

    /**
     * @return string
     */
    public function getPrivacyDeLocal(): string
    {
        return $this->privacyDeLocal;
    }

    /**
     * @param string $privacyDeLocal
     */
    public function setPrivacyDeLocal(string $privacyDeLocal): void
    {
        $this->privacyDeLocal = $privacyDeLocal;
    }
}
