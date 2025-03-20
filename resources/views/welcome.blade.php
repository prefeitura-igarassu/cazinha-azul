<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DAGAI') }}</title>

        <!-- Fonts -->
        @vite('resources/css/app.css')
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" />

        <!-- Styles -->
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        
        <style>
            html {
                font-size: 12px;
            }

            html, body{
                margin: 0;
                padding: 0;
            }
            
            .loader {
			  width: 90px;
			  height: 14px;
			  background: radial-gradient(circle closest-side,#000000 92%,#0000) calc(100%/3) 0/calc(100%/4) 100%;
			  animation: l2 0.5s infinite linear;
			}
			
			@keyframes l2 {
				100% {background-position: 0 0}
			}

            .loader-container {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                background: #e5e7eb;
                color: white;
            }
        </style>
    </head>
    <body>
        <div id="app">
			<div class="loader-container">
                <div class="loader"></div>
            </div>
        </div>
    </body>
    
    @vite( 'resources/js/app.js' )
</html>
