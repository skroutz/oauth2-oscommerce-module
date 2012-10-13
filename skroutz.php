<?php // vim: set ft=php et ts=4 sts=4 sw=4 ai si:
// Enter your key here (client_id)
$client_id = '';
// Enter your secret here (client_secret)
$client_secret = '';

// if you want to hardcode the redirect_uri add it here
// otherwise it will be calculated automatically
// and by default point to this script
$redirect_uri = '';

/************************************
 * No need to change anything below *
 ************************************/
$site = 'https://www.skroutz.gr';
$authorization_url = '/oauth2/authorizations/new';
$token_url = '/oauth2/token';
$address_url = '/oauth2/address';

if (!isset($redirect_uri) || $redirect_uri == '') {
    $redirect_uri = (isset($_SERVER['HTTPSS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    $redirect_uri .= $_SERVER["HTTP_HOST"] . $_SERVER['SCRIPT_NAME'];
}

if (isset($_GET['code'])) {
    require('includes/application_top.php');

    // set POST variables
    $url = $site . $token_url;
    $fields = array(
        'code' => urlencode($_GET['code']),
        'client_id' => urlencode($client_id),
        'client_secret' => urlencode($client_secret),
        'redirect_uri' => urlencode($redirect_uri),
        'grant_type' => urlencode('authorization_code')
    );

    // url-ify the data for the POST
    $fields_string = '';
    foreach ($fields as $key=>$value) { $fields_string .= $key . '=' . $value . '&'; }
    rtrim($fields_string, '&');

    // open connection
    $ch = curl_init();

    // set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // execute POST
    $json = curl_exec($ch);

    // close connection
    curl_close($ch);

    // get oauth_token
    $result = json_decode($json);

    if (isset($result->error)) {
        //handle error
        tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    // get the address
    $oauth_token = $result->access_token;
    $url = $site . $address_url;
    $qry_str = "?oauth_token=" . urlencode($oauth_token);

    // open connection
    $ch = curl_init();

    // set query data here with the URL
    curl_setopt($ch, CURLOPT_URL, $url . $qry_str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, '3');

    // execute GET
    $json = trim(curl_exec($ch));

    // close connection
    curl_close($ch);

    $user = json_decode($json);

    if (isset($user->error)) {
        //handle error
        tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    $url = preg_split('/\?/', tep_href_link('skroutz_login.php', '', 'SSL'), 0, PREG_SPLIT_NO_EMPTY);

    // Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $user->email . "'");

    if (!tep_db_num_rows($check_customer_query)) { ?>
        <?php echo tep_draw_form('create_account', $url[0], 'post', 'onsubmit="return check_form(create_account);"', true) . tep_draw_hidden_field('action', 'process') ?>
        <input type="hidden" name="gender" value="m" />
        <input type="hidden" name="firstname" value="<?php echo $user->first_name ?>" />
        <input type="hidden" name="lastname" value="<?php echo $user->last_name ?>" />
        <input type="hidden" name="dob" value="01/01/1900" />
        <input type="hidden" name="email_address" value="<?php echo $user->email ?>" />
        <input type="hidden" name="company" value="<?php echo $user->company ?>" />
        <input type="hidden" name="street_address" value="<?php echo $user->address ?>" />
        <!--<input type="hidden" name="suburb" value="<?php // echo $user->suburb ?>" />-->
        <input type="hidden" name="postcode" value="<?php echo $user->zip ?>" />
        <input type="hidden" name="city" value="<?php echo $user->city ?>" />
        <input type="hidden" name="state" value="<?php echo $user->region ?>" />
        <input type="hidden" name="country" value="84" /> <!-- Greece -->
        <input type="hidden" name="telephone" value="<?php echo (isset($user->mobile) && $user->mobile != "") ? $user->mobile : $user->phone ?>" />
        <!--<input type="hidden" name="fax" value="<?php // echo $user->fax ?>" />-->
        <input type="hidden" name="newsletter" value="0" />
        <input type="hidden" name="mytype" value="2" />
        </form>
        <script type="text/javascript">
            document.forms["create_account"].submit();
        </script>
<?php  } else { ?>
        <?php // register a session variable to mark we come from oauth
           tep_session_register('oauth_login'); ?>
        <?php echo tep_draw_form('login', $url[0], 'post', '', true) . tep_draw_hidden_field('action', 'process') ?>
        <input type="hidden" name="email_address" value="<?php echo $user->email ?>" />
        <input type="hidden" name="mytype" value="1" />
        </form>
        <script type="text/javascript">
            document.forms["login"].submit();
        </script>
<?php  }
} else if (isset($_GET['error'])) {
    require('includes/application_top.php');

    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
} else {
    header('Location: ' . $site . $authorization_url . '?client_id=' . urlencode($client_id) . '&redirect_uri=' . urlencode($redirect_uri) . '&response_type=code');
}
?>
