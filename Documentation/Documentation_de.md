Anleitung zum eRecht24 Rechtstexte Plugin für TYPO3
=======================================================

Zugriff auf die Einstellungen des eRecht24 Rechtstexte Plugins
--------------------------------------------------------------

Hinterlegen des API-Schlüssels
------------------------------

Dieses Plugin bietet [eRecht24 Premium Nutzern](https://www.e-recht24.de/mitglieder/) die Möglichkeit die Rechtstexte aus dem eRecht24 Projekt Manager direkt in WordPress zu übertragen. Damit die dafür notwendige Kommunikation zwischen beiden Seiten stattfinden kann, ist die Hinterlegung eines API-Schlüssels notwendig.

Dafür gehen Sie wie folgt vor:

1. Legen Sie im [eRecht24 Projekt Manager](https://www.e-recht24.de/mitglieder/tools/projekt-manager/) ein Projekt für Ihre Website an, sofern es noch keines gibt.
2. Klicken Sie dort neben dem Projektnamen auf das Symbol Einstellungen (Zahnradsymbol).
3. Klicken Sie auf die Schaltfläche _Neuen API-Schlüssel erzeugen_.
4. Kopieren Sie den API-Schlüssel in die Zwischenablage.
5. Wechseln Sie im TYPO3-Backend in das eRecht24 Rechtstexte Plugin.
6. Fügen Sie den API-Schlüssel in das zugehörige Feld unter dem Reiter API-Schlüssel.
7. Tragen Sie unter dem Reiter Domain die URL Ihrer Webseite ein und wählen Sie die jeweilige Site-Konfiguration aus.
8. Klicken Sie auf Konfiguration erstellen, um die Einstellung zu übernehmen.
9. Wollen Sie das Rechtstexte Plugin mit weiteren Webseiten in Ihrer TYPO3 Installation verwenden, können Sie weitere Konfigurationen anlegen, indem Sie diese Schritte wiederholen.

**Ihr eRecht24 Rechtstexte Plugin kann ab sofort mit dem eRecht24 Projekt Manager kommunizieren.**

Impressum übertragen und einfügen
---------------------------------

### Hinterlegen des Impressums

1. Erstellen Sie Ihr Impressum im [eRecht24 Projekt Manager](https://www.e-recht24.de/mitglieder/tools/projekt-manager/).
2. Gehen Sie zu den Einstellungen des eRecht24 Rechtstexte Plugins, klicken Sie auf den Reiter _Impressum_.
3. Wählen Sie bei Datenquelle die Option _eRecht24 Projekt Manager_.
4. Klicken Sie darunter auf die Schaltfläche _Daten synchronisieren und speichern_, um Ihr Impressum aus dem eRecht24 Projekt Manager zu übernehmen.

**Ihr Impressum ist nun in der Konfiguration Ihres eRecht24 Rechtstexte Plugins gespeichert.**

_**Hinweis**: Synchronisieren Sie ihre Daten künftig nach jeder Änderung des Impressums im eRecht24 Projekt Manager. Aus Haftungsgründen muss jede Aktualisierung des Impressums manuell durch den Websitebetreiber erfolgen und geprüft werden. Ein automatisches Einspielen ist daher nicht vorgesehen._

### Integrieren des Impressums in eine Seite

1. Rufen Sie die Bearbeitungsansicht Ihrer Seite auf, in welchem das Impressum künftig angezeigt werden soll.
2. Klicken Sie auf den Button _+ Inthalt_ und wählen Sie unter dem Reiter _Plug-Ins_ den Punkt eRecht24 Rechtstexte aus.
3. Wählen Sie in dem angezeigten Dialog unter dem Reiter _Plug-In_ bei „_Welcher Dokumenttyp soll angezeigt werden?_“ in der Auswahlliste den Eintrag _Impressum_ aus und klicken Sie darauf.
4. Wählen Sie unter „_In welcher Sprache soll es angezeigt werden?_“ die gewünschte Anzeigesprache aus.
5. Speichern Sie Ihre Seite.

_**Hinweis**: Das Impressum wird im eRecht24 Projekt Manager mit der Überschrift „Impressum“ erstellt. Sofern Ihre Seite ebenfalls die Überschrift „Impressum“ hat, könnte es hier zu einer Dopplung kommen. In diesem Fall aktivieren Sie die Option „H1 Überschrift aus Text entfernen“ im Plug-In Menü unter Schritt 3. Dann wird die Überschrift aus dem Impressumtext in der Anzeige entfernt._

Datenschutzerklärung übertragen und einfügen
--------------------------------------------

### Hinterlegen der Datenschutzerklärung

Zum Abrufen Ihrer Datenschutzerklärung gehen Sie bitte vor unter dem Punkt _Impressum_ beschrieben.

### Integrieren der Datenschutzerklärung in eine Seite

Zum Integrieren Ihrer Datenschutzerklärung gehen Sie bitte vor unter dem Punkt _Impressum_ beschrieben. Wählen Sie unter dem Punkt „_Welcher Dokumenttyp soll angezeigt werden?_“ die Option Datenschutzerklärung aus.

_**Hinweis**: Fügen Sie Impressum und Datenschutzerklärung immer auf separaten Seiten ein, da der Gesetzgeber dies so verlangt. Machen Sie beide Seiten von jeder Unterseite der Website aus per Link erreichbar._

Datenschutzerklärung für Social-Media-Profile übertragen und einfügen
---------------------------------------------------------------------

### Hinterlegen der Datenschutzerklärung für Ihre Social-Media-Profile

Zum Abrufen Ihrer Social-Media-Datenschutzerklärung gehen Sie bitte vor unter dem Punkt _Impressum_ beschrieben.

### Integrieren der Datenschutzerklärung in eine Seite für Ihre Social-Media-Profile

Zum Integrieren Ihrer Social-Media-Datenschutzerklärung gehen Sie bitte vor unter dem Punkt _Impressum_ beschrieben. Wählen Sie unter dem Punkt „_Welcher Dokumenttyp soll angezeigt werden?_“ die Option _Datenschutzerklärung für Ihre Social-Media-Profile_ aus.

_**Wichtig**: Wenn Sie aus Ihrem Social-Media-Profil auf die Seite Ihrer Datenschutzerklärung verlinken, fügen Sie bitte nach dem Kopieren der URL zu Ihrer Datenschutzerklärung in Ihr Social-Media-Profil am Ende der URL zusätzlich den Ankerpunkt #socialmediaprofile an. So springt die Seitenansicht nach dem Aufrufen des Links gleich zum Passus für die Social-Media-Profile._

Google Analytics Tracking Code integrieren
------------------------------------------

1. Sofern Sie noch keine Google Analytics Tracking ID für Ihre Seite haben, erstellen Sie mit Ihrem Google Account einen Tracking-Code für Ihre Website ([Hier finden Sie die Anleitung](https://support.google.com/analytics/answer/1008015?hl=de)).
2. Wechseln Sie hier in das eRecht24 Rechtstexte Plugin, klicken Sie auf den Reiter _Google Analytics_.
3. Kopieren Sie anschließend die ID des Tracking-Codes (Beispiel: UA-1234567-1) in das Feld _Google Analytics ID_.
4. Wenn der Google Analytics Tracking Code durch das eRecht24 Rechtstexte Plugin eingefügt werden soll, aktivieren Sie die Option _Trackingcode einbinden_.
5. Speichern Sie die Einstellung.

_**Wichtig**: Wenn Sie den Google Analytics Tracking Code und / oder den Opt-Out-Code über das eRecht24 Rechtstexte Plugin integrieren, achten Sie bitte darauf, dass dieser Code nicht auch durch das Template oder andere Erweiterungen von TYPO3 integriert wird. Es kann sonst zu Funktionsfehlern, fehlerhaftem Tracking oder zur Beeinträchtigung der Rechtssicherheit kommen._

Künftige Aktualisierung von Impressum und Datenschutzerklärung
--------------------------------------------------------------

Die Texte Ihres Impressums und Ihrer Datenschutzerklärung müssen gelegentlich aktualisiert werden, weil sich beispielsweise Formulierungen geändert haben oder neue Punkte aufgenommen wurden.

### Überarbeitung des Rechtstexts

Hierzu durchlaufen Sie wie gewohnt zunächst den entsprechenden Generator im eRecht24 Projekt Manager. Danach haben Sie folgende Möglichkeiten, Ihre Rechtstexte in Ihr Plugin zu übertragen:

### Methode 1: Aktualisierung direkt aus dem eRecht24 Projekt Manager

Klicken Sie direkt im eRecht24 Projekt Manager auf das Synchronisieren-Symbol in der Zeile mit Ihrem Rechtstext. Der eRecht24 Projekt Manager baut eine Verbindung zu Ihrem eRecht24 Rechtstexte Plugin auf, welches dann den aktualisierten Rechtstext abruft.

_**Wichtig**: Eine Synchronisierung der Rechtstexte kann nur erfolgen, wenn Sie in der Konfiguration Ihres Plugins bei den jeweiligen Rechtstexten bei der Option Datenquelle die Auswahl auf eRecht24 Projekt Manager gesetzt haben._

### Methode 2: Abruf des geänderten Rechtstexts im eRecht24 Rechtstexte Plugin

Zudem haben Sie die Möglichkeit auch direkt aus dem eRecht24 Rechtstexte Plugin heraus, Impressum und Datenschutzerklärung aus dem eRecht24 Projekt Manager abzurufen und die bisher gespeicherte Version der Texte im Plugin zu überschreiben. Nutzen Sie dafür in der Pluginkonfiguration, die Schaltfläche _Alle Rechtstexte synchronisieren_ und speichern oder jeweils hinter dem Reiter des einzelnen Rechtstexts die Schaltfläche _Daten synchronisieren und speichern_.

_**Wichtig**: Eine Synchronisierung der Rechtstexte kann nur erfolgen, wenn Sie in der Konfiguration Ihres Plugins bei den jeweiligen Rechtstexten bei der Option Datenquelle die Auswahl auf eRecht24 Projekt Manager gesetzt haben._

### Methode 3: Manuelles Kopieren in Ihr eRecht24 Rechtstexte Plugin

Klicken Sie im eRecht24 Projekt Manager in der Zeile mit Ihrem Rechtstext auf den Link HTML und im sich öffnenden Dialog unten auf die Schaltfläche _HTML-Code in die Zwischenablage kopieren_.

Öffnen Sie anschließend im eRecht24 Rechtstexte Plugin auf Ihrer Website die Konfiguration. Rufen Sie den Reiter für den zugehörigen Rechtstext auf. Stellen Sie sicher, dass dort die Auswahl bei _Datenquelle_ auf _Lokale Version_ gesetzt ist, entfernen Sie im Textfeld den bisherigen Rechtstext und fügen Sie Ihren aktualisierten Rechtstext aus der Zwischenablage ein. Schließen Sie den Vorgang mit Klick auf den Speichern-Button ab.

### Hinweis für alle v. g. Methoden

Sofern Sie in Ihrer Website ein Caching einsetzen, prüfen Sie bitte, ob der Cache für die betreffenden Seiten mit den Rechtstexten eventuell noch einmal geleert werden muss, damit die aktualisierten Inhalte angezeigt werden.
