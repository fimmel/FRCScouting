
<script>


	
	
	
	var prof;
	var ajaxtoken;
function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();
  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
  console.log('Name: ' + profile.getName());
  console.log('Image URL: ' + profile.getImageUrl());
  console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
	document.getElementById('ga_name').innerHTML = profile.getName();
	document.getElementById('ga_pic').innerHTML = '<img src="' + profile.getImageUrl() + '" />';
	
	var id_token = googleUser.getAuthResponse().id_token;
	var xhr = new XMLHttpRequest();
xhr.open('POST', 'https://frcscouting.net/prod/backend/tokensignin.php');
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.onload = function() {
  console.log('Signed in as: ' + xhr.responseText);
};
xhr.send('idtoken=' + id_token);
	
	prof = profile.getId();
	ajaxtoken = id_token;
	//document.getElementById('scoutid').innerHTML = '<input type=hidden name="scout" value="' + id_token + '" />';
}
</script>
					
					
<script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
		document.getElementById('ga_name').innerHTML = "";
	document.getElementById('ga_pic').innerHTML = '';
    });
  }
					</script>
	
</body>
</html>