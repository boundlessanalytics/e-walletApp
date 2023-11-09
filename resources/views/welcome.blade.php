<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            .bd-placeholder-img {
              font-size: 1.125rem;
              text-anchor: middle;
              -webkit-user-select: none;
              -moz-user-select: none;
              -ms-user-select: none;
              user-select: none;
            }
      
            @media (min-width: 768px) {
              .bd-placeholder-img-lg {
                font-size: 3.5rem;
              }
            }

            /* Jumbotron */
            body {
                padding-top: 3.5rem;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark mb-3">
            <a href="#" class="navbar-brand">e-walletApp</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                  @if (Route::has('login'))
                      @auth
                          <li class="nav-item active">
                              <a class="nav-link" href="{{ url('/home') }}">Home</a>
                          </li>
                      @else
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('login') }}">Log in</a>
                          </li>
                          @if (Route::has('register'))
                              <li class="nav-item">
                                  <a class="nav-link" href="{{ route('register') }}">Register</a>
                              </li>
                          @endif
                          <li class="nav-item">
                              <a class="nav-link" href="#">Features</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="#">Pricing</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="#">Contact</a>
                          </li>                    
                      @endauth                
                  @endif            
                </ul>
            </div>
        </nav>
        <main role="main mt-3">
    
            <!-- Main jumbotron for a primary marketing message or call to action -->
            <div class="jumbotron">
              <div class="container">
                <h1 class="display-6">Welcome to wallet App</h1>
                <p>Introducing our cutting-edge wallet app, designed to revolutionize the way you manage your finances. Seamlessly combining convenience and security, our app offers a user-friendly interface that empowers you to effortlessly track your expenses, monitor account balances, and make transactions on the go.</p>
    
                <p>With state-of-the-art encryption and multi-factor authentication, your financial data is kept safe and private at all times. The app's intuitive budgeting tools provide insights into your spending patterns, helping you set achievable financial goals and manage your money more effectively.</p>
                    
                <p>But our wallet app goes beyond the basics. It also supports peer-to-peer money transfers, making it simple to split bills, repay friends, or send money to family members with just a few taps. Plus, it seamlessly integrates with loyalty cards and digital coupons, ensuring you never miss out on discounts or rewards.</p>
                    
                <p>Whether you're a savvy investor, a meticulous budgeter, or simply looking for a convenient way to handle your finances, our wallet app is here to simplify your financial journey, all while keeping your information secure and your goals within reach. Welcome to the future of finance at your fingertips..</p>
                {{-- <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p> --}}
              </div>
            </div>
        
            <div class="container">
              <!-- Example row of columns -->
              <div class="row">
                <div class="col-md-4">
                  <h2>Easy to Use</h2>
                  <p>Our user-friendly interface makes managing your finances a breeze.</p>
                  <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
                </div>
                <div class="col-md-4">
                  <h2>Secure Transactions</h2>
                  <p>Rest assured that your transactions are fully encrypted and secure.</p>
                  <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
                </div>
                <div class="col-md-4">
                  <h2>24/7 Support</h2>
                  <p>Our dedicated support team is available around the clock to assist you.</p>
                  <p><a class="btn btn-secondary" href="#" role="button">View details &raquo;</a></p>
                </div>
              </div>
        
              <hr>
        
            </div> <!-- /container -->
        
          </main>
          <footer class="container">
            <p>&copy; Company 2023</p>
          </footer>
    </body>
</html>
