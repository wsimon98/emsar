<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$decryptedMessage = ""; // Variable to hold the decrypted message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    if (isset($_POST['message'], $_POST['key'], $_POST['file-name'])) {
        $message = sanitize($_POST['message']);
        $key = sanitize($_POST['key']);
        $filename = sanitize($_POST['file-name']);
        if (strpos($filename, '.') === false) {
            $filename .= '.txt'; // Default to .txt if no extension is provided
        }

        if (strlen($key) !== 16) {
            echo '<p style="text-align: center; color: red;">Error: Encryption key must be 16 characters.</p>';
        } elseif (file_exists($filename)) {
            echo '<p style="text-align: center; color: orange;">File already exists! Choose a different name or delete the existing file.</p>';
        } else {
            $encryptedMessage = openssl_encrypt($message, 'AES-128-ECB', $key);
            $result = file_put_contents($filename, $encryptedMessage);
            if ($result === false) {
                echo '<p style="text-align: center; color: red;">Error: Unable to save the file. Check permissions.</p>';
            } else {
                echo '<p style="text-align: center; color: green;">Message saved successfully as file: ' . $filename . '</p>';
            }
        }
    }

    if (isset($_POST['key-to-open'], $_POST['filename'])) {
        $key = sanitize($_POST['key-to-open']);
        $filename = sanitize($_POST['filename']);
        if (strpos($filename, '.') === false) {
            $filename .= '.txt'; // Default to .txt if no extension is provided
        }

        if (file_exists($filename)) {
            $encryptedMessage = file_get_contents($filename);
            $decryptedMessage = openssl_decrypt($encryptedMessage, 'AES-128-ECB', $key);

            if ($decryptedMessage === false) {
                $decryptedMessage = '<span style="color: red;">Error: Incorrect key or corrupted file.</span>';
            }
        } else {
            $decryptedMessage = '<span style="color: red;">Error: File not found.</span>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encrypted Message Storage and Retrieval</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin: 20px;
            color: #007bff;
        }
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .output {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Encrypted Message Storage and Retrieval</h1>

    <form action="" method="post">
        <label>Enter Message:</label>
        <textarea name="message" required></textarea>
        <label>Enter File Name:</label>
        <input type="text" name="file-name" required>
        <label>Enter Encryption Key (16 characters):</label>
        <input type="text" name="key" maxlength="16" required>
        <input type="submit" value="Save Message">
    </form>

    <form action="" method="post">
        <label>Enter Filename:</label>
        <input type="text" name="filename" required>
        <label>Enter Decryption Key:</label>
        <input type="text" name="key-to-open" maxlength="16" required>
        <input type="submit" value="Open Message">
    </form>

    <?php if (!empty($decryptedMessage)): ?>
        <div class="output">
            <strong>Decrypted Message:</strong>
            <p><?= $decryptedMessage ?></p>
        </div>
    <?php endif; ?>
</body>
</html>
