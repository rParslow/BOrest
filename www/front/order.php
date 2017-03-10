<?php
/**
 * Embbeded Form minimal integration example
 * 
 * To run the example, go to 
 * https://github.com/LyraNetwork/krypton-php-examples
 */

#print_r($_POST);
#die();

/**
 * I initialize the PHP SDK
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/keys.php';

/** 
 * Initialize the SDK 
 * Please update your keys in keys.php
 */
$client = new LyraNetwork\Client();  
$client->setPrivateKey($_privateKey);       /* key defined in keys.php file */
$client->setPublicKey($_publicKey);         /* key defined in keys.php file */
$client->setEndpoint($_endpoint);           /* REST API endpoint defined in keys.php file */

/**
 * Datas 
 */

$orderId   = "ORD-234";
$currency  = "EUR";
$amount    = $_POST['amount'];
$price     = ($amount/100);
$firstName = $_POST['firstName'];
$lastName  = $_POST['lastName'];

$manualValidation = "YES";
$captureDelay     = "2";
$paymentSource    = "EC";

/**
 * I create a formToken
 */
$store = array("amount" => $amount, "currency" => $currency);

$store = array("amount"   => $amount,
               "currency" => $currency,
               "orderId"  => $orderId,
               "paymentMethodOptions" => array("cardOptions" => array("manualValidation" => $manualValidation, "captureDelay" => $captureDelay,"paymentSource" => $paymentSource)),
               "customer" => array("email" => "$email", "reference" => "CUST-1234",
                                   "billingDetails" => array("firstName" => $firstName, "lastName" => "$lastName")
                                  ),
               "metadata" => array("product0" => "Chocolate Cookie", "product1" => "French Chocolatine")
              );



$response = $client->post("Charge/CreatePayment", $store);

/* I check if there is some errors */
if ($response['status'] != 'SUCCESS') {
    /* an error occurs, I throw an exception */
    $error = $response['answer'];
    throw new Exception("error " . $error['errorCode'] . ": " . $error['errorMessage'] );
}

/* everything is fine, I extract the formToken */
$formToken = $response["answer"]["formToken"];

#print "newly generated formToken is " . $formToken . " <br>\n";

?>


		<link rel="stylesheet" type="text/css" href="assets/css/kr-bs.css"/>		
		<link rel="stylesheet" type="text/css" href="assets/css/button.css"/>		
		<link rel="stylesheet" type="text/css" href="assets/css/dot_loader.css"/>		
	

<!-- payment form HTML code -->
<div class="kr-embedded">
    <div class="kr-pan"></div>
    <div class="kr-expiry"></div>
    <div class="kr-security-code"></div>
       <div class="kr-form-error"></div>

    <div class="kr-row-no-gutter">
        <div class="kr-payment-button-wrap">
            <button class="kr-payment-button kr-text-animated">
                <span class="regular-label">Pay <? echo "$price $currency" ?></span>

                <!-- necessary element to print dots loader -->
                <div class="waiting-animation">
                    <div class="dot-wrapper">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            </button>
        </div>
        <div class="kr-payment-button-wrap">
            <div class="kr-form-error kr-form-error-doc-embedded"></div>
        </div>

    </div>

</div>


<script src="https://secure.payzen.eu/static/js/krypton-client/V3/stable/kr.min.js?formToken=<? echo $formToken ?>"
    kr-public-key="<? echo $_publicKey ?>"
    kr-language="en"
    kr-payment-type="card"
    kr-post-url="paid.php"
    kr-theme="icons-1"
</script>

<!-- listen events to show and hide the dots loading -->
<script language="javascript">
    KR.$(document).ready(function() {
        var $krLoading = KR.$(".waiting-animation");
        var $spanButton = KR.$(".regular-label");
        var $krError = KR.$(".kr-form-error");
        // shows loading when the payment starts
        KR.event.handler.listen("paymentStart", function(error) {
            $spanButton.hide();
            // $krLoading.show();
            $krLoading.css('display', 'inline-block');
            $krError.hide();
        });
        // hides loading if the payment fails
        KR.event.handler.listen("fireError", function(error) {
            $spanButton.show();
            $krLoading.hide();
            $krError.show();
        });
    });
</script>
<!-- payment form HTML code -->

