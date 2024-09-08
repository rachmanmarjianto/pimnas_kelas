<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up Form by Colorlib</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="{{ asset('/public/fonts/material-icon/css/material-design-iconic-font.min.css') }}">
    

    <!-- Main css -->
    <link rel="stylesheet" href="{{ asset('/public/css/login/style.css') }}">
</head>
<body>
    <div class="main">
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="{{ asset('/public/images/REMO-SAGA-HD.png')}}" alt="sing up image"></figure>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Log In</h2>
                        <form method="POST" class="register-form" id="login-form" action="log n">
                            @csrf
                            <div class="form-group">
                                <label for="idusername"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username" id="idusername" placeholder="Username" required style="font-size: 20px;"/>
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="your_pass" placeholder="Password" style="font-size: 20px;" required/>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="form-submit" value="Log in" style="font-size: 20px;" />
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>