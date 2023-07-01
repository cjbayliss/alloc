<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

global $TPL;
$current_user = &singleton('current_user');
$TPL['current_user'] = $current_user;

function check_optional_client_exists()
{
    global $clientID;

    return $clientID;
}

function show_client_contacts()
{
    $url_alloc_client = null;
    $clientContact_clientID = null;
    $clientContactActive_checked = null;
    $sessID = null;
    $clientContacts = null;
    global $TPL;
    global $clientID;

    $TPL['clientContact_clientID'] = $clientID;

    if (isset($_POST['clientContact_delete'], $_POST['clientContactID'])) {
        $clientContact = new clientContact();
        $clientContact->set_id($_POST['clientContactID']);
        $clientContact->delete();
    }

    $client = new client();
    $client->set_id($clientID);
    $client->select();

    $clientContactsQuery = unsafe_prepare(
        'SELECT *
           FROM clientContact
          WHERE clientID=%d
       ORDER BY clientContactActive DESC, primaryContact DESC, clientContactName',
        $clientID
    );

    $database = new AllocDatabase();
    $database->query($clientContactsQuery);

    $buildHTML = [];
    while ($database->next_record()) {
        $clientContact = new clientContact();
        $clientContact->read_db_record($database);

        if (
            isset($_POST['clientContact_edit'])
            && $_POST['clientContactID'] == $clientContact->get_id()
        ) {
            continue;
        }

        $vcardIcon = 'icon_vcard.png';
        if (!$clientContact->get_value('clientContactActive')) {
            $vcardIcon = 'icon_vcard_faded.png';
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

            if (!empty($fieldValue) && 'clientContactName' === $firstColumnContactfield) {
                $primaryContact = '';
                if ($clientContact->get_value('primaryContact')) {
                    $primaryContact = ' [Primary]';
                }

                $generatedFirstColumnHTMLArray[] = <<<HTML
                                    <h2 style='margin:0px; display:inline;'>{$vcardHTML}{$fieldValue}</h2>{$primaryContact}
                    HTML;
            } elseif ('' !== $firstColumnContactfield) {
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

            if (!empty($value) && 'clientContactEmail' === $seconContactColumnField) {
                $value = str_replace(['<', '>', '&lt;', '&gt;'], '', $value);
                $contactName = $clientContact->get_value('clientContactName', DST_HTML_DISPLAY);
                $mailto = rawurlencode($contactName ? sprintf('"%s" <%s>', $contactName, $value) : $value);
                $generatedSecondColumnHTMLArray[] = sprintf("%s: <a href='mailto:%s'>%s</a>", $label, $mailto, $value);
            } elseif (!empty($value)) {
                $generatedSecondColumnHTMLArray[] = sprintf('%s: %s', $label, $value);
            }
        }

        $class_extra = $clientContact->get_value('clientContactActive') ? 'loud' : 'quiet';

        $firstColumnHTML = implode('</span><br><span class="nobr">', $generatedFirstColumnHTMLArray);
        $secondColumnHTML = implode('</span><br><span class="nobr">', $generatedSecondColumnHTMLArray);
        $otherClientContact = nl2br($clientContact->get_value('clientContactOther', DST_HTML_DISPLAY));
        $starredClientContact = Page::star('clientContact', $clientContact->get_id());
        $buttons = <<<'HTML'
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
        $TPL['clientContacts'] = implode("\n", $buildHTML);
    }

    if (isset($_POST['clientContact_edit'], $_POST['clientContactID'])) {
        $clientContact = new clientContact();
        $clientContact->set_id($_POST['clientContactID']);
        $clientContact->select();
        $clientContact->set_values('clientContact_');
        if ($clientContact->get_value('primaryContact')) {
            $TPL['primaryContact_checked'] = ' checked';
        }

        if ($clientContact->get_value('clientContactActive')) {
            $TPL['clientContactActive_checked'] = ' checked';
        }
    } elseif ([] !== $buildHTML) {
        $TPL['class_new_client_contact'] = 'hidden';
    }

    if (!isset($_POST['clientContactID']) || isset($_POST['clientContact_save'])) {
        $TPL['clientContactActive_checked'] = ' checked';
    }

    if (is_array($TPL)) {
        extract($TPL, EXTR_OVERWRITE);
    }

    ?>
<table class="box">
    <tr>
        <th class="header">Client Contacts
            <span>
                <?php echo Page::expand_link('id_new_client_contact', 'New Client Contact'); ?>
            </span>
        </th>
    </tr>
    <tr>
        <td colspan="2">
            <form action="<?php echo $url_alloc_client; ?>"
                method="post">
                <input type="hidden" name="clientContactID"
                    value="<?php $clientContact_clientContactID ?? ''; ?>">
                <input type="hidden" name="clientID"
                    value="<?php echo $clientContact_clientID; ?>">

                <div class="<?php $class_new_client_contact ?? ''; ?>"
                    id="id_new_client_contact">
                    <table width="100%">
                        <tr>
                            <td width="1%">Name</td>
                            <td width="1%"><input type="text" name="clientContactName"
                                    value="<?php $clientContact_clientContactName ?? ''; ?>">
                            </td>
                            <td width="1%">Email</td>
                            <td width="1%"><input type="text" name="clientContactEmail"
                                    value="<?php $clientContact_clientContactEmail ?? ''; ?>">
                            </td>
                            <td>Info</td>
                            <td rowspan="5" class="top right">
                                <?php echo Page::textarea('clientContactOther', $clientContact_clientContactOther ?? '', ['height' => 'medium', 'width' => '100%']); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Address</td>
                            <td><input type="text" name="clientContactStreetAddress"
                                    value="<?php $clientContact_clientContactStreetAddress ?? ''; ?>">
                            </td>
                            <td>Phone</td>
                            <td><input type="text" name="clientContactPhone"
                                    value="<?php $clientContact_clientContactPhone ?? ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Suburb</td>
                            <td><input type="text" name="clientContactSuburb"
                                    value="<?php $clientContact_clientContactSuburb ?? ''; ?>">
                            </td>
                            <td>Mobile</td>
                            <td><input type="text" name="clientContactMobile"
                                    value="<?php $clientContact_clientContactMobile ?? ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>State</td>
                            <td><input type="text" name="clientContactState"
                                    value="<?php $clientContact_clientContactState ?? ''; ?>">
                            </td>
                            <td>Fax</td>
                            <td><input type="text" name="clientContactFax"
                                    value="<?php $clientContact_clientContactFax ?? ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>Postcode</td>
                            <td><input type="text" name="clientContactPostcode"
                                    value="<?php $clientContact_clientContactPostcode ?? ''; ?>">
                            </td>
                            <td class="nobr">Country</td>
                            <td><input type="text" name="clientContactCountry"
                                    value="<?php $clientContact_clientContactCountry ?? ''; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="right">
                                <label for="cca">Enabled</label> <input id="cca" type="checkbox"
                                    name="clientContactActive" value="1"
                                    <?php echo $clientContactActive_checked; ?>>&nbsp;&nbsp;
                                <label for="pcc">Primary Contact</label> <input id="pcc" type="checkbox"
                                    name="primaryContact" value="1"
                                    <?php $primaryContact_checked ?? ''; ?>>
                                <button type="submit" name="clientContact_save" value="1" class="save_button">Save
                                    Client Contact<i class="icon-ok-sign"></i></button>

                            </td>
                        </tr>
                    </table>
                </div>

                <input type="hidden" name="sessID"
                    value="<?php echo $sessID; ?>">
            </form>

        </td>
    </tr>
    <tr>
        <td colspan="2">
            <?php echo $clientContacts; ?>
        </td>
    </tr>
</table>
    <?php
}

function show_attachments()
{
    global $clientID;
    util_show_attachments('client', $clientID);
}

function show_comments()
{
    global $clientID;
    global $TPL;
    global $client;
    $TPL['commentsR'] = comment::util_get_comments('client', $clientID);
    $TPL['commentsR'] && ($TPL['class_new_comment'] = 'hidden');
    $interestedPartyOptions = $client->get_all_parties();
    $interestedPartyOptions = InterestedParty::get_interested_parties(
        'client',
        $client->get_id(),
        $interestedPartyOptions
    );
    ($TPL['allParties'] = $interestedPartyOptions) || ($TPL['allParties'] = []);
    $TPL['entity'] = 'client';
    $TPL['entityID'] = $client->get_id();
    $TPL['clientID'] = $client->get_id();

    $commentTemplate = new commentTemplate();
    $ops = $commentTemplate->get_assoc_array(
        'commentTemplateID',
        'commentTemplateName',
        '',
        ['commentTemplateType' => 'client']
    );
    $TPL['commentTemplateOptions'] =
        sprintf('<option value="">Comment Templates</option>{Page::select_options(%s)}', implode(',', $ops));

    // TODO: remove global variables
    if (is_array($TPL)) {
        extract($TPL, EXTR_OVERWRITE);
    }

    $comment = new comment();
    $comment->commentSectionHTML();
}

function show_invoices()
{
    $_FORM = [];
    $current_user = &singleton('current_user');
    global $clientID;

    $_FORM['showHeader'] = true;
    $_FORM['showInvoiceNumber'] = true;
    $_FORM['showInvoiceClient'] = true;
    $_FORM['showInvoiceName'] = true;
    $_FORM['showInvoiceAmount'] = true;
    $_FORM['showInvoiceAmountPaid'] = true;
    $_FORM['showInvoiceDate'] = true;
    $_FORM['showInvoiceStatus'] = true;
    $_FORM['clientID'] = $clientID;

    // Restrict non-admin users records
    if (!$current_user->have_role('admin')) {
        $_FORM['personID'] = $current_user->get_id();
    }

    $rows = invoice::get_list($_FORM);
    echo invoice::get_list_html($rows, $_FORM);
}

$client = new client();
$clientID = $_POST['clientID'] ?? $_GET['clientID'] ?? '';

if (isset($_POST['save'])) {
    if (!isset($_POST['clientName'])) {
        alloc_error('Please enter a Client Name.');
    }

    $client->read_globals();
    $client->set_value('clientModifiedTime', date('Y-m-d'));
    $clientID = $client->get_id();
    $client->set_values('client_');
    if (!$client->get_id()) {
        // New client.
        $client->set_value('clientCreatedTime', date('Y-m-d'));
        $new_client = true;
    }

    if (!isset($TPL['message'])) {
        $client->save();
        $clientID = $client->get_id();
        $client->set_values('client_');
    }
} elseif (isset($_POST['save_attachment'])) {
    move_attachment('client', $clientID);
    alloc_redirect(sprintf('%sclientID=%s&sbs_link=attachments', $TPL['url_alloc_client'], $clientID));
} else {
    if (isset($_GET['get_vcard'])) {
        $clientContact = new clientContact();
        $clientContact->set_id($_GET['clientContactID']);
        $clientContact->select();
        $clientContact->output_vcard();

        return;
    }

    if (isset($_POST['delete'])) {
        $client->read_globals();
        $client->delete();
        alloc_redirect($TPL['url_alloc_clientList']);
    } else {
        $client->set_id($clientID);
        $client->select();
    }

    $client->set_values('client_');
}

$m = new Meta('clientStatus');
$clientStatus_array = $m->get_assoc_array('clientStatusID', 'clientStatusID');
$TPL['clientStatusOptions'] = Page::select_options(
    $clientStatus_array,
    $client->get_value('clientStatus')
);

$clientCategories = config::get_config_item('clientCategories') ?: [];

foreach ($clientCategories as $clientCategory) {
    $categoryOptions[$clientCategory['value']] = $clientCategory['label'];
}

$selectedCategory = $client->get_value('clientCategory');
$TPL['clientCategoryOptions'] = Page::select_options($categoryOptions, $selectedCategory);

if ($selectedCategory) {
    $TPL['client_clientCategoryLabel'] = $categoryOptions[$selectedCategory];
}

// client contacts
if (isset($_POST['clientContact_save']) || isset($_POST['clientContact_delete'])) {
    $clientContact = new clientContact();
    $clientContact->read_globals();

    if (isset($_POST['clientContact_save'])) {
        $clientContact->save();
    }

    if (isset($_POST['clientContact_delete'])) {
        $clientContact->delete();
    }
}

if (!isset($clientID)) {
    $TPL['message_help'][] =
        'Create a new Client by inputting the Client Name and other details and clicking the Create New Client button.';
    $TPL['main_alloc_title'] = 'New Client - ' . APPLICATION_NAME;
    $TPL['clientSelfLink'] = 'New Client';
} else {
    $TPL['main_alloc_title'] = sprintf('Client %s: %s - ', $client->get_id(), $client->get_name()) . APPLICATION_NAME;
    $TPL['clientSelfLink'] =
        sprintf('<a href="%s">%s %s</a>', $client->get_url(), $client->get_id(), $client->get_name(['return' => 'html']));
}

$TPL['invoice_links'] ??= '';
if ($current_user->have_role('admin')) {
    $TPL['invoice_links'] .= sprintf('<a href="%sclientID=%s">New Invoice</a>', $TPL['url_alloc_invoice'], $clientID);
}

$projectListOps = ['showProjectType' => true, 'clientID' => $client->get_id()];

$TPL['projectListRows'] = project::getFilteredProjectList($projectListOps);

$TPL['client_clientPostalAddress'] = $client->format_address('postal');
$TPL['client_clientStreetAddress'] = $client->format_address('street');

// TODO: remove global variables
if (is_array($TPL)) {
    extract($TPL, EXTR_OVERWRITE);
}

$page = new Page();
echo $page->header();
echo $page->toolbar(); ?>
<script type="text/javascript" language="javascript">
    $(document).ready(function() {
        <?php if (!$client_clientID) { ?>
        toggle_view_edit();
        $('#clientName').focus();
        $("#clientName").on('keyup', function() {
            makeAjaxRequest(
                '<?php echo $url_alloc_updateClientDupes; ?>',
                'clientDupes', {
                    clientName: $("#clientName").val()
                });
        });
        <?php } else { ?>
        $('#editClient').focus();
        <?php }
        ?>
    });
</script>

<?php if (check_optional_client_exists()) { ?>
    <?php $first_div = 'hidden'; ?>
    <?php echo $page->side_by_side_links(
        $url_alloc_client . 'clientID=' . $client_clientID,
        [
            'client'      => 'Main',
            'reminders'   => 'Reminders',
            'comments'    => 'Comments',
            'attachments' => 'Attachments',
            'projects'    => 'Projects',
            'invoices'    => 'Invoices',
            'sales'       => 'Sales',
            'sbsAll'      => 'All',
        ],
        null,
        $clientSelfLink
    ); ?>
<?php }
?>

<?php if (null === parse_url($client_clientURL, PHP_URL_SCHEME)) { ?>
    <?php $client_clientURL = 'http://' . $client_clientURL; ?>
<?php }
?>
<!-- need to merge this style back into the stylesheets -->
<style>
    .task_pane {
        min-width: 400px;
        width: 47%;
        float: left;
        margin: 0px 12px;
        vertical-align: top;
    }
</style>


<div id="client"
    class="<?php $first_div ?? ''; ?>">
    <form action="<?php echo $url_alloc_client; ?>" method=post>
        <input type="hidden" name="clientID"
            value="<?php echo $client_clientID; ?>">

        <table class="box view">
            <tr>
                <th class="header">View Details
                    <span><?php echo $page->star('client', $client_clientID); ?></span>
                </th>
            </tr>
            <tr>
                <td valign="top">
                    <div class="task_pane">
                        <h6>Client
                            Name<?php echo $page->mandatory($client_clientName); ?>
                        </h6>
                        <h2 style="margin-bottom:0px; display:inline;">
                            <?php echo $client_clientID; ?>
                            <?php echo $page->htmlentities($client_clientName); ?>
                        </h2>
                        &nbsp;&nbsp;&nbsp;<?php echo $client_clientStatus; ?>
                        <?php echo $page->htmlentities($client_clientCategoryLabel); ?>
                        <?php if ($client_clientPostalAddress) { ?>
                        <h6>Postal Address</h6>
                            <?php echo $client_clientPostalAddress; ?>
                        <?php }
                        ?>
                    </div>
                    <div class="task_pane">
                        <div class="enclose">
                            <h6>Phone Number<div>Fax Number</div>
                            </h6>
                            <div style="float:left; width:47%;">
                                <?php echo $page->htmlentities($client_clientPhoneOne); ?>
                            </div>
                            <div style="float:right; width:50%;">
                                <?php echo $page->htmlentities($client_clientFaxOne); ?>
                            </div>
                        </div>
                        <?php if ($client_clientStreetAddress) { ?>
                        <h6>Street Address</h6>
                            <?php echo $client_clientStreetAddress; ?>
                        <?php }
                        ?>
                        <?php if ($client_clientURL) { ?>
                        <h6>URL</h6>
                        <a
                            href="<?php echo $page->htmlentities($client_clientURL); ?>"><?php echo $page->htmlentities($client_clientURL); ?></a>
                        <?php }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="center" class="padded">
                    <div style="margin:20px">
                        <button type="button" id="editClient" value="1" onClick="return toggle_view_edit();">Edit
                            Client<i class="icon-edit"></i></button>
                    </div>
                </td>
            </tr>
        </table>

        <table class="box edit">
            <tr>
                <th class="header">Edit Details
                    <span></span>
                </th>
            </tr>
            <tr>
                <td>
                    <div class="task_pane">
                        <h6>Client
                            Name<?php echo $page->mandatory($client_clientName); ?>
                        </h6>
                        <div style="width:100%" class="">
                            <input type="text" size="43" id="clientName" name="clientName"
                                value="<?php echo $client_clientName; ?>"
                                tabindex="1">
                            <select name="clientStatus"
                                tabindex="2"><?php echo $clientStatusOptions; ?></select>
                            <select name="clientCategory"
                                tabindex="3"><?php echo $clientCategoryOptions; ?></select>
                        </div>
                        <h6>Postal Address</h6>
                        <table border="0" cellspacing=0 cellpadding=5 width="100%">
                            <tr>
                                <td>Address</td>
                                <td><input type="text" name="clientStreetAddressOne"
                                        value="<?php echo $client_clientStreetAddressOne; ?>"
                                        size="25" tabindex="5"></td>
                            </tr>
                            <tr>
                                <td>Suburb</td>
                                <td><input type="text" name="clientSuburbOne"
                                        value="<?php echo $client_clientSuburbOne; ?>"
                                        size="25" tabindex="6"></td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td><input type="text" name="clientStateOne"
                                        value="<?php echo $client_clientStateOne; ?>"
                                        size="25" tabindex="7"></td>
                            </tr>
                            <tr>
                                <td>Postcode</td>
                                <td><input type="text" name="clientPostcodeOne"
                                        value="<?php echo $client_clientPostcodeOne; ?>"
                                        size="25" tabindex="8"></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td><input type="text" name="clientCountryOne"
                                        value="<?php echo $client_clientCountryOne; ?>"
                                        size="25" tabindex="9"></td>
                            </tr>
                        </table>

                        <?php if (!$client_clientID) { ?>
                        <h6>Possible Duplicates</h6>
                        <div class="message"
                            style="padding:4px 2px; width:100%; height:70px; border:1px solid #cccccc; overflow:auto;">
                            <div id="clientDupes"></div>
                        </div>
                        <?php }
                        ?>
                    </div>
                    <div class="task_pane">
                        <div class="enclose">
                            <h6>Phone Number<div>Fax Number</div>
                            </h6>
                            <div style="float:left; width:47%;">
                                <input type="text" name="clientPhoneOne"
                                    value="<?php echo $client_clientPhoneOne; ?>"
                                    tabindex="3">
                            </div>
                            <div style="float:right; width:50%;">
                                <input type="text" name="clientFaxOne"
                                    value="<?php echo $client_clientFaxOne; ?>"
                                    tabindex="4">
                            </div>
                        </div>
                        <h6>Street Address</h6>
                        <table border="0" cellspacing=0 cellpadding=5 width="100%">
                            <tr>
                                <td>Address</td>
                                <td><input type="text" name="clientStreetAddressTwo"
                                        value="<?php echo $client_clientStreetAddressTwo; ?>"
                                        size="25" tabindex="10"></td>
                            </tr>
                            <tr>
                                <td>Suburb</td>
                                <td><input type="text" name="clientSuburbTwo"
                                        value="<?php echo $client_clientSuburbTwo; ?>"
                                        size="25" tabindex="11"></td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td><input type="text" name="clientStateTwo"
                                        value="<?php echo $client_clientStateTwo; ?>"
                                        size="25" tabindex="12"></td>
                            </tr>
                            <tr>
                                <td>Postcode</td>
                                <td><input type="text" name="clientPostcodeTwo"
                                        value="<?php echo $client_clientPostcodeTwo; ?>"
                                        size="25" tabindex="13"></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td><input type="text" name="clientCountryTwo"
                                        value="<?php echo $client_clientCountryTwo; ?>"
                                        size="25" tabindex="14"></td>
                            </tr>
                        </table>
                        <h6>URL</h6>
                        <input type="text" name="clientURL"
                            value="<?php echo $client_clientURL; ?>"
                            style="width:100%;" tabindex="15">
                    </div>
                </td>
            </tr>
            <tr>
                <td align="center" class="padded">
                    <div style="margin:20px">
                        <!-- IMPORANT: this is to make 'save' the default action -->
                        <button hidden type="submit" name="save" value="1" class="save_button">Save<i
                                class="icon-ok-sign"></i></button>
                        <?php if ($client_clientID) { ?>
                        <button type="submit" name="delete" value="1" class="delete_button">Delete<i
                                class="icon-trash"></i></button>
                        <?php }
                        ?>
                        <button type="submit" name="save" value="1" class="save_button">Save<i
                                class="icon-ok-sign"></i></button>
                        <?php if ($client_clientID) { ?>
                        <br><br>
                        &nbsp;&nbsp;<a href="" onClick="return toggle_view_edit(true);">Cancel edit</a>
                        <?php }
                        ?>
                    </div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="sessID"
            value="<?php echo $sessID; ?>">
    </form>

    <?php if (check_optional_client_exists()) { ?>
        <?php show_client_contacts(); ?>
    <?php }
    ?>

</div>

<?php if (check_optional_client_exists()) { ?>
<div id="reminders">
    <table class="box">
        <tr>
            <th class="header">Reminders
                <span>
                    <a
                        href="<?php echo $url_alloc_reminder; ?>step=3&parentType=client&parentID=<?php echo $client_clientID; ?>&returnToParent=client">Add
                        Reminder</a>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <?php reminder::get_list_html('client', $client_clientID); ?>
            </td>
        </tr>
    </table>
</div>

<div id="comments">
    <?php show_comments(); ?>
</div>

<div id="attachments">
    <?php show_attachments(); ?>
</div>

<div id="projects">
    <table class="box">
        <tr>
            <th class="header">Projects
                <span>
                    <a
                        href="<?php echo $url_alloc_project; ?>clientID=<?php echo $client_clientID; ?>">New
                        Project</a>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <?php if ($projectListRows) { ?>
                <table class="list sortable">
                    <tr>
                        <th>Project</th>
                        <th>Nick</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="noprint">&nbsp;</th>
                    </tr>
                    <?php foreach ($projectListRows as $projectListRow) { ?>
                    <tr>
                        <td><?php echo $projectListRow['projectLink']; ?>
                        </td>
                        <td><?php echo $page->htmlentities($projectListRow['projectShortName']); ?>
                        </td>
                        <td><?php echo $page->htmlentities($projectListRow['clientName']); ?>
                        </td>
                        <td><?php echo $page->htmlentities($projectListRow['projectType']); ?>
                        </td>
                        <td><?php echo $page->htmlentities($projectListRow['projectStatus']); ?>
                        </td>
                        <td class="noprint" align="right">
                            <?php echo $projectListRow['navLinks']; ?>
                        </td>
                    </tr>
                    <?php }
                    ?>
                </table>
                <?php } else { ?>
                <b>No Projects Found.</b>
                <?php }
                ?>
            </td>
        </tr>
    </table>
</div>

<div id="invoices">
    <table class="box">
        <tr>
            <th class="header">Invoices
                <span>
                    <?php echo $invoice_links; ?>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <?php show_invoices(); ?>
            </td>
        </tr>
    </table>
</div>

<div id="sales">
    <table class="box">
        <tr>
            <th class="header">Sales
                <span>
                    <a
                        href="<?php echo $url_alloc_productSale; ?>clientID=<?php echo $client_clientID; ?>">New
                        Sale</a>
                </span>
            </th>
        </tr>
        <tr>
            <td>
                <?php $productSaleRows = productSale::get_list(['clientID' => $client_clientID]); ?>
                <?php echo productSale::get_list_html($productSaleRows); ?>
            </td>
        </tr>
    </table>
</div>

    <?php
}

echo $page->footer(); ?>