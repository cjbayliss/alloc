<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once(__DIR__ . "/../alloc.php");

function check_optional_client_exists()
{
    global $clientID;
    return $clientID;
}

function show_client_contacts()
{
    global $TPL;
    global $clientID;

    $TPL["clientContact_clientID"] = $clientID;

    if ($_POST["clientContact_delete"] && $_POST["clientContactID"]) {
        $clientContact = new clientContact();
        $clientContact->set_id($_POST["clientContactID"]);
        $clientContact->delete();
    }

    $client = new client();
    $client->set_id($clientID);
    $client->select();

    $clientContactsQuery = unsafe_prepare(
        "SELECT *
           FROM clientContact
          WHERE clientID=%d
       ORDER BY clientContactActive DESC, primaryContact DESC, clientContactName",
        $clientID
    );

    $database = new AllocDatabase();
    $database->query($clientContactsQuery);

    $buildHTML = [];
    while ($database->next_record()) {
        $clientContact = new clientContact();
        $clientContact->read_db_record($database);

        if (
            $_POST["clientContact_edit"] &&
            $_POST["clientContactID"] == $clientContact->get_id()
        ) {
            continue;
        }

        $vcardIcon = "icon_vcard.png";
        if (!$clientContact->get_value("clientContactActive")) {
            $vcardIcon = "icon_vcard_faded.png";
        }

        $vcardHTML = <<<HTML
                    <a href="{$TPL['url_alloc_client']}clientContactID={$clientContact->get_id()}&get_vcard=1"><img style="vertical-align:middle; padding:3px 6px 3px 3px;border: none" src="{$TPL['url_alloc_images']}{$vcardIcon}" alt="Download VCard" ></a>
            HTML;

        $firstColumnContactfields = [
            'clientContactName',
            'clientContactStreetAddress',
            'clientContactSuburb',
            'clientContactState',
            'clientContactPostcode',
            'clientContactCountry',
        ];

        $generatedFirstColumnHTMLArray = [];
        foreach ($firstColumnContactfields as $firstColumnContactfield) {
            $fieldValue = $clientContact->get_value($firstColumnContactfield, DST_HTML_DISPLAY);

            if (!empty($fieldValue) && $firstColumnContactfield === 'clientContactName') {
                $primaryContact = '';
                if ($clientContact->get_value("primaryContact")) {
                    $primaryContact = " [Primary]";
                }

                $generatedFirstColumnHTMLArray[] = <<<HTML
                                    <h2 style='margin:0px; display:inline;'>{$vcardHTML}{$fieldValue}</h2>{$primaryContact}
                    HTML;
            } elseif ($firstColumnContactfield !== '') {
                $generatedFirstColumnHTMLArray[] = $fieldValue;
            }
        }

        $seconContactColumnFields = [
            'clientContactEmail',
            'clientContactName',
            'clientContactPhone',
            'clientContactMobile',
            'clientContactFax',
        ];

        $generatedSecondColumnHTMLArray = [];
        foreach ($seconContactColumnFields as $seconContactColumnField) {
            $value = $clientContact->get_value($seconContactColumnField, DST_HTML_DISPLAY);
            // get first letter of field type, e.g. P for clientContactPhone
            $label = strtoupper(str_replace('clientContact', '', $seconContactColumnField)[0]);

            if (!empty($value) && $seconContactColumnField === 'clientContactEmail') {
                $value = str_replace(['<', '>', '&lt;', '&gt;'], '', $value);
                $contactName = $clientContact->get_value('clientContactName', DST_HTML_DISPLAY);
                $mailto = rawurlencode($contactName ? sprintf('"%s" <%s>', $contactName, $value) : $value);
                $generatedSecondColumnHTMLArray[] = sprintf("%s: <a href='mailto:%s'>%s</a>", $label, $mailto, $value);
            } elseif (!empty($value)) {
                $generatedSecondColumnHTMLArray[] = sprintf('%s: %s', $label, $value);
            }
        }

        $class_extra = $clientContact->get_value("clientContactActive") ? "loud" : "quiet";

        $firstColumnHTML = implode('</span><br><span class="nobr">', $generatedFirstColumnHTMLArray);
        $secondColumnHTML = implode('</span><br><span class="nobr">', $generatedSecondColumnHTMLArray);
        $otherClientContact = nl2br($clientContact->get_value('clientContactOther', DST_HTML_DISPLAY));
        $starredClientContact = Page::star("clientContact", $clientContact->get_id());
        $buttons = <<<HTML
                    <nobr>
                        <button type="submit" name="clientContact_delete" value="1" class="delete_button">Delete<i class="icon-trash"></i></button>
                        <button type="submit" name="clientContact_edit" value="1"">Edit<i class="icon-edit"></i></button>
                    </nobr> 
            HTML;

        $buildHTML[] = <<<HTML
                        <form action="{$TPL['url_alloc_client']}" method="post">
                        <input type="hidden" name="clientContactID" value="{$clientContact->get_id()}">
                        <input type="hidden" name="clientID" value="{$clientID}">
                        <div class="panel {$class_extra} corner">
                        <table width="100%" cellspacing="0" border="0">
                            <tr>
                                <td width="25%" valign="top"><span class="nobr">{$firstColumnHTML}</span>&nbsp;</td>
                                <td width="20%" valign="top"><span class="nobr">{$secondColumnHTML}</span>&nbsp;</td>
                                <td width="50%" align="left" valign="top">{$otherClientContact}&nbsp;</td>
                                <td align="right" class="right nobr">{$buttons}</td>
                                <td align="right" class="right nobr" width="1%">{$starredClientContact}</td>
                            </tr>
                        </table>
                        </div>
                        <input type="hidden" name="sessID" value="{$TPL['sessID']}">
                        </form>
            HTML;
    }

    if (is_array($buildHTML)) {
        $TPL["clientContacts"] = implode("\n", $buildHTML);
    }

    if ($_POST["clientContact_edit"] && $_POST["clientContactID"]) {
        $clientContact = new clientContact();
        $clientContact->set_id($_POST["clientContactID"]);
        $clientContact->select();
        $clientContact->set_values("clientContact_");
        if ($clientContact->get_value("primaryContact")) {
            $TPL["primaryContact_checked"] = " checked";
        }

        if ($clientContact->get_value("clientContactActive")) {
            $TPL["clientContactActive_checked"] = " checked";
        }
    } elseif ($buildHTML !== []) {
        $TPL["class_new_client_contact"] = "hidden";
    }

    if (!$_POST["clientContactID"] || $_POST["clientContact_save"]) {
        $TPL["clientContactActive_checked"] = " checked";
    }

    include_template("templates/clientContactM.tpl");
}

function show_attachments()
{
    global $clientID;
    util_show_attachments("client", $clientID);
}

function show_comments()
{
    global $clientID;
    global $TPL;
    global $client;
    $TPL["commentsR"] = comment::util_get_comments("client", $clientID);
    $TPL["commentsR"] && ($TPL["class_new_comment"] = "hidden");
    $interestedPartyOptions = $client->get_all_parties();
    $interestedPartyOptions = InterestedParty::get_interested_parties(
        "client",
        $client->get_id(),
        $interestedPartyOptions
    );
    ($TPL["allParties"] = $interestedPartyOptions) || ($TPL["allParties"] = []);
    $TPL["entity"] = "client";
    $TPL["entityID"] = $client->get_id();
    $TPL["clientID"] = $client->get_id();

    $commentTemplate = new commentTemplate();
    $ops = $commentTemplate->get_assoc_array(
        "commentTemplateID",
        "commentTemplateName",
        "",
        ["commentTemplateType" => "client"]
    );
    $TPL["commentTemplateOptions"] =
        sprintf('<option value="">Comment Templates</option>{Page::select_options(%s)}', $ops);
    include_template("../comment/templates/commentM.tpl");
}

function show_invoices()
{
    $_FORM = [];
    $current_user = &singleton("current_user");
    global $clientID;

    $_FORM["showHeader"] = true;
    $_FORM["showInvoiceNumber"] = true;
    $_FORM["showInvoiceClient"] = true;
    $_FORM["showInvoiceName"] = true;
    $_FORM["showInvoiceAmount"] = true;
    $_FORM["showInvoiceAmountPaid"] = true;
    $_FORM["showInvoiceDate"] = true;
    $_FORM["showInvoiceStatus"] = true;
    $_FORM["clientID"] = $clientID;

    // Restrict non-admin users records
    if (!$current_user->have_role("admin")) {
        $_FORM["personID"] = $current_user->get_id();
    }

    $rows = invoice::get_list($_FORM);
    echo invoice::get_list_html($rows, $_FORM);
}

$client = new client();
($clientID = $_POST["clientID"]) || ($clientID = $_GET["clientID"]);

if ($_POST["save"]) {
    if (!$_POST["clientName"]) {
        alloc_error("Please enter a Client Name.");
    }

    $client->read_globals();
    $client->set_value("clientModifiedTime", date("Y-m-d"));
    $clientID = $client->get_id();
    $client->set_values("client_");
    if (!$client->get_id()) {
        // New client.
        $client->set_value("clientCreatedTime", date("Y-m-d"));
        $new_client = true;
    }

    if (!$TPL["message"]) {
        $client->save();
        $clientID = $client->get_id();
        $client->set_values("client_");
    }
} elseif ($_POST["save_attachment"]) {
    move_attachment("client", $clientID);
    alloc_redirect(sprintf('%sclientID=%s&sbs_link=attachments', $TPL['url_alloc_client'], $clientID));
} else {
    if ($_GET["get_vcard"]) {
        $clientContact = new clientContact();
        $clientContact->set_id($_GET["clientContactID"]);
        $clientContact->select();
        $clientContact->output_vcard();
        return;
    }

    if ($_POST["delete"]) {
        $client->read_globals();
        $client->delete();
        alloc_redirect($TPL["url_alloc_clientList"]);
    } else {
        $client->set_id($clientID);
        $client->select();
    }

    $client->set_values("client_");
}

$m = new Meta("clientStatus");
$clientStatus_array = $m->get_assoc_array("clientStatusID", "clientStatusID");
$TPL["clientStatusOptions"] = Page::select_options(
    $clientStatus_array,
    $client->get_value("clientStatus")
);

$clientCategories = config::get_config_item("clientCategories") ?: [];

foreach ($clientCategories as $clientCategory) {
    $categoryOptions[$clientCategory["value"]] = $clientCategory["label"];
}

$selectedCategory = $client->get_value("clientCategory");
$TPL["clientCategoryOptions"] = Page::select_options($categoryOptions, $selectedCategory);

if ($selectedCategory) {
    $TPL["client_clientCategoryLabel"] = $categoryOptions[$selectedCategory];
}

// client contacts
if ($_POST["clientContact_save"] || $_POST["clientContact_delete"]) {
    $clientContact = new clientContact();
    $clientContact->read_globals();

    if ($_POST["clientContact_save"]) {
        $clientContact->save();
    }

    if ($_POST["clientContact_delete"]) {
        $clientContact->delete();
    }
}

if (!$clientID) {
    $TPL["message_help"][] =
        "Create a new Client by inputting the Client Name and other details and clicking the Create New Client button.";
    $TPL["main_alloc_title"] = "New Client - " . APPLICATION_NAME;
    $TPL["clientSelfLink"] = "New Client";
} else {
    $TPL["main_alloc_title"] = sprintf('Client %s: %s - ', $client->get_id(), $client->get_name()) . APPLICATION_NAME;
    $TPL["clientSelfLink"] =
        sprintf('<a href="%s">%s %s</a>', $client->get_url(), $client->get_id(), $client->get_name(["return" => "html"]));
}

if ($current_user->have_role("admin")) {
    $TPL["invoice_links"] .= sprintf('<a href="%sclientID=%s">New Invoice</a>', $TPL['url_alloc_invoice'], $clientID);
}

$projectListOps = ["showProjectType" => true, "clientID" => $client->get_id()];

$TPL["projectListRows"] = project::getFilteredProjectList($projectListOps);

$TPL["client_clientPostalAddress"] = $client->format_address("postal");
$TPL["client_clientStreetAddress"] = $client->format_address("street");

include_template("templates/clientM.tpl");
