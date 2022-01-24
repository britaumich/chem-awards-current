<?php
require 'vendor/autoload.php';


$issuer = 'https://shib-idp-staging.dsc.umich.edu';
//$issuer = 'https://shibboleth.umich.edu';
$cid = '677abbb6-ba16-4c95-b8a8-84760247592d';
$secret = '7867e424-5bff-44de-8951-1350b4a59a1f';
$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);

//$oidc->addScope(array('edumember' => 'ChemAwardsAccessGroup'));
$oidc->addScope(array('openid profile email edumember'));
$oidc->authenticate();
$oidc->requestUserInfo('sub');
$sub = $oidc->getVerifiedClaims('sub');

echo "<br>sub: ";
echo $sub;
echo "<br><br>oidc: <br>";
var_export($oidc);
$session = array();
//$userinfo = $oidc->getuserInfo();
$verifiedclaims = $oidc->getVerifiedClaims();
$scopes = $oidc->getScopes();
echo "<br><br>scopes:<br>";
var_export($scopes);
echo "<br>VerifiedClaims:<br>";
var_export($verifiedclaims);
echo "<br><br>";
foreach ($verifiedclaims as $key => $value) {
    if(is_array($value)){
            $v = implode(', ', $value);
    }else{
            $v = $value;
    }
    $session[$key] = $v;
}
//echo "<br><br>session: <br>";
//var_export($session);

session_start();
$_SESSION['attributes'] = $session;

//header("Location: ./attributes.php");

?>
