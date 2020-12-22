define(['jquery',
    'TYPO3/CMS/Core/Ajax/AjaxRequest',
    'TYPO3/CMS/Backend/FormEngineValidation',
    'TYPO3/CMS/Backend/Utility/MessageUtility',
    'TYPO3/CMS/Backend/Severity',
    'TYPO3/CMS/Backend/Modal',
    'TYPO3/CMS/Backend/Notification'
  ], function ($, AjaxRequest, FormEngineValidation, MessageUtility, Severity, Modal, Notification) {


    var eRecht24Module = {
      languagePossibleUrls: [],
      domainConfigId: null,
      currentImprintSource: 0,
      currentSources: {
        imprint: 0,
        privacy: 0,
        social: 0
      }
    };

    eRecht24Module.showLoader = function () {
      $('.ui-block').show();
    }

    eRecht24Module.hideLoader = function () {
      $('.ui-block').hide();
    }

    eRecht24Module.hideLoader();

    // @deprecated
    eRecht24Module.getLanguagePossibleUrls = function () {
      new AjaxRequest(TYPO3.settings.ajaxUrls.er24_changeSiteConfig)
        .withQueryArguments(
          {
            siteconfig: $('#siteConfig').val(),
            siteLanguage: $('#language').val()
          }
        )
        .get().then(async function (response) {
          resolved = await response.resolve();
          eRecht24Module.languagePossibleUrls = resolved;
        }
      )
    }

    eRecht24Module.handleError = function (errors) {
      for (var error of errors) {
        Notification.error(
          'Fehler', 'eRecht24 Extension für TYPO3: ' + error, 5
        );
      }
    }

    eRecht24Module.handleSuccess = function (successes) {
      for (var success of successes) {
        Notification.success(
          'OK', 'eRecht24 Extension für TYPO3: ' + success, 5
        );
      }
    }

    eRecht24Module.initSyncAllDocuments = function () {
      $('#syncAllDocuments').click(function () {
        eRecht24Module.showLoader();
        new AjaxRequest(TYPO3.settings.ajaxUrls.er24_syncAllDocuments)
          .withQueryArguments(
            {
              domainConfigId: eRecht24Module.domainConfigId,
              apiKey: $('#apiKey').val()
            }
          )
          .get().then(async function (response) {
            resolved = await response.resolve();
            eRecht24Module.handleResultMessages(resolved);
            location.reload();
            eRecht24Module.hideLoader();
          }
        )
      })
    }

    eRecht24Module.capitalize = function (stringToCast) {
      return stringToCast.charAt(0).toUpperCase() + stringToCast.slice(1);
    }

    eRecht24Module.handleResultMessages = function (resolved) {
      if (resolved.errors) {
        eRecht24Module.handleError(resolved.errors);
      }
      if (resolved.successes) {
        eRecht24Module.handleSuccess(resolved.successes);
      }
    }


    eRecht24Module.initDocumentPartForm = function (partType) {

      var documentType = partType;

      if(partType === 'privacy') {
        documentType = 'privacyPolicy'
      } else if(partType === 'social') {
        documentType = 'privacyPolicySocialMedia';
      }

      var upperCaseType = eRecht24Module.capitalize(partType);

      eRecht24Module.currentSources[partType] = $('.tab-' + partType).data('source');
      console.log($('.tab-' + partType).data('source'));

      processCurrentDataSource();

      $('#sync' + upperCaseType).click(function () {
        eRecht24Module.showLoader();
        new AjaxRequest(TYPO3.settings.ajaxUrls.er24_syncDocument)
          .withQueryArguments(
            {
              domainConfigId: eRecht24Module.domainConfigId,
              documentType: documentType
            }
          )
          .get().then(async function (response) {

            resolved = await response.resolve();

            eRecht24Module.handleResultMessages(resolved);

            if (resolved.response) {
              var response = resolved.response;
              if (response.html_de && response.html_en && response.modified) {
                $('#' + partType + 'De').val(response.html_de);
                $('#' + partType + 'En').val(response.html_en);
                $('#' + partType + 'DeTstamp').val(response.modified);
                $('#' + partType + 'EnTstamp').val(response.modified);
                $('#' + partType + 'RestoreRemote').removeAttr('disabled');
              }
            }
            eRecht24Module.hideLoader();
          }
        )
      })

      $('#' + partType + 'Source').change(function () {

        eRecht24Module.showLoader();

        if ($(this).is(':checked')) {
          eRecht24Module.currentSources[partType] = 1;
          // Toggle TYPO3 hidden field for checkboxes
          $('input[name="'+$(this).attr("name")+'"]').not($(this)).val(1);
        } else {
          eRecht24Module.currentSources[partType] = 0;
          // Toggle TYPO3 hidden field for checkboxes
          $('input[name="'+$(this).attr("name")+'"]').not($(this)).val(0);
        }

        var arguments = {
          domainConfigId: eRecht24Module.domainConfigId,
          properties: {}
        };

        arguments.properties[partType + 'Source'] = eRecht24Module.currentSources[partType];

        new AjaxRequest(TYPO3.settings.ajaxUrls.er24_saveDomainConfig)
          .withQueryArguments(arguments)
          .get().then(async function (response) {

            resolved = await response.resolve();

            eRecht24Module.handleResultMessages(resolved);

            processCurrentDataSource();

            eRecht24Module.hideLoader();
          }, function (e) {
            // @todo Error Handling
            eRecht24Module.hideLoader();
          }
        )
      });

      $('#' + partType + 'RestoreRemote').click(function () {
        $('#' + partType + 'DeLocal').val($('#' + partType + 'De').val());
        $('#' + partType + 'EnLocal').val($('#' + partType + 'En').val());
      });

      function processCurrentDataSource() {
        console.log(partType);
        console.log(eRecht24Module.currentSources);
        if (eRecht24Module.currentSources[partType] == 0) {
          $('.tab-' + partType).find('.remote-content').hide();
          $('.tab-' + partType).find('.local-content').show();
        } else {
          $('.tab-' + partType).find('.remote-content').show();
          $('.tab-' + partType).find('.local-content').hide();
        }
      }
    }

    // eRecht24Module.initImprintForm = function () {
    //
    //   eRecht24Module.currentImprintSource = $('.tab-imprint').data('source');
    //   processCurrentDataSource();
    //
    //   $('#syncImprint').click(function () {
    //     eRecht24Module.showLoader();
    //     new AjaxRequest(TYPO3.settings.ajaxUrls.er24_syncImprint)
    //       .withQueryArguments(
    //         {
    //           domainConfigId: eRecht24Module.domainConfigId,
    //         }
    //       )
    //       .get().then(async function (response) {
    //
    //         resolved = await response.resolve();
    //
    //         eRecht24Module.handleResultMessages(resolved);
    //
    //         if (resolved.response) {
    //           var response = resolved.response;
    //           if (response.imprintDe && response.imprintEn && response.modified) {
    //             $('#imprintDe').val(response.imprintDe);
    //             $('#imprintEn').val(response.imprintEn);
    //             $('#imprintDeTstamp').val(response.modified);
    //             $('#imprintEnTstamp').val(response.modified);
    //           }
    //         }
    //
    //         eRecht24Module.hideLoader();
    //       }
    //     )
    //   })
    //
    //   $('#imprintSource').change(function () {
    //
    //     eRecht24Module.showLoader();
    //
    //     if ($(this).is(':checked')) {
    //       eRecht24Module.currentImprintSource = 1;
    //     } else {
    //       eRecht24Module.currentImprintSource = 0;
    //     }
    //
    //     new AjaxRequest(TYPO3.settings.ajaxUrls.er24_saveDomainConfig)
    //
    //       .withQueryArguments(
    //         {
    //           domainConfigId: eRecht24Module.domainConfigId,
    //           properties: {
    //             imprintSource: eRecht24Module.currentImprintSource
    //           }
    //         }
    //       )
    //       .get().then(async function (response) {
    //
    //         resolved = await response.resolve();
    //
    //         eRecht24Module.handleResultMessages(resolved);
    //
    //         processCurrentDataSource();
    //
    //         eRecht24Module.hideLoader();
    //       }, function (e) {
    //         // @todo Error Handling
    //         eRecht24Module.hideLoader();
    //       }
    //     )
    //   });
    //
    //
    //   $('#imprintRestoreRemote').click(function () {
    //     $('#imprintDeLocal').val($('#imprintDe').val());
    //     $('#imprintEnLocal').val($('#imprintEn').val());
    //   });
    //
    //   function processCurrentDataSource() {
    //     if (eRecht24Module.currentImprintSource == 0) {
    //       $('.tab-imprint').find('.remote-content').hide();
    //       $('.tab-imprint').find('.local-content').show();
    //     } else {
    //       $('.tab-imprint').find('.remote-content').show();
    //       $('.tab-imprint').find('.local-content').hide();
    //     }
    //   }
    //
    // }

    if ($('#configCreateForm').length > 0) {

      //eRecht24Module.getLanguagePossibleUrls();
      eRecht24Module.hideLoader();

      $('#siteConfig').change(function () {
        eRecht24Module.showLoader();

        //$('#language option').not(':first').remove();

        var $selectedOption = $(this).find('option:selected');

        if($selectedOption.data('domain')) {
          $('#domain').val($selectedOption.data('domain'));
        } else {
          $('#domain').val('');
        }

        eRecht24Module.hideLoader();

        // new AjaxRequest(TYPO3.settings.ajaxUrls.er24_changeSiteConfig)
        //   .withQueryArguments(
        //     {
        //       siteconfig: $('#siteConfig').val(),
        //       siteLanguage: $('#language').val()
        //     }
        //   )
        //   .get().then(async function (response) {
        //     resolved = await response.resolve();
        //     eRecht24Module.languagePossibleUrls = resolved;
        //     for (language of eRecht24Module.languagePossibleUrls) {
        //       $('#language').append('<option value="' + language.languageId + '">' + language.name + '</option>')
        //     }
        //     eRecht24Module.hideLoader();
        //   }
        // )
      });

      // $('#language').change(function () {
      //   for (language of eRecht24Module.languagePossibleUrls) {
      //     if (parseInt(language.languageId) === parseInt($(this).val())) {
      //       $('#domain').val(language.domain);
      //       break;
      //     }
      //   }
      // });
    }

    if ($('#domainConfigEditForm').length > 0) {
      eRecht24Module.showLoader();
      eRecht24Module.domainConfigId = parseInt($('#domainConfigId').val());
      eRecht24Module.initSyncAllDocuments();
      eRecht24Module.initDocumentPartForm('imprint');
      eRecht24Module.initDocumentPartForm('privacy');
      eRecht24Module.initDocumentPartForm('social');
      eRecht24Module.hideLoader();
    }

    $('.site-config-delete').click(function (e) {
      e.preventDefault();
      var target = $(this).attr('href');
      Modal.confirm('Achtung', 'Sind Sie sicher, dass Sie diese API Konfiguration löschen möchten?', Severity.danger, [
        {
          text: 'Löschen',
          active: true,
          trigger: function () {
            Modal.dismiss();
            window.setTimeout(function() {
              window.location = target;
            }, 500);
          }
        }, {
          text: 'Abbrechen',
          trigger: function () {
            Modal.dismiss();
          }
        }
      ]);
    });


  }
);
