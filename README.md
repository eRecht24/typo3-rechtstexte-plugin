# eRecht24 TYPO3 Rechtstexte Extension
eRecht24.de is a SaaS, that provides users with legal texts for their websites suh as imprints, privacy policies and others according to the german law.

## What does it do?
This extension allows the easy integration of imprint and privacy policy of eRecht24.

## Usage
### Installation
#### Installation using Composer
The recommended way to install the extension is using [Composer](https://getcomposer.org/).

Run the following command within your Composer based TYPO3 project:
```
composer require erecht24/er24-rechtstexte
```

#### Installation as extension from TYPO3 Extension Repository (TER)
Download and install the [extension](https://extensions.typo3.org/extension/er24_rechtstexte) with the extension manager module.

### Configuration & FAQ
Please use the following manual for initial configuration:
https://www.e-recht24.de/mitglieder/tools/erecht24-rechtstexte-plugin/typo3/

If you want to use the mail bot spam protection, you have to put the following code in your config:
```typoscript
config.spamProtectEmailAddresses = ascii
```

## Contribute
**Pull Requests** are welcome! Please don't forget to add an issue and connect it to your pull requests. This is very helpful to understand what kind of issue the PR is going to solve.
