<script language="javascript" type="text/javascript">
    function onVideoFail(e) {
        console.log('webcam fail!', e);
    }

    function hasGetUserMedia() {
        // Note: Opera is unprefixed.
        return !!(navigator.getUserMedia || navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia || navigator.msGetUserMedia);
    }

    if (hasGetUserMedia()) {
        // Good to go!
    } else {
        alert('getUserMedia() is not supported in your browser');
    }

    window.URL = window.URL || window.webkitURL;
    navigator.getUserMedia = navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia;

    var video = document.querySelector('video');
    var streamRecorder;
    var webcamstream;

    if (navigator.getUserMedia) {
        navigator.getUserMedia({audio: true, video: true}, function (stream) {
            video.src = window.URL.createObjectURL(stream);
            webcamstream = stream;
//  streamrecorder = webcamstream.record();
        }, onVideoFail);
    } else {
        alert('failed');
    }

    function startRecording() {
        streamRecorder = webcamstream.record();
        setTimeout(stopRecording, 10000);
    }
    function stopRecording() {
        streamRecorder.getRecordedData(postVideoToServer);
    }
    function postVideoToServer(videoblob) {

        var data = {};
        data.video = videoblob;
        data.metadata = 'test metadata';
        data.action = "upload_video";
        jQuery.post("http://test.local/company/leju/8/uploadvideo.php", data, onUploadSuccess);
    }
    function onUploadSuccess() {
        alert('video uploaded');
    }

</script>

<div id="webcamcontrols">
    <button class="recordbutton" onclick="startRecording();">RECORD</button>
</div>