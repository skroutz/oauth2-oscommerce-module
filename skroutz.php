<?php // vim: set ft=php et ts=4 sts=4 sw=4 ai si:
// your data here
$client_id='';
$client_secret='';
$redirect_uri='';

if(isset($_GET['code']))
{
    //set POST variables
    $url = 'https://www.skroutz.gr/oauth2/token';
    $fields = array(
        'code'=>urlencode($_GET['code']),
        'client_id'=>urlencode($client_id),
        'client_secret'=>urlencode($client_secret),
        'redirect_uri'=>urlencode($redirect_uri),
        'grant_type'=>urlencode('authorization_code')
    );

    //url-ify the data for the POST
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string,'&');

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt($ch,CURLOPT_POST,count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    $theResult=json_decode($result);
    $oauth_token=$theResult->access_token;
    $url = 'https://www.skroutz.gr/oauth2/address';
    $qry_str = "?oauth_token=".urlencode($oauth_token);


    $ch = curl_init();
    // Set query data here with the URL
    curl_setopt($ch, CURLOPT_URL,$url . $qry_str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, '3');
    $content = trim(curl_exec($ch));
    curl_close($ch);

    $user = json_decode($content);

    require('includes/application_top.php');
    $url = preg_split('/\?/', tep_href_link('skrz_login.php','','SSL'), 0, PREG_SPLIT_NO_EMPTY);
    // Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $user->email . "'");
    if (!tep_db_num_rows($check_customer_query)) {

        echo tep_draw_form('create_account', $url[0], 'post', 'onsubmit="return check_form(create_account);"', true) . tep_draw_hidden_field('action', 'process'); ?>
        <input type="hidden" name="firstname" value="<?=$user->first_name?>" />
        <input type="hidden" name="lastname" value="<?=$user->last_name?>" />
        <input type="hidden" name="email_address" value="<?=$user->email?>" />
        <input type="hidden" name="company" value="<?=$user->company?>" />
        <input type="hidden" name="street_address" value="<?=$user->address?>" />
        <input type="hidden" name="postcode" value="<?=$user->zip?>" />
        <input type="hidden" name="city" value="<?=$user->city?>" />
        <input type="hidden" name="country" value="84" />
        <input type="hidden" name="telephone" value="<?=$user->mobile?>" />
        <input type="hidden" name="fax" value="<?=$user->phone?>" />
        <input type="hidden" name="mytype" value="2" />
        </form>
        <script type="text/javascript">
            document.forms["create_account"].submit();
        </script>
<?php
    } else {
        echo tep_draw_form('login', $url[0], 'post', '', true); ?>
        <input type="hidden" name="email_address" value="<?=$user->email?>" />
        <input type="hidden" name="mytype" value="1" />
        </form>
        <script type="text/javascript">
            document.forms["login"].submit();
        </script>
    <?php }
}else{
    header( 'Location: https://www.skroutz.gr/oauth2/authorizations/new?client_id='.urlencode($client_id).'&redirect_uri='.urlencode($redirect_uri).'&response_type=code' ) ;
}
?>
