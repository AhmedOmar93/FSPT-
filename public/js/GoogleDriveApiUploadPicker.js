            function onApiLoad() {
                gapi.load('auth', { 'callback': onAuthApiLoad });
                gapi.load('picker');
            }
            function onAuthApiLoad() {
                window.gapi.auth.authorize({
                    'client_id': '1032700748033-ckuj9g5a6v4i78l776hf6oll1b7v0992.apps.googleusercontent.com',
                    'scope': ['https://www.googleapis.com/auth/drive.apps.readonly']//https://www.googleapis.com/auth/drive']
                }, handleAuthResult);
            }
            var oauthToken;
            function handleAuthResult(authResult) {
                if (authResult && !authResult.error) {
                    oauthToken = authResult.access_token;
                    createPicker();
                }
            }
            function createPicker() {
                var picker = new google.picker.PickerBuilder()
                    .addView(new google.picker.DocsUploadView())
                    .addView(new google.picker.DocsView())
                    .setOAuthToken(oauthToken)
                    //.setDeveloperKey('AIzaSyAFJn8GcUwUdqtV5F1uDcVUTX7hajFB8tU')
                    .setCallback(pickerCallback)
                    .build();
                picker.setVisible(true);
            }

            function pickerCallback(data) {
                var url = 'nothing';
                var name = 'nothing';
                if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                    var doc = data[google.picker.Response.DOCUMENTS][0];
                    url = doc[google.picker.Document.URL];
                    name = doc[google.picker.Document.NAME];
                }
                document.getElementById('resultUrl').value = url;
                document.getElementById('resultName').value = name;
            }
  