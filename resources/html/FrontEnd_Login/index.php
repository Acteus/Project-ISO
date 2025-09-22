<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Kwadra</title>
  <!-- Google Fonts: Montserrat + Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: 
        linear-gradient(135deg, rgba(66,133,244,0.45), rgba(255,215,0,0.35)),
        url('JoseRizalUniversityy.jpg') no-repeat center center/cover;
      font-family: 'Poppins', Arial, sans-serif;
      color: white;
      text-align: center;
    }

    .overlay {
      background: rgba(0, 0, 0, 0.55);
      padding: 60px 70px;
      border-radius: 20px;
      box-shadow: 0 10px 35px rgba(0,0,0,0.3);
      backdrop-filter: blur(6px);
    }

    h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 70px;
      font-weight: 700;
      letter-spacing: 3px;
      margin-bottom: 15px;
      background: linear-gradient(90deg, #FFD700, #ffffff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .desc {
      font-size: 24px;
      font-weight: 400;
      margin-bottom: 40px;
      color: #f0f0f0;
      letter-spacing: 1px;
      text-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }

.btn {
      padding: 16px 42px;
      font-size: 20px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(90deg, #C0C0C0, #E0E0E0); /* Silver gradient */
      color: #222;
      cursor: pointer;
      text-decoration: none;
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      transition: 
        transform 0.2s ease, 
        box-shadow 0.2s ease,
        background 0.2s ease,
        color 0.2s ease;
      box-shadow: 0 4px 12px rgba(66,133,244,0.4);
      display: inline-block;
    }

    .btn:hover {
      background: linear-gradient(90deg, #4285F4, #2c6cd6); /* Blue gradient */
      color: #fff;
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(66,133,244,0.6);
    }
  </style>
</head>
<body>
  <div class="overlay">
    <h1>Kwadra</h1>
    <div class="desc">Validation System</div>
    <a href="Login.php" class="btn">Enter</a>
  </div>
</body>
</html>

