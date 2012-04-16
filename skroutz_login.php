<?php // vim: set ft=php et ts=4 sts=4 sw=4 ai si:
// login
if ($_POST['mytype'] == 1){

    require('includes/application_top.php');

    // redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
    if ($session_started == false) {
        tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

    $error = false;
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);

    // Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");

    if (!tep_db_num_rows($check_customer_query)) {
        $error = true;
    } else {
        $check_customer = tep_db_fetch_array($check_customer_query);

        if (SESSION_RECREATE == 'True') {
            tep_session_recreate();
        }

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);
        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");
        // reset session token
        $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());
        if (sizeof($navigation->snapshot) > 0) {
            $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
            $navigation->clear_snapshot();
            tep_redirect($origin_href);
        } else {
            tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }
    }

    if ($error == true) {
        $messageStack->add('login', TEXT_LOGIN_ERROR);
    }

// create user
} elseif($_POST['mytype'] == 2) {

    require('includes/application_top.php');

    // needs to be included earlier to set the success message in the messageStack
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

    $process = true;

    if (ACCOUNT_GENDER == 'true') {
        if (isset($HTTP_POST_VARS['gender'])) {
            $gender = tep_db_prepare_input($HTTP_POST_VARS['gender']);
        } else {
            $gender = false;
        }
    }
    $firstname = tep_db_prepare_input($HTTP_POST_VARS['firstname']);
    $lastname = tep_db_prepare_input($HTTP_POST_VARS['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($HTTP_POST_VARS['dob']);
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($HTTP_POST_VARS['company']);
    $street_address = tep_db_prepare_input($HTTP_POST_VARS['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($HTTP_POST_VARS['suburb']);
    $postcode = tep_db_prepare_input($HTTP_POST_VARS['postcode']);
    $city = tep_db_prepare_input($HTTP_POST_VARS['city']);
    if (ACCOUNT_STATE == 'true') {
        $state = tep_db_prepare_input($HTTP_POST_VARS['state']);
        if (isset($HTTP_POST_VARS['zone_id'])) {
            $zone_id = tep_db_prepare_input($HTTP_POST_VARS['zone_id']);
        } else {
            $zone_id = false;
        }
    }
    $country = tep_db_prepare_input($HTTP_POST_VARS['country']);
    $telephone = tep_db_prepare_input($HTTP_POST_VARS['telephone']);
    $fax = tep_db_prepare_input($HTTP_POST_VARS['fax']);
    if (isset($HTTP_POST_VARS['newsletter'])) {
        $newsletter = tep_db_prepare_input($HTTP_POST_VARS['newsletter']);
    } else {
        $newsletter = false;
    }
    $password = tep_db_prepare_input(substr($email_address, 0, strpos($email_address, '@')).
        str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT));

    $sql_data_array = array(
        'customers_firstname' => $firstname,
        'customers_lastname' => $lastname,
        'customers_email_address' => $email_address,
        'customers_telephone' => $telephone,
        'customers_fax' => $fax,
        'customers_newsletter' => $newsletter,
        'customers_password' => tep_encrypt_password($password)
    );

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

    tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

    $customer_id = tep_db_insert_id();

    $sql_data_array = array(
        'customers_id' => $customer_id,
        'entry_firstname' => $firstname,
        'entry_lastname' => $lastname,
        'entry_street_address' => $street_address,
        'entry_postcode' => $postcode,
        'entry_city' => $city,
        'entry_country_id' => $country
    );

    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
    if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
            $sql_data_array['entry_zone_id'] = $zone_id;
            $sql_data_array['entry_state'] = '';
        } else {
            $sql_data_array['entry_zone_id'] = '0';
            $sql_data_array['entry_state'] = $state;
        }
    }

    tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

    $address_id = tep_db_insert_id();

    tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

    tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

    if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
    }

    $customer_first_name = $firstname;
    $customer_default_address_id = $address_id;
    $customer_country_id = $country;
    $customer_zone_id = $zone_id;
    tep_session_register('customer_id');
    tep_session_register('customer_first_name');
    tep_session_register('customer_default_address_id');
    tep_session_register('customer_country_id');
    tep_session_register('customer_zone_id');

    // reset session token
    $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());

    // restore cart contents
    $cart->restore_contents();

    // build the message content
    $name = $firstname . ' ' . $lastname;

    $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
    $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
    tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
}
?>
