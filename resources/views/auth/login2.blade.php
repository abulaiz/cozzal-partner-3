<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">    
</head>
<body>
<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 d-none d-lg-block" style="background: url({{ URL::asset('images/helloquence-OQMZwNd3ThU-unsplash.jpg') }}); background-size: cover; opacity: 0;" id="bgbg"></div>  	
    <div class="col-md-8 col-lg-6" style="background-color: white;">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
          	<div class="col-lg-12 mb-5 text-center">
          		<img src="{{ URL::asset('images/logonya.png') }}" style="width: 30%;">
          	</div>
            <div class="col-md-9 col-lg-8 mx-auto">

              <h5 class="login-heading mb-2">Sign in to Cozzal Partner</h5>
              <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                  <label for="exampleInputEmail">Email</label>
                  <input type="email" class="form-control form-control-lg" id="exampleInputEmail" name="email" placeholder="Email">
                  @error('email')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror                  
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword">Password</label>
                  <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword" placeholder="Password">   
                  @error('password')
                      <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror                      
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>
                <div class="my-3">
                  <button type="submit" class="btn btn-block btn-danger btn-lg font-weight-medium auth-form-btn">Masuk</button>
                </div>
              </form> 
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<style type="text/css">

.login{
	  min-height: 100vh;
}

.login-heading {
  font-weight: 300;
}

#bgbg{
      -webkit-transition: opacity 0.5s ease-in;
      -moz-transition: opacity 0.5s ease-in;
      -ms-transition: opacity 0.5s ease-in;
      -o-transition: opacity 0.5s ease-in;
      transition: opacity 0.5s ease-in;		
}
</style>

<script>
	window.onload = function(){
		document.getElementById('bgbg').style.opacity = '1';
	}
</script>

</body>
</html>