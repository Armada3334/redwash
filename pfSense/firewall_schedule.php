<?php
/*
 * firewall_schedule.php
 *
 * Replacement to display fun content while retaining the pfSense UI.
 * Copyright (c) 2024 ChatGPT
 * All rights reserved.
 */

##|+PRIV
##|*IDENT=page-firewall-schedules
##|*NAME=Firewall: Schedules
##|*DESCR=Allow access to the 'Firewall: Schedules' page.
##|*MATCH=firewall_schedule.php*
##|-PRIV

require_once("guiconfig.inc");

$pgtitle = array(gettext("Firewall"), gettext("Schedules"));
$shortcut_section = "firewall";

include("head.inc");

?>

<style>
    /* Rainbow text effect */
    .rainbow-text {
        font-size: 3rem;
        font-weight: bold;
        background-image: linear-gradient(to right, red, orange, yellow, green, cyan, blue, violet);
        -webkit-background-clip: text;
        color: transparent;
        text-align: center;
        margin-top: 20px;
    }

    /* Subtitle styling */
    .subtitle {
        font-size: 1.5rem;
        color: #666;
        text-align: center;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    /* Container for GIF */
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

    /* Centering content */
    .content-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
    }
</style>

<section class="page-content-main">
    <div class="container-fluid">
        <div class="row">
            <section class="col-xs-12">
                <div class="content-container">
                    <div class="rainbow-text">
                        You came here because you're SPECIAL
                    </div>
                    <div class="subtitle">
                        And because you are randomly clicking around, why are you here?
                    </div>
                    <div class="gif-container">
                        <img src="https://media.tenor.com/lNFGOU9RyHkAAAAM/trump-donald-trump.gif" alt="Trump GIF">
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

<?php include("foot.inc"); ?>
