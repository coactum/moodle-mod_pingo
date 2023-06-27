# License #

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.

2023 coactum GmbH

# PINGO #

## Description ##

The PINGO activity enables the integration of the free-to-use classroom response system PINGO into Moodle.

PINGO allows the easy collection of live feedback and can be used in a variety of ways in teaching. Surveys can be conveniently prepared in advance and then quickly made available to the entire audience via their mobile devices during the lecture.

This activity enables the integration of PINGO with Moodle. It allows teachers to log in to PINGO directly in the activity and then access their sessions created in PINGO, view them, add surveys to them, and then launch them.
Participants can then view the surveys directly in the activity. For additional actions, such as creating new sessions or questions, the activity also redirects to the web version of PINGO.

Teachers can ...

- conveniently log into PINGO
- view all sessions created in PINGO
- add and launch quick surveys and questions from the question catalogue to a session
- view individual sessions and the last active survey in each session

Students can ...

- view the active session and open the survey there for voting

## Use PINGO

Both the plugin and the PINGO service can be used free of charge and without restrictions.

All a teacher needs to do is to have a PINGO account and log in to the plugin with it. A PINGO account can be easily created at https://pingo.coactum.de/users/sign_up.

If your organization has opted for the paid upgrade to PINGOplus (https://coactum.de/pingoplus) and thus benefits from additional advantages such as an individual design and its own domain, the remote url may have to be adjusted in the plugin settings. In this case, teachers may also need to register under the changed domain to create an account.

PINGO is a service provided by the coactum GmbH (https://coactum.de/imprint) for which the following privacy policy (https://pingo.coactum.de/privacy_policy.html) applies.

## Quick installation instructions ##

### Install from git ###
- Navigate to Moodle root folder.
- **git clone https://github.com/coactum/moodle-mod_pingo.git mod/pingo**

### Install from a compressed file ###
- Extract the compressed file data.
- Rename the main folder to pingo.
- Copy to the Moodle mod/ folder.
- Click the 'Notifications' link on the frontpage administration block.

## Dependencies ##
No dependencies.