<head>
  <title>Encrypted Message Storage and Retrieval</title>
  <style>
    * {
      font-family: Arial, sans-serif;
    }
h1 {
  text-align: center;
  margin-top: 20px;
  font-size: 36px;
}

form {
  margin: 20px;
  padding: 20px;
  background-color: #f2f2f2;
  border: 1px solid gray;
  border-radius: 5px;
}

input[type="text"], textarea {
  margin-bottom: 20px;
  padding: 10px;
  width: 100%;
  border: 1px solid gray;
  border-radius: 5px;
}

input[type="submit"] {
  padding: 10px 20px;
  background-color: blue;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

#message-popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #f2f2f2;
  padding: 20px;
  border: 1px solid gray;
  border-radius: 5px;
  display: none;
}

#message-popup.show {
  display: block;
}

.close-button {
  float: right;
  cursor: pointer;
  margin-top: -10px;
  margin-right: -10px;
  padding: 5px;
  background-color: gray;
  color: white;
  border-radius: 50%;
}
 </style>
</head>
<body>
  <h1>Encrypted Message Storage and Retrieval</h1>
  <script>
    function showMessage(message) {
      var popup = document.getElementById("message-popup");
      popup.innerHTML = message + '<div class="close-button" onclick="hideMessage()">X</div>';
      popup.classList.add("show");
    }
    
    function hideMessage() {
      var popup = document.getElementById("message-popup");
      popup.innerHTML = "";
      popup.classList.remove("show");
    }
  </script>
  <div id="message-popup"></div>
  <?php
if (isset($_POST['message']) && isset($_POST['key']) && isset($_POST['file-name'])) {
// Store the encrypted message as a text file
$message = $_POST['message'];
$key = $_POST['key'];
$filename = $_POST['file-name'] . '.txt';
file_put_contents($filename, openssl_encrypt($message, 'AES-128-ECB', $key));
echo '<p style="text-align: center;">Message saved successfully as file ' . $filename . '!</p><br><br>';
}

if (isset($_POST['key-to-open']) && isset($_POST['filename'])) {
  // Decrypt the message and display it
  $key = $_POST['key-to-open'];
  $filename = $_POST['filename'];
  $message = openssl_decrypt(file_get_contents($filename), 'AES-128-ECB', $key);
  echo '<script>showMessage("' . $message . '")</script>';
}

?>

<form action="index.php" method="post">
  Enter message:<br>
  <textarea name="message"></textarea><br><br>
  Enter file name:<br>
  <input type="text" name="file-name"><br><br>
  Enter encryption key (16 characters, numbers, letters, and capital letters):<br>
  <input type="text" name="key"><br><br>
  <input type="submit" value="Save Message">
</form>

<br><br>

<form action="index.php" method="post">
  Enter filename:<br>
  <input type="text" name="filename"><br><br>
  Enter encryption key to open the message:<br>
  <input type="text" name="key-to-open"><br><br>
  <input type="submit" value="Open Message">
</form>