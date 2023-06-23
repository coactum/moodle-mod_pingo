<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * German strings for the plugin are defined here.
 *
 * @package     mod_pingo
 * @category    string
 * @copyright   2023 coactum GmbH
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Common strings.
$string['pluginname'] = 'PINGO';

// Strings for mod_form.php.
$string['modulename'] = 'PINGO';
$string['modulename_help'] = 'Die Aktivität PINGO erlaubt ... ';
$string['modulename_link'] = 'mod/pingo/view';
$string['pluginadministration'] = 'Administration der PINGO-Instanz';
$string['editability'] = 'Bearbeitbarkeit';
$string['editableforall'] = 'Von anderen Lehrenden nutzbar';
$string['editableforall_help'] = 'Wenn aktiviert können alle Lehrenden in dieser Aktivität alle Sessions des verbundenen PINGO Accounts ansehen, in diesen Umfragen anlegen und deren Ergebnisse ansehen. Ist diese Option nicht aktiviert kann dies nur die Person die sich mit ihrem PINGO-Account einloggt, alle anderen Lehrenden sehen andere dann lediglich die Teilnehmeransicht. <br><strong>Achtung:</strong> Diese Einstellung kann nach dem Anlegen der Aktivität nicht mehr verändert werden. Falls eine nachträgliche Änderung gewünscht ist muss die Aktivität gelöscht und wieder neu erstellt werden. Daten gehen in diesem Fall keine verloren.';

// Strings for index.php.
$string['modulenameplural'] = 'PINGOs';
$string['nonewmodules'] = 'Keine neuen Instanzen';

// Strings for lib.php.
$string['deletealluserdata'] = 'Alle Benutzerdaten löschen';
$string['alluserdatadeleted'] = 'Alle Benutzerdaten gelöscht';

// Strings for the capabilities.
$string['pingo:addinstance'] = 'Neue PINGO Instanz hinzufügen';
$string['pingo:viewoverview'] = 'Übersichtsseite ansehen';
$string['pingo:logintopingo'] = 'Bei PINGO anmelden';
$string['pingo:viewallsessions'] = 'Alle PINGO Sessions ansehen';
$string['pingo:startsurvey'] = 'Umfrage aus PINGO starten';

// Strings for the admin settings.
$string['connectionsdetails'] = 'Verbindungseinstellungen';
$string['remoteserver'] = 'PINGO-Server';
$string['remoteserverall'] = 'PINGO-Server zu dem sich das Plugin verbinden soll.';

// Strings for the view page.
$string['overview'] = 'Überblick';
$string['viewallpingos'] = 'Alle PINGO-Instanzen im Kurs ansehen';
$string['login'] = 'Login';
$string['loginfailed'] = 'PINGO Login fehlgeschlagen';
$string['loginfailedinvalidcredentials'] = 'PINGO Login fehlgeschlagen (fehlerhafte Anmeldedaten)';
$string['loginsuccessful'] = 'PINGO Login erfolgreich';
$string['connected'] = 'Verbunden';
$string['closeconnection'] = 'Verbindung trennen';
$string['noconnection'] = 'Nicht verbunden';
$string['yoursessions'] = 'Ihre Sessions';
$string['nosessionsavailable'] = 'Keine Sitzungen verfügbar';
$string['answers'] = 'Antwortmöglichkeiten';
$string['continuesurvey'] = 'Fortfahren';
$string['repeatsurvey'] = 'Wiederholen';
$string['stopsurvey'] = 'Stoppen';
$string['stoptime'] = 'Wann stoppen?';
$string['nosurveys'] = 'Sie haben noch keine Umfragen in dieser Session erstellt.';
$string['nosurveyactive'] = 'Keine Umfrage aktiv';
$string['sessions'] = 'Sessions';
$string['quickstart'] = 'Schnellstart';
$string['catalog'] = 'Katalog';
$string['createsessioninpingo'] = 'Session anlegen (in PINGO)';
$string['startsurvey'] = 'Starten';
$string['quicksurvey'] = 'Schnelle Umfrage';
$string['questionfromcatalog'] = 'Aus Katalog';
$string['session'] = 'Session';
$string['surveycreated'] = 'Umfrage gestartet';
$string['reloadpage'] = 'Seite neu laden';
$string['surveystopped'] = 'Umfrage wird gestoppt';
$string['surveyends'] = 'Endet in ';
$string['surveyhasnoend'] = 'Kein Enddatum.';
$string['surveyended'] = 'Beendet:';
$string['nosessionchoosen'] = 'Keine Session ausgewählt.';
$string['noactivesession'] = 'Keine aktive Sitzung.';
$string['setsessionactive'] = 'Sitzung für Teilnehmende aktiv schalten';
$string['voteinpingo'] = 'Abstimmen (in PINGO)';
$string['state'] = 'Status';
$string['activatesession'] = 'Für Teilnehmende anzeigen';
$string['sessionactivated'] = 'Session aktiviert';
$string['surveyinsession'] = 'Umfrage in Session';

// Strings for the login form.
$string['pingoemail'] = 'Die E-Mail-Adresse in PINGO';
$string['pingoemail_help'] = 'Der für den PINGO Account genutzte E-Mail-Adresse';
$string['pingopassword'] = 'Das Passwort in PINGO';
$string['pingopassword_help'] = 'Das Passwort des Accounts in PINGO';
$string['nopingoyet'] = 'Noch kein Account?';
$string['registerforpingo'] = 'Bei PINGO registrieren (externe Seite)';
$string['logintopingo'] = 'Bei PINGO anmelden';
$string['pingoimpressum'] = 'Impressum (PINGO)';
$string['pingoprivacypolicy'] = 'Datenschutzerklärung (PINGO)';

// Strings for the quickstart form.
$string['quickstartexplanation'] = 'Starten Sie eine generische Umfrage in dieser Session.';
$string['questiontypes'] = 'Fragetyp';
$string['answeroptions'] = 'Antwortoptionen';
$string['durationchoices'] = 'Dauer';
$string['nocountdown'] = 'Ohne Countdown';

// Strings for the questionfromcatalog form.
$string['addquestionfromcatalog'] = 'Frage vom Katalog hinzufügen';
$string['questionfromcatalogexplanation'] = 'Starten Sie in dieser Session eine Frage aus ihrem Fragenkatalog.';
$string['managequestionsinpingo'] = 'Fragenkatalog verwalten (in PINGO)';
$string['yourquestions'] = 'Ihre Fragen';
$string['filterbytags'] = 'Tag-Filter';
$string['alltags'] = 'Alle Tags';

// Strings for the events.
$string['eventconnectionclosed'] = 'PINGO Verbindung beendet';
$string['eventconnectioncreated'] = 'PINGO Verbindung angelegt';
$string['eventpingologinfailed'] = 'PINGO Login fehlgeschlagen';
$string['eventpingologinsuccessful'] = 'PINGO Login erfolgreich';

// Strings for all errors.
$string['errunauthorized'] = 'Authentifizierung fehlgeschlagen. Die gespeicherte Anmeldung für PINGO ist ungültig oder wurde in PINGO widerrufen. Die Verbindung muss geschlossen und neu gestartet werden.';
$string['errnoemail'] = 'Keine gültige E-Mail-Adresse';
$string['errfetching'] = 'Fehler beim Laden der Daten. Bitte Seite neu laden.';
$string['errsurveynotcreated'] = 'Fehler beim Anlegen der neuen Umfrage.';
$string['errsurveynotstopped'] = 'Fehler beim Stoppen der Umfrage.';
$string['errnoquestionchoosen'] = 'Keine Frage ausgewählt.';
$string['errnotallowedforotherteachers'] = 'Andere Lehrende dürfen in dieser Aktivität keine Umfragen hinzufügen.';

// Strings for the privacy api.
$string['privacy:metadata:pingo_connections'] = 'Enthält personenbezogene Anmeldedaten der Lehrenden für die Anmeldung bei PINGO.';
$string['privacy:metadata:pingo_connections:userid'] = 'Moodle ID des Benutzers der die Plugininstanz bei PINGO anmeldet';
$string['privacy:metadata:pingo_connections:pingo'] = 'ID der Plugin Instanz';
$string['privacy:metadata:pingo_connections:authenticationtoken'] = 'Der Token zur Authentifizierung bei PINGO. Wird beim erstmaligen Login in der Plugin-Instanz von PINGO geholt und dann dauerhaft in der Datenbank gespeichert. Wird bei jeder Aktion im Plugin erneut an PINGO geschickt und ermöglicht vollen Zugriff auf alle auf dem PINGO Server gespeicherten Daten des oder der Nutzenden (z. B. Fragen, Umfragen, Sessions usw.). Kann in PINGO ungültig gemacht werden (von allen externen Anwendungen abmelden).';
$string['privacy:metadata:pingo_connections:timestarted'] = 'Datum an dem die Anmeldung an PINGO erfolgt ist';
$string['privacy:metadata:pingo_connections:activesession'] = 'Die PINGO Session die in der Teilnehmeransicht sichtbar ist';
