<?php

declare(strict_types=1);

namespace ERecht24\Er24Rechtstexte\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('erecht24Er24RechtstexteCTypeMigration')]
final class ERecht24Er24RechtstexteCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "ERecht24 Er24Rechtstexte" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "ERecht24 Er24Rechtstexte" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'er24rechtstexte_main' => 'er24rechtstexte_main',
        ];
    }
}
