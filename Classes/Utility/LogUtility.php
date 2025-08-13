<?php

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

namespace ERecht24\Er24Rechtstexte\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class LogUtility
{
    public static function writeErrorLog(string $message): void
    {
        self::createLogFileIfNotExist();
        $logFilePath = ExtensionManagementUtility::extPath('er24_rechtstexte') . 'Resources/Private/Log/Error.log';

        if (file_exists($logFilePath) === false) {
            $logWriter = fopen($logFilePath, 'a+');
            fclose($logWriter);
        }

        $message = date('d.m.Y H:i') . ': ' . $message . PHP_EOL;

        file_put_contents($logFilePath, $message, FILE_APPEND);

        // Clean up
        $keepLinesUntil = new \DateTime('2 weeks ago');
        $keepLinesUntil = $keepLinesUntil->getTimestamp();

        $content = file_get_contents($logFilePath);
        $lines = explode(PHP_EOL, $content);

        foreach ($lines as $index => $line) {
            $timeStamp = substr($line, 0, 10);
            if (false !== ($lineTstamp = strtotime($timeStamp)) && $lineTstamp < $keepLinesUntil) {
                unset($lines[$index]);
            }
        }

        file_put_contents($logFilePath, implode(PHP_EOL, $lines));

    }

    public static function getErrorLog(): bool|string
    {
        self::createLogFileIfNotExist();
        $logFilePath = ExtensionManagementUtility::extPath('er24_rechtstexte') . 'Resources/Private/Log/Error.log';
        if (file_exists($logFilePath) === false) {
            $logWriter = fopen($logFilePath, 'a+');
            fclose($logWriter);
        }

        return file_get_contents($logFilePath);
    }

    /**
     * Creates the Error.log file and the folder Resources/Private/Log if it does not exist
     */
    private static function createLogFileIfNotExist(): void
    {
        // check if the folder exists
        $logDir = ExtensionManagementUtility::extPath('er24_rechtstexte') . 'Resources/Private/Log/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        // check if the file exists
        $logFilePath = $logDir . 'Error.log';
        if (!file_exists($logFilePath)) {
            file_put_contents($logFilePath, '');
        }
    }
}
