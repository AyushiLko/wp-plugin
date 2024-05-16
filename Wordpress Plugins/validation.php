<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registration/Login Page</title>
    <link rel="stylesheet" href="style.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"
    ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  </head>

  <body>
    <section class="vh-100">
      <div class="container-fluid h-custom">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <!--Login Image-->
          <div class="col-md-9 col-lg-6 col-xl-5">
            <img src="./assets/34.jpg" class="img-fluid" alt="Sample image" />
          </div>
          <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
            <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-2 mt-2">
              Registration
            </p>

            <!--form start-->

            <form class="mx-1 mx-md-4" action="" method="post" id="contactform">
              <!--name field-->
              <div class="form-outline mb-4">
                <label class="form-label">Name</label>
                <input
                  type="text"
                  placeholder="Enter a your Name"
                  id="name"
                  class="form-control form-control-lg"
                />

                <span id="nameMsg"></span>
              </div>
              <!--email field-->
              <div class="form-outline mb-4">
                <label class="form-label" for="form3Example3"
                  >Email address</label
                >
                <input
                  type="email"
                  id="email"
                  class="form-control form-control-lg"
                  placeholder="Enter a valid email address"
                />
                <span id="emailMsg"></span>
              </div>
              <!--Phone Number Field-->
              <div class="form-outline mb-4">
                <label class="form-label">Phone Number</label>
                <input
                  type="tel"
                  name="num"
                  id="number"
                  class="form-control form-control-lg"
                  placeholder="Enter Your Number  "
                  maxlength="10"
                />
                <span id="numMsg"></span>
              </div>
              <!--password field-->
              <div class="form-outline mb-4">
                <label class="form-label">password</label>
                <input
                  type="password"
                  id="password"
                  class="form-control form-control-lg"
                  placeholder="Enter Your Password"
                />
              </div>
              <span id="passMsg"></span>
              <!--button-->
              <div class="d-flex justify-content-center mx-4 mb-2 mb-lg-4">
                <button
                  type="button"
                  class="contactform-buttons btn btn-primary btn-lg"
                  id="reg"
                  style="padding-left: 2.5rem; padding-right: 2.5rem"
                >
                  Register
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <script>
      $(document).ready(function () {
        //keyup function to check the input name
        $("#name").keyup(function () {
          if (validateName()) {
            //if the name is validated
            $("#name").css("border", "2px solid green");
            $("#nameMsg").html(
              '<p style="color: green">Your Name is Correct</p>'
            );
          } else {
            // if the name is not validated
            $("#name").css("border", "2px solid red");
            $("#nameMsg").html(
              '<p style="color: red">Please Enter the Correct Name</p>'
            );
          }
        });
        $("#email").keyup(function () {
          if (validateEmail()) {
            //if the email is validated
            $("#email").css("border", "2px solid green");
            $("#emailMsg").html(
              '<p style="color: green">Your Email is Valid</p>'
            );
          } else {
            // if the email is not validated
            $("#email").css("border", "2px solid red");
            $("#emailMsg").html(
              '<p style="color: red">Please Enter the Valid Email</p>'
            );
          }
        });
        $("#number").keyup(function () {
          var totalchar = 10;
          var leftchars = totalchar - $(this).val().length;
          this.value = this.value.replace(/[^0-9\.]/g, "");
          if (validateNumber()) {
            //if the number is validated
            $("#number").css("border", "2px solid green");
            $("#numMsg").html(
              '<p style="color: green">Your Number is valid</p>'
            );
          } else {
            // if the number is not validated
            $("#number").css("border", "2px solid red");
            $("#numMsg").html(
              '<p style="color: red">Please Enter the Valid Number</p>'
            );
          }
        });
        $("#password").keyup(function () {
          if (validatePassword()) {
            //if the password is validated
            $("#password").css("border", "2px solid green");
            $("#passMsg").html('<p style="color: green">Valid Password</p>');
          } else {
            // if the password is not validated
            $("#password").css("border", "2px solid red");
            $("#passMsg").html('<p style="color: red">Invalid Password</p>');
          }
        });
        //function for empyt field
        $("#reg").click(function () {
          //if the name  field is empty
          if ($("#name").val() === "") {
            $("#name").css("border", "2px solid red");
            $("#nameMsg").html(
              '<p style="color: red">Please Enter the Name</p>'
            );
          }
          if ($("#email").val() === "") {
            //if the email field is empty
            $("#email").css("border", "2px solid red");
            $("#emailMsg").html(
              '<p style="color: red">Please Enter the Email</p>'
            );
          }
          if ($("#number").val() === "") {
            //if the number field is empty
            $("#number").css("border", "2px solid red");
            $("#numMsg").html(
              '<p style="color: red">Please Enter the Number</p>'
            );
          }
          if ($("#password").val() === "") {
            //if the password field is empty
            $("#password").css("border", "2px solid red");
            $("#passMsg").html(
              '<p style="color: red">Please Enter the Password</p>'
            );
          }
          //if all the fields are valid
          if (
            validateName() &&
            validateEmail() &&
            validateNumber() &&
            validatePassword()
          ) {
            $("#contactform").submit();
          }
        });
      });
      //function for checking name
      function validateName() {
        var name = $("#name").val();
        var regName = /^[a-zA-Z\s]+$/;
        if (regName.test(name)) {
          return true;
        } else {
          return false;
        }
      }
      //function for checking email
      function validateEmail() {
        //get value of input email
        var email = $("#email").val();
        //use reular expression
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,3})$/;
        if (reg.test(email)) {
          return true;
        } else {
          return false;
        }
      }
      //function for checking number
      function validateNumber() {
        //get the number value
        var number = $("#number").val();
        var regNumber = /^[0-9]+$/;
        if (regNumber.test(number)) {
          return true;
        } else {
          return false;
        }
      }

      //function for checking password
      function validatePassword() {
        //get value of input password
        var pass = $("#password").val();
        if (pass.length >= 8) {
          return true;
        } else {
          return false;
        }
      }
    </script>
  </body>
</html>