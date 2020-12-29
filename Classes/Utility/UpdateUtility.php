<?php
namespace ERecht24\Er24Rechtstexte\Utility;

class UpdateUtility
{

    protected $repositoryUrl = 'https://git.muensmedia.de/api/v4/projects/333/';

    protected $accessToken = 'Sx8mKC_9tDQUyZzs1SmC';

    protected $currentVersion = 0;

    protected $latestVersion = 0;

    protected $updateAvailable = false;


    public function __construct() {
        /** @var \TYPO3\CMS\Core\Package\PackageManager $packageManager */
        $packageManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class);
        $this->currentVersion = $packageManager->getPackage('er24_rechtstexte')->getPackageMetaData()->getVersion();
        self::isUpdateAvailable();
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this);
    }

    public function isUpdateAvailable() {
        $tags = self::performApiRequest('repository/tags/');
        $latest = $this->currentVersion;
        foreach($tags as $tag) {
            if(version_compare($latest, $tag['name'], '<')) {
                $latest = $tag['name'];
                $this->updateAvailable = true;
                $this->latestVersion = $tag['name'];
            }
        }
        return true;
    }

    protected function performApiRequest($requestUrl) {
        $ch = curl_init($this->repositoryUrl . $requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('PRIVATE-TOKEN: ' . $this->accessToken));
        $res = curl_exec($ch);
        return json_decode($res, true);
    }

}
