<?php include '../general/user_header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Reviewer Landing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../styles/documents.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
    <link rel="icon" type="png" href="../../img/SLU Logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            line-height: 1.6;
        }

        .template-gallery {
            display: flex;
            flex-direction: column;
            padding: 30px;
            margin: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .templates {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .template {
            flex: 1;
            max-width: 150px;
            text-align: center;
        }

        .template img {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: transform 0.3s;
        }

        .template img:hover {
            transform: scale(1.05);
        }

        .recent-documents {
            padding: 30px;
            margin: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .recent-documents h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #007bff;
        }

        .documents {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .document {
            width: calc(25% - 15px);
            background-color: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .document:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.15);
        }

        .document img {
            width: 100%;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .document p {
            font-size: 1rem;
            color: #555;
        }

        .profile img {
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }
    </style>
</head>
<body>
  

    <div class="template-gallery">
        <h1>Start a new document</h1>
        <div class="templates">
            <div class="template">
                <img src="https://via.placeholder.com/150x200" alt="Blank Document">
                <p>Blank Document</p>
            </div>
            <div class="template">
                <img src="https://via.placeholder.com/150x200" alt="Report">
                <p>Report</p>
            </div>
            <div class="template">
                <img src="https://via.placeholder.com/150x200" alt="Essay">
                <p>Essay</p>
            </div>
        </div>
    </div>

    <div class="recent-documents">
        <h2>Recent Documents</h2>
        <div class="documents">
            <div class="document">
                <img src="https://via.placeholder.com/150x200" alt="Document 1">
                <p>Document 1</p>
            </div>
            <div class="document">
                <img src="https://via.placeholder.com/150x200" alt="Document 2">
                <p>Document 2</p>
            </div>
            <div class="document">
                <img src="https://via.placeholder.com/150x200" alt="Document 3">
                <p>Document 3</p>
            </div>
            <div class="document">
                <img src="https://via.placeholder.com/150x200" alt="Document 4">
                <p>Document 4</p>
            </div>
        </div>
    </div>
</body>
</html>
