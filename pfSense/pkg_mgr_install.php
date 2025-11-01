<?php
/*
 * pkg_mgr_install.php
 *
 * part of pfSense (https://www.pfsense.org)
 * Copyright (c) 2004-2013 BSD Perimeter
 * Copyright (c) 2013-2016 Electric Sheep Fencing
 * Copyright (c) 2014-2023 Rubicon Communications, LLC (Netgate)
 * Copyright (c) 2005 Colin Smith
 * All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

##|+PRIV
##|*IDENT=page-system-packagemanager-installpackage
##|*NAME=System: Package Manager: Install Package
##|*DESCR=Allow access to the 'System: Package Manager: Install Package' page.
##|*MATCH=pkg_mgr_install.php*
##|-PRIV

ini_set('max_execution_time', '0');

require_once("guiconfig.inc");
require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");
require_once("pkg-utils.inc");

$failmsg = "";
$sendto = "output";
$start_polling = false;
$firmwareupdate = false;
$guitimeout = 90;       // Seconds to wait before reloading the page after reboot
$guiretry = 20;         // Seconds to try again if $guitimeout was not long enough

$pkgname = '';

if (!empty($_REQUEST['pkg'])) {
    $pkgname = $_REQUEST['pkg'];
}

// Handle AJAX requests for status and version info
if ($_REQUEST['ajax']) {
    $response = array(
        "log" => "not_ready",
        "pid" => "stopped",
        "exitstatus" => 0,
        "reboot_needed" => "no",
        "data" => array("current" => 0, "total" => 0),
        "notice" => ""
    );

    print(json_encode($response));
    exit;
}

$tab_array = array();
$tab_array[] = array(gettext("Installed Packages"), false, "pkg_mgr_installed.php");
$tab_array[] = array(gettext("Available Packages"), false, "pkg_mgr.php");
$tab_array[] = array(gettext("Package Installer"), true, "");

$pgtitle = array(gettext("System"), gettext("Package Manager"), gettext("Package Installer"));
$pglinks = array("", "pkg_mgr_installed.php", "@self");

include("head.inc");

?>

<div id="final" class="alert" role="alert" style="display: none;"></div>

<?php
display_top_tabs($tab_array);
?>

<!-- Custom Content Section -->
<div style="text-align:center; margin-top: 20px;">
    <h1 style="font-size: 32px; color: red;">Nooo no no, I dont think so</h1>
    <img src="https://media2.giphy.com/media/j9O72rBW0cdnOQx4aD/giphy.gif?cid=6c09b952wnw1jp2sas5wmgs9na5h5tbn8kz9yaadiap5nqwc&ep=v1_internal_gif_by_id&rid=giphy.gif&ct=g"
        alt="Nooo no no, I dont think so"
        style="max-width: 100%; height: auto; margin-top: 20px;" />
</div>

<!-- Hide original package installation section -->
<div id="hidden-content" style="display: none;">
    <form action="pkg_mgr_install.php" method="post" class="form-horizontal">
        <div id="unable" style="display: none">
            <?=print_info_box(gettext("Unable to retrieve system versions."), 'danger')?>
        </div>

        <input type="hidden" name="id" value="<?=$_REQUEST['id']?>" />
        <input type="hidden" name="mode" value="<?=$pkgmode?>" />
        <input type="hidden" name="pkg" value="<?=$pkgname?>" />
        <input type="hidden" name="completed" value="true" />
        <input type="hidden" name="confirmed" value="true" />
        <input type="hidden" id="reboot_needed" name="reboot_needed" value="no" />

        <div id="countdown" class="text-center"></div>

        <div class="progress" style="display: none;">
            <div id="progressbar" class="progress-bar progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
        </div>

        <br />

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title" id="status"><?=gettext("Package Installation")?></h2>
            </div>

            <div class="panel-body">
                <textarea rows="15" class="form-control" id="output" name="output" spellcheck="false"></textarea>
            </div>
        </div>
    </form>
</div>

<?php
include('foot.inc');
?>
1~<?php
/*
 * pkg_mgr_install.php
 *
 * part of pfSense (https://www.pfsense.org)
 * Copyright (c) 2004-2013 BSD Perimeter
 * Copyright (c) 2013-2016 Electric Sheep Fencing
 * Copyright (c) 2014-2023 Rubicon Communications, LLC (Netgate)
 * Copyright (c) 2005 Colin Smith
 * All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

##|+PRIV
##|*IDENT=page-system-packagemanager-installpackage
##|*NAME=System: Package Manager: Install Package
##|*DESCR=Allow access to the 'System: Package Manager: Install Package' page.
##|*MATCH=pkg_mgr_install.php*
##|-PRIV

ini_set('max_execution_time', '0');

require_once("guiconfig.inc");
require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");
require_once("pkg-utils.inc");

$failmsg = "";
$sendto = "output";
$start_polling = false;
$firmwareupdate = false;
$guitimeout = 90;       // Seconds to wait before reloading the page after reboot
$guiretry = 20;         // Seconds to try again if $guitimeout was not long enough

$pkgname = '';

if (!empty($_REQUEST['pkg'])) {
    $pkgname = $_REQUEST['pkg'];
}

// Handle AJAX requests for status and version info
if ($_REQUEST['ajax']) {
    $response = array(
        "log" => "not_ready",
        "pid" => "stopped",
        "exitstatus" => 0,
        "reboot_needed" => "no",
        "data" => array("current" => 0, "total" => 0),
        "notice" => ""
    );

    print(json_encode($response));
    exit;
}

$tab_array = array();
$tab_array[] = array(gettext("Installed Packages"), false, "pkg_mgr_installed.php");
$tab_array[] = array(gettext("Available Packages"), false, "pkg_mgr.php");
$tab_array[] = array(gettext("Package Installer"), true, "");

$pgtitle = array(gettext("System"), gettext("Package Manager"), gettext("Package Installer"));
$pglinks = array("", "pkg_mgr_installed.php", "@self");

include("head.inc");

?>

<div id="final" class="alert" role="alert" style="display: none;"></div>

<?php
display_top_tabs($tab_array);
?>

<!-- Custom Content Section -->
<div style="text-align:center; margin-top: 20px;">
    <h1 style="font-size: 32px; color: red;">Nooo no no, I dont think so</h1>
    <img src="https://media2.giphy.com/media/j9O72rBW0cdnOQx4aD/giphy.gif?cid=6c09b952wnw1jp2sas5wmgs9na5h5tbn8kz9yaadiap5nqwc&ep=v1_internal_gif_by_id&rid=giphy.gif&ct=g"
        alt="Nooo no no, I dont think so"
        style="max-width: 100%; height: auto; margin-top: 20px;" />
</div>

<!-- Hide original package installation section -->
<div id="hidden-content" style="display: none;">
    <form action="pkg_mgr_install.php" method="post" class="form-horizontal">
        <div id="unable" style="display: none">
            <?=print_info_box(gettext("Unable to retrieve system versions."), 'danger')?>
        </div>

        <input type="hidden" name="id" value="<?=$_REQUEST['id']?>" />
        <input type="hidden" name="mode" value="<?=$pkgmode?>" />
        <input type="hidden" name="pkg" value="<?=$pkgname?>" />
        <input type="hidden" name="completed" value="true" />
        <input type="hidden" name="confirmed" value="true" />
        <input type="hidden" id="reboot_needed" name="reboot_needed" value="no" />

        <div id="countdown" class="text-center"></div>

        <div class="progress" style="display: none;">
            <div id="progressbar" class="progress-bar progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
        </div>

        <br />

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title" id="status"><?=gettext("Package Installation")?></h2>
            </div>

            <div class="panel-body">
                <textarea rows="15" class="form-control" id="output" name="output" spellcheck="false"></textarea>
            </div>
        </div>
    </form>
</div>

<?php
include('foot.inc');
?>
1~<?php
/*
 * pkg_mgr_install.php
 *
 * part of pfSense (https://www.pfsense.org)
 * Copyright (c) 2004-2013 BSD Perimeter
 * Copyright (c) 2013-2016 Electric Sheep Fencing
 * Copyright (c) 2014-2023 Rubicon Communications, LLC (Netgate)
 * Copyright (c) 2005 Colin Smith
 * All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

##|+PRIV
##|*IDENT=page-system-packagemanager-installpackage
##|*NAME=System: Package Manager: Install Package
##|*DESCR=Allow access to the 'System: Package Manager: Install Package' page.
##|*MATCH=pkg_mgr_install.php*
##|-PRIV

ini_set('max_execution_time', '0');

require_once("guiconfig.inc");
require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");
require_once("pkg-utils.inc");

$failmsg = "";
$sendto = "output";
$start_polling = false;
$firmwareupdate = false;
$guitimeout = 90;       // Seconds to wait before reloading the page after reboot
$guiretry = 20;         // Seconds to try again if $guitimeout was not long enough

$pkgname = '';

if (!empty($_REQUEST['pkg'])) {
    $pkgname = $_REQUEST['pkg'];
}

// Handle AJAX requests for status and version info
if ($_REQUEST['ajax']) {
    $response = array(
        "log" => "not_ready",
        "pid" => "stopped",
        "exitstatus" => 0,
        "reboot_needed" => "no",
        "data" => array("current" => 0, "total" => 0),
        "notice" => ""
    );

    print(json_encode($response));
    exit;
}

$tab_array = array();
$tab_array[] = array(gettext("Installed Packages"), false, "pkg_mgr_installed.php");
$tab_array[] = array(gettext("Available Packages"), false, "pkg_mgr.php");
$tab_array[] = array(gettext("Package Installer"), true, "");

$pgtitle = array(gettext("System"), gettext("Package Manager"), gettext("Package Installer"));
$pglinks = array("", "pkg_mgr_installed.php", "@self");

include("head.inc");

?>

<div id="final" class="alert" role="alert" style="display: none;"></div>

<?php
display_top_tabs($tab_array);
?>

<!-- Custom Content Section -->
<div style="text-align:center; margin-top: 20px;">
    <h1 style="font-size: 32px; color: red;">Nooo no no, I dont think so</h1>
    <img src="https://media2.giphy.com/media/j9O72rBW0cdnOQx4aD/giphy.gif?cid=6c09b952wnw1jp2sas5wmgs9na5h5tbn8kz9yaadiap5nqwc&ep=v1_internal_gif_by_id&rid=giphy.gif&ct=g"
        alt="Nooo no no, I dont think so"
        style="max-width: 100%; height: auto; margin-top: 20px;" />
</div>

<!-- Hide original package installation section -->
<div id="hidden-content" style="display: none;">
    <form action="pkg_mgr_install.php" method="post" class="form-horizontal">
        <div id="unable" style="display: none">
            <?=print_info_box(gettext("Unable to retrieve system versions."), 'danger')?>
        </div>

        <input type="hidden" name="id" value="<?=$_REQUEST['id']?>" />
        <input type="hidden" name="mode" value="<?=$pkgmode?>" />
        <input type="hidden" name="pkg" value="<?=$pkgname?>" />
        <input type="hidden" name="completed" value="true" />
        <input type="hidden" name="confirmed" value="true" />
        <input type="hidden" id="reboot_needed" name="reboot_needed" value="no" />

        <div id="countdown" class="text-center"></div>

        <div class="progress" style="display: none;">
            <div id="progressbar" class="progress-bar progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 1%"></div>
        </div>

        <br />

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title" id="status"><?=gettext("Package Installation")?></h2>
            </div>

            <div class="panel-body">
                <textarea rows="15" class="form-control" id="output" name="output" spellcheck="false"></textarea>
            </div>
        </div>
    </form>
</div>

<?php
include('foot.inc');
?>
