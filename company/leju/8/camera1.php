<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr">
<head>    <title>Camera and Video Control with HTML5 Example</title>
    <meta name="description" content="Access the desktop camera and video using HTML, JavaScript, and Canvas.  The camera may be controlled using HTML5 and getUserMedia." />
<style>
    
    nav .search {
        display: none;
    }

    .demoFrame header,
    .demoFrame .footer,
    .demoFrame h1,
    .demoFrame .p {
        display: none !important;
    }

    h1 {
        font-size: 2.6em;
    }

    h2, h3 {
        font-size: 1.7em;
    }

    .left {
        width: 920px !important;
        padding-bottom: 40px;
    }
    div.p {
        font-size: .8em;
        font-family: arial;
        margin-top: -20px;
        font-style: italic;
        padding: 10px 0;
    }

    .footer {
        padding: 20px;
        margin: 20px 0 0 0;
        background: #f8f8f8;
        font-weight: bold;
        font-family: arial;
        border-top: 1px solid #ccc;
    }

    .left > p:first-of-type { 
        background: #ffd987; 
        font-style: italic; 
        padding: 5px 10px; 
        margin-bottom: 40px;
    }


    #promoNode { 
        margin: 20px 0; 
    }
</style>    <style>
        video { border: 1px solid #ccc; display: block; margin: 0 0 20px 0; }
        #canvas { margin-top: 20px; border: 1px solid #ccc; display: block; }
    </style>
</head>
<body>

<!-- Add the HTML header -->
<div id="page">

<!-- holds content, will be frequently changed -->
<div id="contentHolder">

    <!-- start the left section if not the homepage -->
    <section class="left">
<h1>Camera and Video Control with HTML5</h1>
<div class="p">Read <a href="http://davidwalsh.name/browser-camera" target="_top">Camera and Video Control with HTML5</a></div>
<div id="promoNode"></div>    
    <p>Using Opera Next or Chrome Canary, use this page to take your picture!</p>

    <!--
        Ideally these elements aren't created until it's confirmed that the 
        client supports video/camera, but for the sake of illustrating the 
        elements involved, they are created with markup (not JavaScript)
    -->
    <video id="video" width="640" height="480" autoplay></video>
    <button id="snap" class="sexyButton">Snap Photo</button>
    <canvas id="canvas" width="640" height="480"></canvas>

    <script>

        // Put event listeners into place
        window.addEventListener("DOMContentLoaded", function() {
            // Grab elements, create settings, etc.
            var canvas = document.getElementById("canvas"),
                context = canvas.getContext("2d"),
                video = document.getElementById("video"),
                videoObj = { "video": true },
                errBack = function(error) {
                    console.log("Video capture error: ", error.code); 
                };

            // Put video listeners into place
            if(navigator.getUserMedia) { // Standard
                navigator.getUserMedia(videoObj, function(stream) {
                    video.src = stream;
                    video.play();
                }, errBack);
            } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
                navigator.webkitGetUserMedia(videoObj, function(stream){
                    video.src = window.webkitURL.createObjectURL(stream);
                    video.play();
                }, errBack);
            }

            // Trigger photo take
            document.getElementById("snap").addEventListener("click", function() {
                context.drawImage(video, 0, 0, 640, 480);
            });
        }, false);

    </script>
        
</section>



</div>


</body>
</html>