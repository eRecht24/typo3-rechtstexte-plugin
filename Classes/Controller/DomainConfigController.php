<?php
declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Controller;


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
 * DomainConfigController
 */
class DomainConfigController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * domainConfigRepository
     *
     * @var \ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository
     */
    protected $domainConfigRepository = null;

    /**
     * @param \ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository $domainConfigRepository
     */
    public function injectDomainConfigRepository(\ERecht24\Er24Rechtstexte\Domain\Repository\DomainConfigRepository $domainConfigRepository)
    {
        $this->domainConfigRepository = $domainConfigRepository;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {

        /** @var \TYPO3\CMS\Core\Site\SiteFinder $siteFinder */
        $siteFinder = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Site\SiteFinder::class);
        $allSiteConfigurations = $siteFinder->getAllSites();

        $this->view->assignMultiple([
            'domainConfigs' => $this->domainConfigRepository->findAll(),
            'allSiteConfigurations' => $siteFinder->getAllSites()
        ]);
    }

    /**
     * action show
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @return void
     */
    public function showAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        $this->view->assign('domainConfig', $domainConfig);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
    }

    /**
     * action create
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $newDomainConfig
     * @return void
     */
    public function createAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $newDomainConfig)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->domainConfigRepository->add($newDomainConfig);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("domainConfig")
     * @return void
     */
    public function editAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        $this->view->assign('domainConfig', $domainConfig);
    }

    /**
     * action update
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @return void
     */
    public function updateAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->domainConfigRepository->update($domainConfig);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig
     * @return void
     */
    public function deleteAction(\ERecht24\Er24Rechtstexte\Domain\Model\DomainConfig $domainConfig)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/typo3cms/extensions/extension_builder/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->domainConfigRepository->remove($domainConfig);
        $this->redirect('list');
    }
}
