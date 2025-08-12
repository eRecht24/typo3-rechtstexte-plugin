import AjaxRequest from '@typo3/core/ajax/ajax-request.js';
import Severity from '@typo3/backend/severity.js';
import Modal from '@typo3/backend/modal.js';
import Notification from '@typo3/backend/notification.js';

const eRecht24Module = {
  languagePossibleUrls: [],
  domainConfigId: null,
  currentImprintSource: 0,
  trackingEnabled: false,
  currentSources: {
    imprint: 0,
    privacy: 0,
    social: 0
  },

  showLoader() {
    document.querySelectorAll('.ui-block').forEach(el => el.style.display = 'block');
  },

  hideLoader() {
    document.querySelectorAll('.ui-block').forEach(el => el.style.display = 'none');
  },

  handleError(errors) {
    errors.forEach(error => {
      Notification.error(
        TYPO3.lang.error, TYPO3.lang.message_prefix + ' ' + error, 5
      );
    });
  },

  handleSuccess(successes) {
    successes.forEach(success => {
      Notification.success(
        'OK', TYPO3.lang.message_prefix + ' ' + success, 5
      );
    });
  },

  initSyncAllDocuments() {
    const button = document.getElementById('syncAllDocuments');
    if (button) {
      button.addEventListener('click', () => {
        this.showLoader();
        new AjaxRequest(TYPO3.settings.ajaxUrls.er24_syncAllDocuments)
          .withQueryArguments({
            domainConfigId: this.domainConfigId,
            apiKey: document.getElementById('apiKey').value
          })
          .get().then(async response => {
            const resolved = await response.resolve();
            this.handleResultMessages(resolved);
            window.location.reload();
            this.hideLoader();
          });
      });
    }
  },

  capitalize(stringToCast) {
    return stringToCast.charAt(0).toUpperCase() + stringToCast.slice(1);
  },

  handleResultMessages(resolved) {
    if (resolved.errors) this.handleError(resolved.errors);
    if (resolved.successes) this.handleSuccess(resolved.successes);
  },

  initDocumentPartForm(partType) {
    const documentType = {
      privacy: 'privacyPolicy',
      social: 'privacyPolicySocialMedia'
    }[partType] || partType;

    const upperCaseType = this.capitalize(partType);
    this.currentSources[partType] = document.querySelector(`.tab-${partType}`).dataset.source;

    processCurrentDataSource();

    document.getElementById(`sync${upperCaseType}`)?.addEventListener('click', () => {
      this.showLoader();
      new AjaxRequest(TYPO3.settings.ajaxUrls.er24_syncDocument)
        .withQueryArguments({
          domainConfigId: this.domainConfigId,
          documentType: documentType
        })
        .get().then(async response => {
          const resolved = await response.resolve();
          this.handleResultMessages(resolved);

          if (resolved.response) {
            const response = resolved.response;
            if (response.html_de && response.html_en && response.modified) {
              document.getElementById(`${partType}De`).value = response.html_de;
              document.getElementById(`${partType}En`).value = response.html_en;
              document.getElementById(`${partType}DeTstamp`).value = response.modified;
              document.getElementById(`${partType}EnTstamp`).value = response.modified;
              document.getElementById(`${partType}RestoreRemote`).removeAttribute('disabled');
            }
          }
          this.hideLoader();
        });
    });

    document.getElementById(`${partType}Source`)?.addEventListener('change', (event) => {
      this.showLoader();

      this.currentSources[partType] = event.target.checked ? 1 : 0;

      const hiddenInput = document.querySelector(`input[name="${event.target.name}"]`);
      if (hiddenInput) hiddenInput.value = this.currentSources[partType];

      const ajaxArguments = {
        domainConfigId: this.domainConfigId,
        properties: {}
      };
      ajaxArguments.properties[`${partType}Source`] = this.currentSources[partType];

      new AjaxRequest(TYPO3.settings.ajaxUrls.er24_saveDomainConfig)
        .withQueryArguments(ajaxArguments)
        .get().then(async response => {
          const resolved = await response.resolve();
          this.handleResultMessages(resolved);
          processCurrentDataSource();
          this.hideLoader();
        });
    });

    document.getElementById(`${partType}RestoreRemote`)?.addEventListener('click', () => {
      document.getElementById(`${partType}DeLocal`).value = document.getElementById(`${partType}De`).value;
      document.getElementById(`${partType}EnLocal`).value = document.getElementById(`${partType}En`).value;
    });

    function processCurrentDataSource() {
      const tab = document.querySelector(`.tab-${partType}`);

      if (tab) {
        const remoteContent = tab.querySelectorAll('.remote-content');
        const localContent = tab.querySelectorAll('.local-content');

        if (eRecht24Module.currentSources[partType] == 0) {
          remoteContent.forEach(element => {
            element.style.display = 'none';
          });
          localContent.forEach(element => {
            element.style.display = 'block';
          });
        } else {
          remoteContent.forEach(element => {
            element.style.display = 'block';
          });
          localContent.forEach(element => {
            element.style.display = 'none';
          });
        }
      }
    }
  },

  processTrackingConfiguration() {
    const optOutCodeRow = document.getElementById('optOutCodeRow');
    if (optOutCodeRow) {
      optOutCodeRow.style.display = this.trackingEnabled ? 'block' : 'none';
    }
  },

  initAnalyticsTab() {
    this.processTrackingConfiguration();

    document.querySelectorAll('#tab-google .checkbox-input').forEach(input => {
      input.addEventListener('change', () => {
        this.showLoader();

        const enabled = input.checked;
        input.closest('input').value = enabled ? 1 : 0;

        if (input.id === 'flagEmbedTracking') {
          this.trackingEnabled = enabled;
        }

        this.processTrackingConfiguration();

        const ajaxArguments = {
          domainConfigId: this.domainConfigId,
          properties: {},
          flushAnalyticsCache: 1
        };

        ajaxArguments.properties[input.id] = enabled ? 1 : 0;

        new AjaxRequest(TYPO3.settings.ajaxUrls.er24_saveDomainConfig)
          .withQueryArguments(ajaxArguments)
          .get().then(async response => {
            const resolved = await response.resolve();
            this.handleResultMessages(resolved);
            this.hideLoader();
          });
      });
    });
  },

  initFormInteractions() {
    if (document.querySelector('.t3js-tabmenu-item.has-validation-error')) {
      const errors = [TYPO3.lang.connection_error_detected];
      this.handleError(errors);
    }

    const selfRepair = document.getElementById('selfRepair');
    if (selfRepair) {
      selfRepair.addEventListener('click', () => {
        this.showLoader();

        new AjaxRequest(TYPO3.settings.ajaxUrls.er24_refreshConfig)
          .withQueryArguments({ domainConfigId: this.domainConfigId })
          .get().then(async response => {
            const resolved = await response.resolve();
            this.handleResultMessages(resolved);

            const parentTab = selfRepair.closest('.t3js-tabmenu-item');
            if (resolved.errors.length === 0) {
              parentTab?.classList.remove('has-validation-error');
            } else {
              parentTab?.classList.add('has-validation-error');
            }

            resolved.fixed?.forEach(fixed => {
              if (fixed === 'apiConnection') document.getElementById('connectionStateRow')?.classList.remove('has-error-1');
              if (fixed === 'clientConfiguration') document.getElementById('configStateRow')?.classList.remove('has-error-1');
              if (fixed === 'push') document.getElementById('pushStateRow')?.classList.remove('has-error-1');
            });

            this.hideLoader();
          });
      });
    }

    const copyDebugInformations = document.getElementById('copyDebugInformations');
    if (copyDebugInformations) {
      copyDebugInformations.addEventListener('click', () => {
        const debugInformations = document.getElementById('debugInformations');
        debugInformations.style.display = 'block';
        debugInformations.removeAttribute('disabled');
        debugInformations.select();
        document.execCommand("copy");
        debugInformations.setAttribute('disabled', 'disabled');
        this.handleSuccess([TYPO3.lang.debug_was_copied]);
      });
    }
  }
};

if (document.getElementById('domainConfigEditForm')) {
  eRecht24Module.showLoader();
  eRecht24Module.domainConfigId = parseInt(document.getElementById('domainConfigId').value);
  eRecht24Module.trackingEnabled = document.getElementById('flagEmbedTracking').checked;

  eRecht24Module.initSyncAllDocuments();
  eRecht24Module.initDocumentPartForm('imprint');
  eRecht24Module.initDocumentPartForm('privacy');
  eRecht24Module.initDocumentPartForm('social');
  eRecht24Module.initAnalyticsTab();
  eRecht24Module.initFormInteractions();
  eRecht24Module.hideLoader();
}

eRecht24Module.hideLoader();

const siteConfig = document.getElementById('siteConfig');
if (siteConfig) {
  siteConfig.addEventListener('change', () => {
    this.showLoader();

    const selectedOption = siteConfig.options[siteConfig.selectedIndex];
    const domainInput = document.getElementById('domain');
    domainInput.value = selectedOption.dataset.domain && selectedOption.dataset.domain !== '/' ? selectedOption.dataset.domain : '';

    this.hideLoader();
  });
}

document.querySelectorAll('.site-config-delete').forEach(button => {
  button.addEventListener('click', event => {
    event.preventDefault();
    const target = button.getAttribute('href');
    Modal.confirm(
      TYPO3.lang.attention,
      TYPO3.lang.delete_confirm,
      Severity.danger,
      [
        {
          text: top.TYPO3.lang.delete,
          active: true,
          trigger: () => {
            Modal.dismiss();
            setTimeout(() => {
              window.location = target;
            }, 500);
          }
        },
        {
          text: TYPO3.lang.abort,
          trigger: () => {
            Modal.dismiss();
          }
        }
      ]
    );
  });
});

export default eRecht24Module;
