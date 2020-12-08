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
    protected $imprintSource = 0;

    /**
     * imprintDe
     * 
     * @var string
     */
    protected $imprintDe = '';

    /**
     * imprintDeTstamp
     * 
     * @var \DateTime
     */
    protected $imprintDeTstamp = null;

    /**
     * imprintEn
     * 
     * @var string
     */
    protected $imprintEn = '';

    /**
     * imprintEnTstamp
     * 
     * @var \DateTime
     */
    protected $imprintEnTstamp = null;

    /**
     * privacySource
     * 
     * @var int
     */
    protected $privacySource = 0;

    /**
     * privacyDe
     * 
     * @var string
     */
    protected $privacyDe = '';

    /**
     * privacyDeTstamp
     * 
     * @var \DateTime
     */
    protected $privacyDeTstamp = null;

    /**
     * privacyEn
     * 
     * @var string
     */
    protected $privacyEn = '';

    /**
     * privacyEnTstamp
     * 
     * @var \DateTime
     */
    protected $privacyEnTstamp = null;

    /**
     * socialSource
     * 
     * @var int
     */
    protected $socialSource = 0;

    /**
     * socialDe
     * 
     * @var string
     */
    protected $socialDe = '';

    /**
     * socialDeTstamp
     * 
     * @var \DateTime
     */
    protected $socialDeTstamp = null;

    /**
     * socialEn
     * 
     * @var string
     */
    protected $socialEn = '';

    /**
     * socialEnTstamp
     * 
     * @var \DateTime
     */
    protected $socialEnTstamp = null;

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
     * @param string $imprintSource
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
     * @return \DateTime $imprintDeTstamp
     */
    public function getImprintDeTstamp()
    {
        return $this->imprintDeTstamp;
    }

    /**
     * Sets the imprintDeTstamp
     * 
     * @param \DateTime $imprintDeTstamp
     * @return void
     */
    public function setImprintDeTstamp(\DateTime $imprintDeTstamp)
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
     * @return \DateTime $imprintEnTstamp
     */
    public function getImprintEnTstamp()
    {
        return $this->imprintEnTstamp;
    }

    /**
     * Sets the imprintEnTstamp
     * 
     * @param \DateTime $imprintEnTstamp
     * @return void
     */
    public function setImprintEnTstamp(\DateTime $imprintEnTstamp)
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
     * @return \DateTime $privacyDeTstamp
     */
    public function getPrivacyDeTstamp()
    {
        return $this->privacyDeTstamp;
    }

    /**
     * Sets the privacyDeTstamp
     * 
     * @param \DateTime $privacyDeTstamp
     * @return void
     */
    public function setPrivacyDeTstamp(\DateTime $privacyDeTstamp)
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
     * @return \DateTime $privacyEnTstamp
     */
    public function getPrivacyEnTstamp()
    {
        return $this->privacyEnTstamp;
    }

    /**
     * Sets the privacyEnTstamp
     * 
     * @param \DateTime $privacyEnTstamp
     * @return void
     */
    public function setPrivacyEnTstamp(\DateTime $privacyEnTstamp)
    {
        $this->privacyEnTstamp = $privacyEnTstamp;
    }

    /**
     * Returns the socialSource
     * 
     * @return int $socialSource
     */
    public function getSocialSource()
    {
        return $this->socialSource;
    }

    /**
     * Sets the socialSource
     * 
     * @param int $socialSource
     * @return void
     */
    public function setSocialSource($socialSource)
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
     * @return \DateTime $socialDeTstamp
     */
    public function getSocialDeTstamp()
    {
        return $this->socialDeTstamp;
    }

    /**
     * Sets the socialDeTstamp
     * 
     * @param \DateTime $socialDeTstamp
     * @return void
     */
    public function setSocialDeTstamp(\DateTime $socialDeTstamp)
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
     * @return \DateTime $socialEnTstamp
     */
    public function getSocialEnTstamp()
    {
        return $this->socialEnTstamp;
    }

    /**
     * Sets the socialEnTstamp
     * 
     * @param \DateTime $socialEnTstamp
     * @return void
     */
    public function setSocialEnTstamp(\DateTime $socialEnTstamp)
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
}
