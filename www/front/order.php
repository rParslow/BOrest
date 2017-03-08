<?php
/**
 * Embbeded Form minimal integration example
 * 
 * To run the example, go to 
 * https://github.com/LyraNetwork/krypton-php-examples
 */

print_r($_POST);
die();

/**
 * I initialize the PHP SDK
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/keys.php';

/** 
 * Initialize the SDK 
 * Please update your keys in keys.php
 */
$client = new LyraNetwork\Client();  
$client->setPrivateKey($_privateKey);       /* key defined in keys.php file */
$client->setPublicKey($_publicKey);         /* key defined in keys.php file */
$client->setEndpoint($_endpoint);           /* REST API endpoint defined in keys.php file */

/**
 * I create a formToken
 */
$store = array("amount" => 150, "currency" => "USD");
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
<!DOCTYPE html>
<html>
	<head>
		<title>Payzen Shopping Cart</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
		<link rel="stylesheet" type="text/css" href="assets/css/custom.css"/>		
		<link rel="stylesheet" type="text/css" href="assets/css/kr-bs.css"/>		
		<link rel="stylesheet" type="text/css" href="assets/css/button.css"/>		
		<link rel="stylesheet" type="text/css" href="assets/css/dot_loader.css"/>		
	</head>

	<body>
		
		<nav class="navbar">
			<div class="container">
				<a class="navbar-brand" href="#">Payzen store</a>
				<div class="navbar-right">
					<div class="container minicart"></div>
				</div>
			</div>
		</nav>
		
		<div class="container-fluid breadcrumbBox text-center">
			<ol class="breadcrumb">
				<li><a href="#">Review</a></li>
				<li><a href="./index.html">Order</a></li>
				<li class="active"><a href="#">Payment</a></li>
			</ol>
		</div>
		
		<div class="container text-center">

			<div class="col-md-5 col-sm-12">
				<div class="bigcart"></div>
				<h1>Payment</h1>
			</div>
			
			<div class="col-md-7 col-sm-12 text-left">

<!--               <ul>
                    <li class="row">-->
		<div class="container-fluid breadcrumbBox text-center">
			<ol class="breadcrumb">
		

<!-- payment form HTML code -->

<div class="kr-embedded">
    <div class="kr-pan"></div>
    <div class="kr-expiry"></div>
    <div class="kr-security-code"></div>
       <div class="kr-form-error"></div>

    <div class="kr-row-no-gutter">
        <div class="kr-payment-button-wrap">
            <button class="kr-payment-button kr-text-animated">
                <span class="regular-label">Pay USD 4.65 now!</span>

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
			</ol>
		</div>


			</div>

		</div>

		<!-- The popover content -->

		<div id="popover" style="display: none">
			<a href="#"><span class="glyphicon glyphicon-pencil"></span></a>
			<a href="#"><span class="glyphicon glyphicon-remove"></span></a>
		</div>
		
		<!-- JavaScript includes -->

		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script> 
		<script src="assets/js/bootstrap.min.js"></script>
		<script src="assets/js/customjs.js"></script>

	</body>
</html>
