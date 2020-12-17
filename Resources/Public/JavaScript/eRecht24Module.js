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
    currentImprintSource: 0
  };

  eRecht24Module.showLoader = function () {
    $('.ui-block').show();
  }

  eRecht24Module.hideLoader = function () {
    $('.ui-block').hide();
  }

  eRecht24Module.hideLoader();

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

  eRecht24Module.handleError = function(errors) {
    for(var error of errors) {
      Notification.error(
        'Fehler', error, 5
      );
    }
  }

  eRecht24Module.handleSuccess = function(successes) {
    for(var success of successes) {
      Notification.success(
        'OK', success, 5
      );
    }
  }

  eRecht24Module.initSyncAllDocuments = function() {
    $('#syncAllDocuments').click(function() {
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

  eRecht24Module.handleResultMessages = function(resolved) {
    if(resolved.errors) {
      eRecht24Module.handleError(resolved.errors);
    }
    if(resolved.successes) {
      eRecht24Module.handleSuccess(resolved.successes);
    }
  }

  eRecht24Module.initImprintForm = function() {

    eRecht24Module.currentImprintSource = $('.tab-imprint').data('source');
    processCurrentDataSource();

    $('#syncImprint').click(function() {
      eRecht24Module.showLoader();
      new AjaxRequest(TYPO3.settings.ajaxUrls.er24_syncImprint)
        .withQueryArguments(
          {
            domainConfigId: eRecht24Module.domainConfigId,
          }
        )
        .get().then(async function (response) {

          resolved = await response.resolve();

          eRecht24Module.handleResultMessages(resolved);

          if(resolved.response) {
            var response = resolved.response;
            if(response.imprintDe && response.imprintEn && response.modified) {
              $('#imprintDe').val(response.imprintDe);
              $('#imprintEn').val(response.imprintEn);
              $('#imprintDeTstamp').val(response.modified);
              $('#imprintEnTstamp').val(response.modified);
            }
          }

          eRecht24Module.hideLoader();
        }
      )
    })

    $('#imprintSource').change(function() {
      if($(this).is(':checked')) {
        eRecht24Module.currentImprintSource = 1;
      } else {
        eRecht24Module.currentImprintSource = 0;
      }
      processCurrentDataSource();
    });


    $('#imprintRestoreRemote').click(function() {
      $('#imprintDeLocal').val($('#imprintDe').val());
      $('#imprintEnLocal').val($('#imprintEn').val());
    });

    function processCurrentDataSource() {
      if(eRecht24Module.currentImprintSource == 0) {
        $('.tab-imprint').find('.remote-content').hide();
        $('.tab-imprint').find('.local-content').show();
      } else {
        $('.tab-imprint').find('.remote-content').show();
        $('.tab-imprint').find('.local-content').hide();
      }
    }

  }

  if ($('#configCreateForm').length > 0) {

    eRecht24Module.getLanguagePossibleUrls();
    eRecht24Module.hideLoader();

    $('#siteConfig').change(function () {
      eRecht24Module.showLoader();

      $('#language option').not(':first').remove();
      $('#domain').val('');

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
          for (language of eRecht24Module.languagePossibleUrls) {
            $('#language').append('<option value="' + language.languageId + '">' + language.name + '</option>')
          }
          eRecht24Module.hideLoader();
        }
      )
    });

    $('#language').change(function () {
      for (language of eRecht24Module.languagePossibleUrls) {
        if (parseInt(language.languageId) === parseInt($(this).val())) {
          $('#domain').val(language.domain);
          break;
        }
      }
    });
  }

  if($('#domainConfigEditForm').length > 0) {
    eRecht24Module.showLoader();
    eRecht24Module.domainConfigId = parseInt($('#domainConfigId').val());
    eRecht24Module.initSyncAllDocuments();
    eRecht24Module.initImprintForm();
    eRecht24Module.hideLoader();
  }

  // $('.site-config-delete').click(function (e) {
  //   e.preventDefault();
  //   Modal.confirm('Warning', 'You may break the internet!', Severity.danger, [
  //     {
  //       text: 'Break it',
  //       active: true,
  //       trigger: function () {
  //         window.location = $(this).attr('href');
  //       }
  //     }, {
  //       text: 'Abort!',
  //       trigger: function () {
  //         Modal.dismiss();
  //       }
  //     }
  //   ]);
  //});


});
