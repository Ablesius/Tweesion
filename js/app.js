

function signup(){

	// TAKE DATA OUT OF FIELDS
	var user_name = document.getElementById("user_name_signup").value;
	var username = document.getElementById("username_signup").value;
	var mail = document.getElementById("email_signup").value;
	var pass = document.getElementById("pass_signup").value;
	var pass_ = document.getElementById("pass_signup_").value;

	// ADD TO PARSE VARIABLE
	if(username != "" && user_name != "" && mail != "" && pass != ""){
		if(pass == pass_){
			if(username.length > 3){
				if(validateEmail(mail)){
					var user = new Parse.User();
					user.set("username", username);
					user.set("password", pass);
					user.set("name", user_name);
					user.set("email", mail);
												//username seven11nash already taken
					//SAVE TO DATABASE
					user.signUp(null, {
					  success: function(user) {
					    window.location.href="dash.html";
					  },
					  error: function(user, error) {
					    // Show the error message somewhere and let the user try again.
					    // alert("Error: " + error.code + " " + error.message);
					    console.log(error.message);

					    if(error.message == "username " + username + " already taken"){
					    	document.getElementById("errormsg").innerHTML = "That username already exists; please select a new one";
					    	$(".footer").hide();
					    	$(".errormsg").fadeIn(400);
					    	setTimeout(function(){
					    		$(".errormsg").fadeOut(400);
					    		$(".footer").fadeIn(400);
					    		document.getElementById("errormsg").innerHTML = "";
					    	},3000);
					    }else if(error.message == "the email address " + mail + " has already been taken"){
					    	document.getElementById("errormsg").innerHTML = "That email is already being used; please select a different one";
					    	$(".footer").hide();
					    	$(".errormsg").fadeIn(400);
					    	setTimeout(function(){
					    		$(".errormsg").fadeOut(400);
					    		$(".footer").fadeIn(400);
					    		document.getElementById("errormsg").innerHTML = "";
					    	},3000);
					    }else{
						    document.getElementById("errormsg").innerHTML = "An unknown error has occured. Please try again.";
					    	$(".footer").hide();
					    	$(".errormsg").fadeIn(400);
					    	setTimeout(function(){
					    		$(".errormsg").fadeOut(400);
					    		$(".footer").fadeIn(400);
					    		document.getElementById("errormsg").innerHTML = "";
					    	},3000);
				    	}
					  }
					});
				}else{
					document.getElementById("errormsg").innerHTML = "That email is not valid; please check again.";
			    	$(".footer").hide();
			    	$(".errormsg").fadeIn(400);
			    	setTimeout(function(){
			    		$(".errormsg").fadeOut(400);
			    		$(".footer").fadeIn(400);
			    		document.getElementById("errormsg").innerHTML = "";
			    	},3000);
				}
			}else{
				document.getElementById("errormsg").innerHTML = "That username isn't long enough. Sorry, you'll need to change that";
		    	$(".footer").hide();
		    	$(".errormsg").fadeIn(400);
		    	setTimeout(function(){
		    		$(".errormsg").fadeOut(400);
		    		$(".footer").fadeIn(400);
		    		document.getElementById("errormsg").innerHTML = "";
		    	},3000);
			}
		}else{
			document.getElementById("errormsg").innerHTML = "The passwords you typed weren't the same; try again.";
	    	$(".footer").hide();
	    	$(".errormsg").fadeIn(400);
	    	setTimeout(function(){
	    		$(".errormsg").fadeOut(400);
	    		$(".footer").fadeIn(400);
	    		document.getElementById("errormsg").innerHTML = "";
	    	},3000);
		}

	}else{
		document.getElementById("errormsg").innerHTML = "You didnt fill out all the fields... please do so.";
    	$(".footer").hide();
    	$(".errormsg").fadeIn(400);
    	setTimeout(function(){
    		$(".errormsg").fadeOut(400);
    		$(".footer").fadeIn(400);
    		document.getElementById("errormsg").innerHTML = "";
    	},3000);
	}

}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}


function resendMail(){
	var currentUser = Parse.User.current();
	currentUser.fetch().then(function (user) {
		var mail = user.getEmail();

		var user = currentUser;
		user.set("email", "");
		user.save(null, {
			success: function(user) {
				user.set("email", mail);
				user.save(null, {
					success: function(user) {
						$(".errormsg").css("background-color", "#48d886");
						document.getElementById("errormsg").innerHTML = "Email Sent";
						setTimeout(function(){
				    		$(".errormsg").fadeOut(400);
				    		setTimeout(function(){
				    		$(".errormsg").css("background-color", "#d84852");
				    		document.getElementById("errormsg").innerHTML = "";
				    		},500);
				    	},3000);
					},
					error: function(user, error) {
						document.getElementById("errormsg").innerHTML = "An unknown error has occured. <a href='javascript:resendMail()'>Try again</a>";
				      }
				});
			},
			error: function(user, error) {
		        document.getElementById("errormsg").innerHTML = "An unknown error has occured. <a href='javascript:resendMail()'>Try again</a>";
		      }
		});

	});
}


function signin(){

var username = document.getElementById("username_signin").value;
var pass = document.getElementById("pass_signin").value;

Parse.User.logIn(username, pass, {
  success: function(user) {
    window.location.href="dash.html";
  },
  error: function(user, error) {
    // The login failed. Check error to see why.
    console.log(error.message);

    // ERROR MSGS
    if(error.message == "invalid login parameters"){
	    	document.getElementById("errormsg").innerHTML = "The username and password combination is invalid; please try again.";
	    	$(".footer").hide();
	    	$(".errormsg").fadeIn(400);
	    	setTimeout(function(){
	    		$(".errormsg").fadeOut(400);
	    		$(".footer").fadeIn(400);
	    		document.getElementById("errormsg").innerHTML = "";
	    	},3000);
	    }else{
	    	document.getElementById("errormsg").innerHTML = "An unknown error has occured. Please try again.";
	    	$(".footer").hide();
	    	$(".errormsg").fadeIn(400);
	    	setTimeout(function(){
	    		$(".errormsg").fadeOut(400);
	    		$(".footer").fadeIn(400);
	    		document.getElementById("errormsg").innerHTML = "";
	    	},3000);
	    }
  }
});

}


function updateEmailSettings(){

	var new_email = document.getElementById("email_new").value;

	var currentUser = Parse.User.current();
	currentUser.fetch().then(function (user) {

		if(new_email != ""){

			user.set("email", new_email);

			user.save(null, {
				success: function(user) {
					document.getElementById("errormsg").innerHTML = "Email updated, verification sent";
					$(".errormsg").css("background-color", "#48d886");
					$(".footer").hide();
					$(".errormsg").fadeIn(400);
					setTimeout(function(){
			    		$(".errormsg").fadeOut(400);
			    		$(".footer").fadeIn(400);
			    		$(".errormsg").css("background-color", "#48d886");
			    		document.getElementById("errormsg").innerHTML = "";
			    	},3000);
				},
				error: function(user, error) {
			        document.getElementById("errormsg").innerHTML = "An unknown error has occured. <a href='javascript:resendMail()'>Try again</a>";
			      }
			});

		}else{

			document.getElementById("errormsg").innerHTML = "You didn't fill out the field needed; please do so.";
			$(".footer").hide();
			$(".errormsg").fadeIn(400);
			setTimeout(function(){
	    		$(".errormsg").fadeOut(400);
	    		$(".footer").fadeIn(400);
	    		document.getElementById("errormsg").innerHTML = "";
	    	},3000);

		}

	});

}

function updatePassSettings(){

	var old_pass = document.getElementById("pass_old").value;
	var pass = document.getElementById("pass_new").value;
	var pass_ = document.getElementById("pass_new_").value;

	var currentUser = Parse.User.current();
	currentUser.fetch().then(function (user) {

		var user_name = user.getUsername();

		if(old_pass.length > 0 && pass.length > 0 && pass_.length > 0){
			if(pass == pass_){

				Parse.User.logIn(user_name, old_pass, {
				  success: function(user) {

				  	user.set("password", pass);

					user.save(null, {
						success: function(user) {
							document.getElementById("errormsg").innerHTML = "Password updated.";
							$(".errormsg").css("background-color", "#48d886");
							$(".footer").hide();
							$(".errormsg").fadeIn(400);
							setTimeout(function(){
					    		$(".errormsg").fadeOut(400);
					    		$(".footer").fadeIn(400);
					    		$(".errormsg").css("background-color", "#48d886");
					    		document.getElementById("errormsg").innerHTML = "";
					    	},3000);



					    	Parse.User.logOut();
					    	Parse.User.logIn(user_name, pass, {
					    		success: function(user) {
					    			console.log("relogged " + user_name);
					    		},
					    		error: function(user, error) {
						        document.getElementById("errormsg").innerHTML = "An unknown error has occured. <a href='javascript:resendMail()'>Try again</a>";
						      }
					    	});
						},
						error: function(user, error) {
					        document.getElementById("errormsg").innerHTML = "An unknown error has occured. <a href='javascript:resendMail()'>Try again</a>";
					      }
					});

				  },
				  error: function(user, error) {
				    // The login failed. Check error to see why.
				    console.log(error.message);

				    // ERROR MSGS
				    if(error.message == "invalid login parameters"){
					    	document.getElementById("errormsg").innerHTML = "The password you wrote in the 'Old Password' field isn't the same as your account password.";
							$(".footer").hide();
							$(".errormsg").fadeIn(400);
							setTimeout(function(){
					    		$(".errormsg").fadeOut(400);
					    		$(".footer").fadeIn(400);
					    		document.getElementById("errormsg").innerHTML = "";
					    	},3000);
					    }else{
					    	document.getElementById("errormsg").innerHTML = "An unknown error has occured. Please try again.";
					    	$(".footer").hide();
					    	$(".errormsg").fadeIn(400);
					    	setTimeout(function(){
					    		$(".errormsg").fadeOut(400);
					    		$(".footer").fadeIn(400);
					    		document.getElementById("errormsg").innerHTML = "";
					    	},3000);
					    }
				  }
				});

				}else{

					document.getElementById("errormsg").innerHTML = "The new passwords you selected didn't match; try again.";
					$(".footer").hide();
					$(".errormsg").fadeIn(400);
					setTimeout(function(){
			    		$(".errormsg").fadeOut(400);
			    		$(".footer").fadeIn(400);
			    		document.getElementById("errormsg").innerHTML = "";
			    	},3000);

				}

		}else{

			document.getElementById("errormsg").innerHTML = "You didn't fill out all the fields; please do so.";
			$(".footer").hide();
			$(".errormsg").fadeIn(400);
			setTimeout(function(){
	    		$(".errormsg").fadeOut(400);
	    		$(".footer").fadeIn(400);
	    		document.getElementById("errormsg").innerHTML = "";
	    	},3000);

		}

	});

}


function logout(){
	Parse.User.logOut();
	window.location.href="index.html";
}













