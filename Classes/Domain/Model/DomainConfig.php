<?php

declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

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
class DomainConfig extends AbstractEntity
{
    protected string $domain = '';
    protected string $apiKey = '';
    protected int $imprintSource = 1;
    protected int $siteLanguage = 0;
    protected string $clientId = '';
    protected string $clientSecret = '';
    protected string $imprintDe = '';
    protected string $imprintDeLocal = '';
    protected string $imprintEnLocal = '';
    protected int $imprintDeTstamp = 0;
    protected string $imprintEn = '';
    protected int $imprintEnTstamp = 0;
    protected int $privacySource = 1;
    protected string $privacyDe = '';
    protected string $privacyDeLocal = '';
    protected int $privacyDeTstamp = 0;
    protected string $privacyEn = '';
    protected string $privacyEnLocal = '';
    protected int $privacyEnTstamp = 0;
    protected int $socialSource = 1;
    protected string $socialDe = '';
    protected string $socialDeLocal = '';
    protected int $socialDeTstamp = 0;
    protected string $socialEn = '';
    protected string $socialEnLocal = '';
    protected int $socialEnTstamp = 0;
    protected string $analyticsId = '';
    protected bool $flagEmbedTracking = false;
    protected bool $flagUserCentricsEmbed = false;
    protected bool $flagOptOutCode = false;
    protected int $rootPid = 0;
    protected string $siteConfigName = '';

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function getImprintSource(): int
    {
        return $this->imprintSource;
    }

    public function setImprintSource(int $imprintSource): void
    {
        $this->imprintSource = $imprintSource;
    }

    public function getImprintDe(): string
    {
        return $this->imprintDe;
    }

    public function setImprintDe(string $imprintDe): void
    {
        $this->imprintDe = $imprintDe;
    }

    public function getImprintDeTstamp(): int
    {
        return $this->imprintDeTstamp;
    }

    public function setImprintDeTstamp(int $imprintDeTstamp): void
    {
        $this->imprintDeTstamp = $imprintDeTstamp;
    }

    public function getImprintEn(): string
    {
        return $this->imprintEn;
    }

    public function setImprintEn(string $imprintEn): void
    {
        $this->imprintEn = $imprintEn;
    }

    public function getImprintEnTstamp(): int
    {
        return $this->imprintEnTstamp;
    }

    public function setImprintEnTstamp(int $imprintEnTstamp): void
    {
        $this->imprintEnTstamp = $imprintEnTstamp;
    }

    public function getPrivacySource(): int
    {
        return $this->privacySource;
    }

    public function setPrivacySource(int $privacySource): void
    {
        $this->privacySource = $privacySource;
    }

    public function getPrivacyDe(): string
    {
        return $this->privacyDe;
    }

    public function setPrivacyDe(string $privacyDe): void
    {
        $this->privacyDe = $privacyDe;
    }

    public function getPrivacyDeTstamp(): int
    {
        return $this->privacyDeTstamp;
    }

    public function setPrivacyDeTstamp(int $privacyDeTstamp): void
    {
        $this->privacyDeTstamp = $privacyDeTstamp;
    }

    public function getPrivacyEn(): string
    {
        return $this->privacyEn;
    }

    public function setPrivacyEn(string $privacyEn): void
    {
        $this->privacyEn = $privacyEn;
    }

    public function getPrivacyEnTstamp(): int
    {
        return $this->privacyEnTstamp;
    }

    public function setPrivacyEnTstamp(int $privacyEnTstamp): void
    {
        $this->privacyEnTstamp = $privacyEnTstamp;
    }

    public function getSocialSource(): int
    {
        return $this->socialSource;
    }

    public function setSocialSource(int $socialSource): void
    {
        $this->socialSource = $socialSource;
    }

    public function getSocialDe(): string
    {
        return $this->socialDe;
    }

    public function setSocialDe(string $socialDe): void
    {
        $this->socialDe = $socialDe;
    }

    public function getSocialDeTstamp(): int
    {
        return $this->socialDeTstamp;
    }

    public function setSocialDeTstamp(int $socialDeTstamp): void
    {
        $this->socialDeTstamp = $socialDeTstamp;
    }

    public function getSocialEn(): string
    {
        return $this->socialEn;
    }

    public function setSocialEn(string $socialEn): void
    {
        $this->socialEn = $socialEn;
    }

    public function getSocialEnTstamp(): int
    {
        return $this->socialEnTstamp;
    }

    public function setSocialEnTstamp(int $socialEnTstamp): void
    {
        $this->socialEnTstamp = $socialEnTstamp;
    }

    public function getAnalyticsId(): string
    {
        return $this->analyticsId;
    }

    public function setAnalyticsId(string $analyticsId): void
    {
        $this->analyticsId = $analyticsId;
    }

    public function getFlagEmbedTracking(): bool
    {
        return $this->flagEmbedTracking;
    }

    public function setFlagEmbedTracking(bool $flagEmbedTracking): void
    {
        $this->flagEmbedTracking = $flagEmbedTracking;
    }

    public function isFlagEmbedTracking(): bool
    {
        return $this->flagEmbedTracking;
    }

    public function getFlagUserCentricsEmbed(): bool
    {
        return $this->flagUserCentricsEmbed;
    }

    public function setFlagUserCentricsEmbed(bool $flagUserCentricsEmbed): void
    {
        $this->flagUserCentricsEmbed = $flagUserCentricsEmbed;
    }

    public function isFlagUserCentricsEmbed(): bool
    {
        return $this->flagUserCentricsEmbed;
    }

    public function getFlagOptOutCode(): bool
    {
        return $this->flagOptOutCode;
    }

    public function setFlagOptOutCode(bool $flagOptOutCode): void
    {
        $this->flagOptOutCode = $flagOptOutCode;
    }

    public function isFlagOptOutCode(): bool
    {
        return $this->flagOptOutCode;
    }

    public function getRootPid(): int
    {
        return $this->rootPid;
    }

    public function setRootPid(int $rootPid): void
    {
        $this->rootPid = $rootPid;
    }

    public function getSiteConfigName(): string
    {
        return $this->siteConfigName;
    }

    public function setSiteConfigName(string $siteConfigName): void
    {
        $this->siteConfigName = $siteConfigName;
    }

    public function getSiteLanguage(): int
    {
        return $this->siteLanguage;
    }

    public function setSiteLanguage(int $siteLanguage): void
    {
        $this->siteLanguage = $siteLanguage;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(?string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(?string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    public function getImprintDeLocal(): string
    {
        return $this->imprintDeLocal;
    }

    public function setImprintDeLocal(string $imprintDeLocal): void
    {
        $this->imprintDeLocal = $imprintDeLocal;
    }

    public function getImprintEnLocal(): string
    {
        return $this->imprintEnLocal;
    }

    public function setImprintEnLocal(string $imprintEnLocal): void
    {
        $this->imprintEnLocal = $imprintEnLocal;
    }

    public function getSocialDeLocal(): string
    {
        return $this->socialDeLocal;
    }

    public function setSocialDeLocal(string $socialDeLocal): void
    {
        $this->socialDeLocal = $socialDeLocal;
    }

    public function getSocialEnLocal(): string
    {
        return $this->socialEnLocal;
    }

    public function setSocialEnLocal(string $socialEnLocal): void
    {
        $this->socialEnLocal = $socialEnLocal;
    }

    public function getPrivacyEnLocal(): string
    {
        return $this->privacyEnLocal;
    }

    public function setPrivacyEnLocal(string $privacyEnLocal): void
    {
        $this->privacyEnLocal = $privacyEnLocal;
    }

    public function getPrivacyDeLocal(): string
    {
        return $this->privacyDeLocal;
    }

    public function setPrivacyDeLocal(string $privacyDeLocal): void
    {
        $this->privacyDeLocal = $privacyDeLocal;
    }
}
