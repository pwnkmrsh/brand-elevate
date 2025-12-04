<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Social Media Post Generator</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        textarea {
            width: 100%;
            height: 120px;
            margin-bottom: 10px;
        }

        select,
        button {
            padding: 10px;
            font-size: 16px;
        }

        .output {
            margin-top: 20px;
            padding: 15px;
            background: #f4f4f4;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <h2>Generate Social Media Post</h2>

    <form action="generate.php" method="POST">
        <label>Platform:</label><br>
        <select name="platform" required>
            <option value="facebook">Facebook Post</option>
            <option value="linkedin">LinkedIn Post</option>
        </select>
        <br><br>

        <label>Describe what post you want:</label><br>
        <textarea name="topic" placeholder="e.g. Promotion announcement, motivation post, new product launch..." required></textarea>

        <button type="submit">Generate Post</button>
    </form>
    <!--  -->
</body>

</html>