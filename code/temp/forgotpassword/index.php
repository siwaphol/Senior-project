<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Forgot password</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>

  <script type="text/javascript">
    function onrequest() {
      $.post( 
          'example.php',
          { username: $("#usernameInput").val()}
       ).done(function(resp){
            test = resp;
            
       })
       .fail(function(resp){
            test = resp;
            
       });
    }
  </script>
</head>
<body style="background-color:#1C1C1C">
	<br>
  <!--PROVIDED BY MARCELO MARTINS - BOOTLY @ mmartins-->
  <div class="container">
    <div class="row">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="text-center">

                <h3 class="text-center">Forgot Password?</h3>
                <p>If you have forgotten your password - put your username here.</p>
                <div class="panel-body">

                  <form class="form"><!--start form--><!--add form action as needed-->
                    <fieldset>
                      <div class="form-group" id="contact_form">
                      <div id="contact_results"></div>
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                          <!--EMAIL ADDRESS-->
                          <input id="usernameInput" placeholder="username" class="form-control" oninvalid="setCustomValidity('Please enter a valid username!')" onchange="try{setCustomValidity('')}catch(e){}" required="">
                        </div>
                      </div>
                      <div class="form-group">
                        <input id="submit_btn" class="btn btn-lg btn-primary btn-block" onclick="onrequest();" value="Send My Password" type="submit">
                      </div>
                    </fieldset>
                  </form><!--/end form-->

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.min.js"></script>
</body>
</html>