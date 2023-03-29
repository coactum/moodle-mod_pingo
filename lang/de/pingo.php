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
$string['pluginname'] = 'Pingo';

// Strings for mod_form.php.
$string['modulename'] = 'Pingo';
$string['modulename_help'] = 'Die Aktivität Pingo erlaubt ... ';
$string['modulename_link'] = 'mod/pingo/view';
$string['pluginadministration'] = 'Administration der Pingo-Instanz';
$string['editability'] = 'Bearbeitbarkeit';
$string['editableforall'] = 'Sessions durch andere Lehrende bearbeitbar';
$string['editableforall_help'] = 'Wenn aktiviert können andere Lehrende in die in PINGO eingestellte Session bearbeiten.';

// Strings for index.php.
$string['modulenameplural'] = 'Pingos';
$string['nonewmodules'] = 'Keine neuen Instanzen';

// Strings for lib.php.
$string['deletealluserdata'] = 'Alle Benutzerdaten löschen';

// Strings for the capabilities.
$string['pingo:addinstance'] = 'Neue Pingo Instanz hinzufügen';
$string['pingo:viewsessionsoverview'] = 'Übersicht der PINGO Sessions ansehen';
$string['pingo:logintosession'] = 'PINGO Session starten';
$string['pingo:viewallsessions'] = 'Alle PINGO Sessions ansehen';
$string['pingo:startsurvey'] = 'Umfrage aus PINGO starten';

// Strings for the tasks.
$string['task'] = 'Aufgabe';

// Strings for the admin settings.
$string['connectionsdetails'] = 'Verbindungseinstellungen';
$string['remoteserver'] = 'PINGO-Server';
$string['remoteserverall'] = 'PINGO-Server zu dem sich das Plugin verbinden soll.';

// Strings for the view page.
$string['overview'] = 'Überblick aller PINGO Sessions';
$string['viewallpingos'] = 'Alle PINGO-Instanzen im Kurs ansehen';
$string['loginfailed'] = 'PINGO Login fehlgeschlagen';
$string['loginfailedinvalidcredentials'] = 'PINGO Login fehlgeschlagen (fehlerhafte Anmeldedaten)';
$string['loginsuccessfull'] = 'PINGO Login erfolgreich';
$string['sessionid'] = 'Session ID';
$string['sessiontoken'] = 'Session Token';
$string['sessionactive'] = 'Session aktiv';
$string['nosession'] = 'Keine Session aktiv';
$string['sessionlogout'] = 'Aktuelle Session abmelden';
$string['nosurveyactive'] = 'Keine Umfrage aktiv';

// Strings for the login form.
$string['pingousername'] = 'Der Anmeldename in PINGO';
$string['pingousername_help'] = 'Der Anmeldename des Accounts in PINGO';
$string['pingopassword'] = 'Das Passwort in PINGO';
$string['pingopassword_help'] = 'Das Passwort des Accounts in PINGO';
$string['nopingoyet'] = 'Noch kein Account?';
$string['registerforpingo'] = 'Bei PINGO registrieren (externe Seite)';

// Strings for the events.
$string['eventsessioncreated'] = 'PINGO Session angelegt';
$string['eventpingologinfailed'] = 'PINGO Login fehlgeschlagen';
$string['eventpingologinsuccessful'] = 'PINGO Login erfolgreich';

// Strings for the privacy api.
/*
$string['privacy:metadata:pingo_participants'] = 'Enthält die persönlichen Daten aller Pingo Teilnehmenden.';
$string['privacy:metadata:pingo_submissions'] = 'Enthält alle Daten zu Pingo Einreichungen.';
$string['privacy:metadata:pingo_participants:pingo'] = 'ID des Pingos des Teilnehmers';
$string['privacy:metadata:pingo_submissions:pingo'] = 'ID des Pingos der Einreichung';
$string['privacy:metadata:core_message'] = 'Das Pingo Plugin sendet Nachrichten an Benutzer und speichert deren Inhalte in der Datenbank.';
*/
