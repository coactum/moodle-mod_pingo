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
$string['modulename_help'] = 'Die Aktivität erlaubt die Integration des kostenlos nutzbaren Classroom-Response-Systems für PINGO in Moodle.

PINGO ermöglicht das unkomplizierte Einholen von Live-Feedback und lässt sich vielfältig in der Lehre einsetzen. So können vorab bequem Umfragen vorbereitet und diese in der Lehrveranstaltung dann schnell dem gesamten Publikum über dessen Mobilgeräte zugänglich gemacht werden.

Diese Aktivität ermöglicht dabei die Integration von PINGO in Moodle. So erlaubt sie Lehrenden, sich direkt in der Aktivität bei PINGO anzumelden und danach auf ihre in PINGO angelegten Sessions zuzugreifen, sie anzusehen, in ihnen Umfragen hinzuzufügen und diese dann zu starten.
Teilnehmende können dann die Umfragen direkt in der Aktivität ansehen. Für zusätzliche Aktionen wie etwa das Anlegen neuer Sessions oder Fragen leitet die Aktivität zudem zur Webvariante von PINGO weiter.

Lehrende können ...

* sich bequem in PINGO einloggen
* alle in PINGO angelegten Sessions ansehen
* schnelle Umfragen sowie Fragen aus dem Fragenkatalog zu einer Session hinzufügen und starten
* einzelne Sessions und die jeweils letzte dort aktive Umfrage ansehen

Teilnehmende können ...

* Die aktive Session ansehen und die dortige Umfrage zur Abstimmung öffnen';
$string['modulename_link'] = 'mod/pingo/view';
$string['pluginadministration'] = 'Administration von PINGO';
$string['editability'] = 'Bearbeitbarkeit';
$string['editableforall'] = 'Von anderen Lehrenden nutzbar';
$string['editableforall_help'] = 'Wenn aktiviert können alle Lehrenden in dieser Aktivität alle Sessions des verbundenen PINGO Accounts ansehen, in diesen Umfragen anlegen und deren Ergebnisse ansehen. Ist diese Option nicht aktiviert kann dies nur die Person die sich mit ihrem PINGO-Account einloggt, alle anderen Lehrenden sehen dann lediglich die Teilnehmeransicht. <br><strong>Achtung:</strong> Diese Einstellung kann nach dem Anlegen der Aktivität nicht mehr verändert werden. Falls eine nachträgliche Änderung gewünscht ist muss die Aktivität gelöscht und wieder neu erstellt werden. Daten in PINGO gehen in diesem Fall keine verloren.';

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
$string['pingo:startsurvey'] = 'Umfrage in Session starten';

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
$string['nosessionsavailable'] = 'Keine Sitzungen verfügbar';
$string['stopsurvey'] = 'Stoppen';
$string['stoptime'] = 'Wann stoppen?';
$string['nosurveys'] = 'Sie haben noch keine Umfragen in dieser Session erstellt.';
$string['sessions'] = 'Sessions';
$string['quickstart'] = 'Schnellstart';
$string['catalogue'] = 'Katalog';
$string['createsessioninpingo'] = 'Session anlegen (in PINGO)';
$string['startsurvey'] = 'Starten';
$string['quicksurvey'] = 'Schnelle Umfrage';
$string['questionfromcatalogue'] = 'Aus Katalog';
$string['session'] = 'Session';
$string['surveycreated'] = 'Umfrage gestartet';
$string['reloadpage'] = 'Seite neu laden';
$string['surveystopped'] = 'Umfrage wird gestoppt';
$string['surveyends'] = 'Endet in ';
$string['surveyhasnoend'] = 'Kein Enddatum.';
$string['surveyended'] = 'Beendet:';
$string['noactivesession'] = 'Keine aktive Sitzung.';
$string['setsessionactive'] = 'Sitzung für Teilnehmende aktiv schalten';
$string['voteinpingo'] = 'Abstimmen (in PINGO)';
$string['state'] = 'Status';
$string['activatesession'] = 'Für Teilnehmende anzeigen';
$string['sessionactivated'] = 'Session aktiviert';
$string['surveyinsession'] = 'Umfrage in Session';

// Strings for the login form.
$string['pingoemail'] = 'Die E-Mail-Adresse in PINGO';
$string['pingoemail_help'] = 'Die für den Login in PINGO genutzte E-Mail-Adresse';
$string['pingopassword'] = 'Das Passwort für PINGO';
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

// Strings for the question from catalogue form.
$string['addquestionfromcatalogue'] = 'Frage vom Katalog hinzufügen';
$string['questionfromcatalogueexplanation'] = 'Starten Sie in dieser Session eine Frage aus Ihrem Fragenkatalog.';
$string['managequestionsinpingo'] = 'Fragenkatalog verwalten (in PINGO)';
$string['yourquestions'] = 'Ihre Fragen';
$string['filterbytags'] = 'Tag-Filter';
$string['alltags'] = 'Alle Tags';

// Strings for the events.
$string['eventconnectionclosed'] = 'PINGO Verbindung beendet';
$string['eventconnectioncreated'] = 'PINGO Verbindung angelegt';
$string['eventpingologinfailed'] = 'PINGO Login fehlgeschlagen';
$string['eventpingologinsuccessful'] = 'PINGO Login erfolgreich';
$string['eventpingosurveycreated'] = 'PINGO Umfrage erstellt';

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
$string['privacy:metadata:pingo_connections:pingo'] = 'ID der Plugininstanz';
$string['privacy:metadata:pingo_connections:authenticationtoken'] = 'Der Token zur Authentifizierung bei PINGO. Wird beim erstmaligen Login in der Plugininstanz von PINGO geholt und dann dauerhaft in der Datenbank gespeichert. Wird bei jeder Aktion im Plugin erneut an PINGO geschickt und ermöglicht vollen Zugriff auf alle auf dem PINGO Server gespeicherten Daten des oder der Nutzenden (z. B. Fragen, Umfragen, Sessions usw.). Kann in PINGO ungültig gemacht werden.';
$string['privacy:metadata:pingo_connections:timestarted'] = 'Datum an dem die Anmeldung an PINGO erfolgt ist';
$string['privacy:metadata:pingo_connections:activesession'] = 'Die PINGO Session die in der Teilnehmeransicht sichtbar ist';
