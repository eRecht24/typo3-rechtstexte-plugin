<?php
namespace ERecht24\Er24Rechtstexte\Utility;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extensionmanager\Service\ExtensionManagementService;
use TYPO3\CMS\Extensionmanager\Utility\FileHandlingUtility;

class UpdateUtility
{

    /**
     * @var string
     */
    const REPOSITORY_URL = 'https://git.muensmedia.de/api/v4/projects/333/';

    /**
     * @var string
     */
    const ACCESS_TOKEN = 'ybxPeRrDixBXRuaGY46w';

    /**
     * @var int|string
     */
    public $currentVersion = 0;

    /**
     * @var bool
     */
    public $composeMode = false;

    /**
     * @var int
     */
    public $latestVersion = 0;

    /**
     * @var bool
     */
    public $updateAvailable = false;

    /**
     * @var ExtensionManagementService
     */
    protected $managementService = null;

    /**
     * @var FileHandlingUtility
     */
    protected $fileHandlingUtility = null;


    public function __construct() {
        /** @var \TYPO3\CMS\Core\Package\PackageManager $packageManager */
        $packageManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Package\PackageManager::class);
        $this->currentVersion = $packageManager->getPackage('er24_rechtstexte')->getPackageMetaData()->getVersion();
        $this->composeMode = \TYPO3\CMS\Core\Core\Environment::isComposerMode();
        self::isUpdateAvailable();
    }

    /**
     * @return bool
     */
    public function isUpdateAvailable() {

        $apiRes = self::performApiRequest('repository/tags/');

        if($apiRes === false) {
            LogUtility::writeErrorLog('API Connection to GIT Repository failed. ' . $this->latestVersion);
            return false;
        }

        $tags = json_decode(self::performApiRequest('repository/tags/'), true);
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

    /**
     * @return bool
     * @throws \TYPO3\CMS\Core\Package\Exception\InvalidPackageKeyException
     * @throws \TYPO3\CMS\Core\Package\Exception\InvalidPackageManifestException
     * @throws \TYPO3\CMS\Core\Package\Exception\InvalidPackagePathException
     * @throws \TYPO3\CMS\Core\Package\Exception\InvalidPackageStateException
     * @throws \TYPO3\CMS\Extensionmanager\Exception\ExtensionManagerException
     */
    public function performSelfUpdate() {

        $extensionKey = 'er24_rechtstexte';

        if($this->composeMode === true) {
            throw new \Exception('The system is runnning in composer mode. This function should never have been called', 1607942004);
        }

        $this->managementService = GeneralUtility::makeInstance(ExtensionManagementService::class);
        $this->fileHandlingUtility = GeneralUtility::makeInstance(FileHandlingUtility::class);

        $apiRes = $this->performApiRequest('repository/archive.zip?sha='.$this->latestVersion);

        if($apiRes === false) {
            LogUtility::writeErrorLog('Selfupdate fehlgeschlagen: cURL Error bei Download von Tag ' . $this->latestVersion);
            return false;
        }

        $tempFile = \TYPO3\CMS\Core\Utility\GeneralUtility::tempnam('erecht24update','.zip');
        $tempWriter = fopen($tempFile, 'w');
        fwrite($tempWriter,$apiRes);
        fclose($tempWriter);

        $backupPath = Environment::getVarPath() . '/transient/erecht24backup' . substr(sha1('er24_rechtstexte' . microtime()), 0, 7) . '/';
        $prePathNewVersion = Environment::getVarPath() . '/transient/erecht24update' . substr(sha1('er24_rechtstexte' . microtime()), 0, 7) . '/';

        GeneralUtility::mkdir($prePathNewVersion);
//        GeneralUtility::mkdir($backupPath);
//        GeneralUtility::copyDirectory(
//            $this->fileHandlingUtility->getExtensionDir($extensionKey),
//            $backupPath
//        );

        $zip = new \ZipArchive();
        $zip->open($tempFile);
        $zip->extractTo($prePathNewVersion);
        $zip->close();
        unset($zip);

        GeneralUtility::unlink_tempfile($tempFile);

        $extensionUpdateZipPath = $prePathNewVersion . 'er24_rechtstexte.zip';
        $extensionUpdateZip = new \ZipArchive();
        $extensionUpdateZip->open($extensionUpdateZipPath, \ZipArchive::CREATE);

        $updatePackagePath = $prePathNewVersion . GeneralUtility::get_dirs($prePathNewVersion)[0];

        $source = realpath($updatePackagePath . '/');

        if (is_dir($source) === true) {
            $iterator = new RecursiveDirectoryIterator($source);
            // skip dot files while iterating
            $iterator->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {
                $file = realpath($file);
                if (is_dir($file) === true) {
                    $extensionUpdateZip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $extensionUpdateZip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }

        $extensionUpdateZip->close();

        $this->fileHandlingUtility->unzipExtensionFromFile($extensionUpdateZipPath, 'er24_rechtstexte');
        $this->managementService->reloadPackageInformation($extensionKey);
        $extension = $this->managementService->getExtension($extensionKey);

        GeneralUtility::rmdir($prePathNewVersion, true);

        return is_array($this->managementService->installExtension($extension));

    }

    /**
     * @param $requestUrl
     * @return bool|string
     */
    protected function performApiRequest($requestUrl) {
        $ch = curl_init(self::REPOSITORY_URL . $requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('PRIVATE-TOKEN: ' . self::ACCESS_TOKEN));
        $res = curl_exec($ch);
        if(curl_getinfo($ch, CURLINFO_RESPONSE_CODE) !== 200) {
            return false;
        }
        return $res;
    }

}
