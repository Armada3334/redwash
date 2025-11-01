<?php
/*
 * firewall_rules.php
 *
 * Replacement to display a GIF while retaining the pfSense UI.
 * Copyright (c) 2024 ChatGPT
 * All rights reserved.
 */

##|+PRIV
##|*IDENT=page-firewall-rules
##|*NAME=Firewall: Rules
##|*DESCR=Allow access to the 'Firewall: Rules' page.
##|*MATCH=firewall_rules.php*
##|-PRIV

require_once("guiconfig.inc");

$pgtitle = array(gettext("Firewall"), gettext("Rules"));
$shortcut_section = "firewall";

include("head.inc");

?>

<style>
    .gif-container {
        text-align: center;
        margin-top: 20px;
    }
    .gif-container img {
        max-width: 100%;
        height: auto;
        border: 5px solid #ff6347;
        border-radius: 12px;
    }
</style>

<section class="page-content-main">
    <div class="container-fluid">
        <div class="row">
            <section class="col-xs-12">
                <div class="gif-container">
                    <h2>Magic Word Required</h2>
                    <img src="https://media.giphy.com/media/3ohzdQ1IynzclJldUQ/giphy.gif" alt="The Magic Word GIF">
                </div>
            </section>
        </div>
    </div>
</section>

<?php include("foot.inc"); ?>
