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

$string['activatesession'] = 'Für Teilnehmende anzeigen';
$string['addquestionfromcatalogue'] = 'Frage vom Katalog hinzufügen';
$string['alltags'] = 'Alle Tags';
$string['alluserdatadeleted'] = 'Alle Benutzerdaten gelöscht';
$string['answeroptions'] = 'Antwortoptionen';
$string['catalogue'] = 'Katalog';
$string['closeconnection'] = 'Verbindung trennen';
$string['connected'] = 'Verbunden';
$string['connectionsdetails'] = 'Verbindungseinstellungen';
$string['createsessioninpingo'] = 'Session anlegen (in PINGO)';
$string['deletealluserdata'] = 'Alle Benutzerdaten löschen';
$string['durationchoices'] = 'Dauer';
$string['editability'] = 'Bearbeitbarkeit';
$string['editableforall'] = 'Von anderen Lehrenden nutzbar';
$string['editableforall_help'] = 'Wenn aktiviert können alle Lehrenden in dieser Aktivität alle Sessions des verbundenen PINGO Accounts ansehen, in diesen Umfragen anlegen und deren Ergebnisse ansehen. Ist diese Option nicht aktiviert kann dies nur die Person die sich mit ihrem PINGO-Account einloggt, alle anderen Lehrenden sehen dann lediglich die Teilnehmeransicht. <br><strong>Achtung:</strong> Diese Einstellung kann nach dem Anlegen der Aktivität nicht mehr verändert werden. Falls eine nachträgliche Änderung gewünscht ist muss die Aktivität gelöscht und wieder neu erstellt werden. Daten in PINGO gehen in diesem Fall keine verloren.';
$string['errfetching'] = 'Fehler beim Laden der Daten. Bitte Seite neu laden.';
$string['errnoemail'] = 'Keine gültige E-Mail-Adresse';
$string['errnoquestionchoosen'] = 'Keine Frage ausgewählt.';
$string['errnotallowedforotherteachers'] = 'Andere Lehrende dürfen in dieser Aktivität keine Umfragen hinzufügen.';
$string['errsurveynotcreated'] = 'Fehler beim Anlegen der neuen Umfrage.';
$string['errsurveynotstopped'] = 'Fehler beim Stoppen der Umfrage.';
$string['errunauthorized'] = 'Authentifizierung fehlgeschlagen. Die gespeicherte Anmeldung für PINGO ist ungültig oder wurde in PINGO widerrufen. Die Verbindung muss geschlossen und neu gestartet werden.';
$string['eventconnectionclosed'] = 'PINGO Verbindung beendet';
$string['eventconnectioncreated'] = 'PINGO Verbindung angelegt';
$string['eventpingologinfailed'] = 'PINGO Login fehlgeschlagen';
$string['eventpingologinsuccessful'] = 'PINGO Login erfolgreich';
$string['eventpingosurveycreated'] = 'PINGO Umfrage erstellt';
$string['filterbytags'] = 'Tag-Filter';
$string['login'] = 'Login';
$string['loginfailed'] = 'PINGO Login fehlgeschlagen';
$string['loginfailedinvalidcredentials'] = 'PINGO Login fehlgeschlagen (fehlerhafte Anmeldedaten)';
$string['loginsuccessful'] = 'PINGO Login erfolgreich';
$string['logintopingo'] = 'Bei PINGO anmelden';
$string['managequestionsinpingo'] = 'Fragenkatalog verwalten (in PINGO)';
$string['modulename'] = 'PINGO';
$string['modulename_help'] = 'Die Aktivität erlaubt die Integration des kostenlos nutzbaren Classroom-Response-Systems für PINGO in Moodle.

PINGO ermöglicht das unkomplizierte Einholen von anonymem Live-Feedback und lässt sich vielfältig in der Lehre einsetzen. So können vorab bequem Umfragen vorbereitet und diese in der Lehrveranstaltung dann schnell dem gesamten Publikum über dessen Mobilgeräte zugänglich gemacht werden.

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
$string['modulenameplural'] = 'PINGOs';
$string['noactivesession'] = 'Keine aktive Sitzung.';
$string['nocountdown'] = 'Ohne Countdown';
$string['nonewmodules'] = 'Keine neuen Instanzen';
$string['nopingoyet'] = 'Noch kein Account?';
$string['nosessionsavailable'] = 'Keine Sitzungen verfügbar';
$string['nosurveys'] = 'Sie haben noch keine Umfragen in dieser Session erstellt.';
$string['overview'] = 'Überblick';
$string['pingo:addinstance'] = 'Neue PINGO Instanz hinzufügen';
$string['pingo:logintopingo'] = 'Bei PINGO anmelden';
$string['pingo:startsurvey'] = 'Umfrage in Session starten';
$string['pingo:viewallsessions'] = 'Alle PINGO Sessions ansehen';
$string['pingo:viewoverview'] = 'Übersichtsseite ansehen';
$string['pingoemail'] = 'Die E-Mail-Adresse in PINGO';
$string['pingoemail_help'] = 'Die für den Login in PINGO genutzte E-Mail-Adresse';
$string['pingoimpressum'] = 'Impressum (PINGO)';
$string['pingopassword'] = 'Das Passwort für PINGO';
$string['pingopassword_help'] = 'Das Passwort des Accounts in PINGO';
$string['pingoprivacypolicy'] = 'Datenschutzerklärung (PINGO)';
$string['pluginadministration'] = 'Administration von PINGO';
$string['pluginname'] = 'PINGO';
$string['privacy:metadata:pingo'] = 'Um sich zu authentifizieren und den Classroom-Response-Dienst von PINGO zu nutzen, müssen einige personenbezogene Daten mit dem externen PINGO-Server ausgetauscht werden.';
$string['privacy:metadata:pingo:authenticationtoken'] = 'Der von PINGO erhaltene Authentifizierungstoken wird bei jeder Aktion an den PINGO-Server gesendet, damit die angeforderten Daten abgerufen werden können.';
$string['privacy:metadata:pingo:email'] = 'Die E-Mail-Adresse des PINGO-Kontos wird an den PINGO-Server gesendet, um die Anmeldung zu authentifizieren und einen Authentifizierungstoken zu erhalten.';
$string['privacy:metadata:pingo:password'] = 'Das Passwort des PINGO-Kontos wird an den PINGO-Server gesendet, um die Anmeldung zu authentifizieren. Es wird nicht in Moodle gespeichert.';
$string['privacy:metadata:pingo_connections'] = 'Enthält personenbezogene Anmeldedaten der Lehrenden für die Anmeldung bei PINGO.';
$string['privacy:metadata:pingo_connections:activesession'] = 'Die PINGO Session die in der Teilnehmeransicht sichtbar ist';
$string['privacy:metadata:pingo_connections:authenticationtoken'] = 'Der Token zur Authentifizierung bei PINGO. Wird beim erstmaligen Login in der Plugininstanz von PINGO geholt und dann dauerhaft in der Datenbank gespeichert. Wird bei jeder Aktion im Plugin erneut an PINGO geschickt und ermöglicht vollen Zugriff auf alle auf dem PINGO Server gespeicherten Daten des oder der Nutzenden (z. B. Fragen, Umfragen, Sessions usw.). Kann in PINGO ungültig gemacht werden.';
$string['privacy:metadata:pingo_connections:pingo'] = 'ID der Plugininstanz';
$string['privacy:metadata:pingo_connections:timestarted'] = 'Datum an dem die Anmeldung an PINGO erfolgt ist';
$string['privacy:metadata:pingo_connections:userid'] = 'Moodle ID des Benutzers der die Plugininstanz bei PINGO anmeldet';
$string['questionfromcatalogue'] = 'Aus Katalog';
$string['questionfromcatalogueexplanation'] = 'Starten Sie in dieser Session eine Frage aus Ihrem Fragenkatalog.';
$string['questiontypes'] = 'Fragetyp';
$string['quickstart'] = 'Schnellstart';
$string['quickstartexplanation'] = 'Starten Sie eine generische Umfrage in dieser Session.';
$string['quicksurvey'] = 'Schnelle Umfrage';
$string['registerforpingo'] = 'Bei PINGO registrieren (externe Seite)';
$string['reloadpage'] = 'Seite neu laden';
$string['remoteserver'] = 'PINGO-Server';
$string['remoteserverall'] = 'PINGO-Server zu dem sich das Plugin verbinden soll.';
$string['session'] = 'Session';
$string['sessionactivated'] = 'Session aktiviert';
$string['sessions'] = 'Sessions';
$string['setsessionactive'] = 'Sitzung für Teilnehmende aktiv schalten';
$string['startsurvey'] = 'Starten';
$string['state'] = 'Status';
$string['stopsurvey'] = 'Stoppen';
$string['stoptime'] = 'Wann stoppen?';
$string['surveycreated'] = 'Umfrage gestartet';
$string['surveyended'] = 'Beendet:';
$string['surveyends'] = 'Endet in ';
$string['surveyhasnoend'] = 'Kein Enddatum.';
$string['surveyinsession'] = 'Umfrage in Session';
$string['surveystopped'] = 'Umfrage wird gestoppt';
$string['viewallpingos'] = 'Alle PINGO-Instanzen im Kurs ansehen';
$string['voteinpingo'] = 'Abstimmen (in PINGO)';
$string['yourquestions'] = 'Ihre Fragen';
