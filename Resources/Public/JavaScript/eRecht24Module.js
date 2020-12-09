define(['jquery',
  'TYPO3/CMS/Core/Ajax/AjaxRequest',
  'TYPO3/CMS/Backend/FormEngineValidation',
  'TYPO3/CMS/Backend/Utility/MessageUtility',
  'TYPO3/CMS/Backend/Severity',
  'TYPO3/CMS/Backend/Modal'
], function ($, AjaxRequest, FormEngineValidation, MessageUtility, Severity, Modal) {

  console.log(top.TYPO3);

  var eRecht24Module = {
    languagePossibleUrls: []
  };

  eRecht24Module.showLoader = function () {
    $('.ui-block').show();
  }

  eRecht24Module.hideLoader = function () {
    $('.ui-block').hide();
  }

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
