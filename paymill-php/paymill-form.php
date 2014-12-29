<html>
<body>
<script type="text/javascript">
  var PAYMILL_PUBLIC_KEY = '9837430574672955d89a4f914ea08b82';
</script>
<script type="text/javascript" src="https://bridge.paymill.com/"></script>
<form id="payment-form" action="save.php" method="POST" >

  <input  class="card-amount-int" name="card-amount-int" type="hidden" value="15" />
  <input   class="card-currency" name="card-currency" type="hidden" value="EUR" />

  <div class="form-row"><label># Kartennummer</label>
    <input class="card-number" name="card-number" type="text" size="20" /></div>

  <div class="form-row"><label>CVV / CVC</label>
    <input class="card-cvc" name="card-cvc" type="text" size="4" /></div>

  <div class="form-row"><label>Karteninhaber</label>
    <input class="card-holdername" id="card-holdername" type="text" size="4" /></div>

  <div class="form-row"><label>Expiry date (MM/YYYY)</label>
    <input class="card-expiry-month" type="text" size="2" />
    <span></span>
    <input class="card-expiry-year" type="text" size="4" /></div>
	<input name="token" type="hidden" id="token" value="" />

  <button class="submit-button" id="payment-form-submit" type="button">Submit</button>

</form>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script  type="text/javascript">
$(document).ready(function() {
	
  $("#payment-form-submit").click(function(event) {
																										
    // Deactivate submit button to avoid further clicks
    $('.submit-button').attr("disabled", "disabled");
	//'4111111111111111'
	alert($('.card-number').val());
	validate = paymill.validateCardNumber( $('.card-number').val());
	 if(!validate)
	 {
		alert("Credit card no is invalid");
		return false;
	 }else{
	 alert("credit card no is valid");
	 }

    paymill.createToken({
      number: $('.card-number').val(),  // required, ohne Leerzeichen und Bindestriche
      exp_month: $('.card-expiry-month').val(),   // required
      exp_year: $('.card-expiry-year').val(),     // required, vierstellig z.B. "2016"
      cvc: $('.card-cvc').val(),                  // required
      amount_int: $('.card-amount-int').val(),    // required, integer, z.B. "15" für 0,15 Euro 
      currency: $('.card-currency').val(),    // required, ISO 4217 z.B. "EUR" od. "GBP"
      cardholder: $('.card-holdername').val() // optional
    }, PaymillResponseHandler);                   // Info dazu weiter unten

    return false;
  });
});
function PaymillResponseHandler(error, result) {
	alert("In handler");
  if (error) {
	alert("In error"+error.apierror);
    // Shows the error above the form
    $(".payment-errors").text(error.apierror);
    $(".submit-button").removeAttr("disabled");
  } else {
	alert("In error else");
    var form = $("#payment-form");
    // Output token
    var token = result.token;
    // Insert token into form in order to submit to server
	alert("token"+token);
	$("#token").val(token);
	
    form.submit();
  }
}
</script>
</body>
</html>