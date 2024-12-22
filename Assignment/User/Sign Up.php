<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Rubik+Mono+One&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Itim&family=Londrina+Outline&family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap');
        :root {
        --accent: #049ada;
        --border-width: 5px;
        --border-radius: 60px;
        --font-size: 15px;
        }
    * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Sour Gummy", sans-serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings:
                "wdth" 100;
            
        }
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .container2 {
        height: 50px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        background-color: white;
        font-family: sans-serif;
    }

    .toggle {
        position: relative;
        border: solid var(--border-width) var(--accent);
        border-radius: var(--border-radius);
        transition: transform cubic-bezier(0, 0, 0.30, 2) .4s;
        transform-style: preserve-3d;
        perspective: 800px;
    }

    .toggle>input[type="radio"] {
        display: none;
    }

    .toggle>#choice1:checked~#flap {
        transform: rotateY(-180deg);
    }

    .toggle>#choice1:checked~#flap>.content {
        transform: rotateY(-180deg);
    }

    .toggle>#choice2:checked~#flap {
        transform: rotateY(0deg);
    }

    .toggle>label {
        display: inline-block;
        min-width: 100px;
        padding: 5px;
        font-size: var(--font-size);
        text-align: center;
        color: var(--accent);
        cursor: pointer;
    }

    .toggle>label,
    .toggle>#flap {
        font-weight: bold;
        text-transform: capitalize;
    }

    .toggle>#flap {
        position: absolute;
        top: calc( 0px - var(--border-width));
        left: 50%;
        height: calc(100% + var(--border-width) * 2);
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: var(--font-size);
        background-color: var(--accent);
        border-top-right-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
        transform-style: preserve-3d;
        transform-origin: left;
        transition: transform cubic-bezier(0.4, 0, 0.2, 1) .5s;
    }

    .toggle>#flap>.content {
        color: #333;
        transition: transform 0s linear .25s;
        transform-style: preserve-3d;
    }
        
        #container {
            width: 70%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            border: 3px solid #049ada;
            border-radius: 20px;
            box-shadow: 0px 40px 0px 0px rgb(84, 91, 98);
            z-index: 1;
            align-items: center;
            background: white;
            }
            .wrapper {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            
            background-color: #FFE0AF;
            background-image:
            linear-gradient(45deg, #FFDD88 25%, transparent 25%, transparent 75%, #FFDD88 75%, #FFDD88), 
            linear-gradient(45deg, #FFDD88 25%, transparent 25%, transparent 75%, #FFDD88 75%, #FFDD88),
            linear-gradient(45deg, transparent, transparent 50%, #FFC87F 50%, #FFC87F);
            background-size: 100px 100px;
            background-position: 0 0, 50px 50px, 50px 0px;
            -webkit-animation: scroll 5s linear infinite;
            }

            @-webkit-keyframes scroll {
            from { background-position: 0 0, 50px 50px, 50px 0; }
            to { background-position: -100px -100px, -50px -50px, -50px -100px; }
            }
        #box{ 
            width: 50%;
            height: 600px;
            border-radius: 20px;
            justify-content: center;
            
        }
        #picture {
            width: 100%;
            height: 100%;
            object-fit: cover; 
            display: block;
            border-radius: 20px;
            background: blue;
        }
        #Login {
            width: 50%;
            background: white;
            height: 100%%;
            text-align: center;
            border-radius: 20px;
            padding: 30px 60px;
            font-family:'Times New Roman', Times, serif;
            align-items:center;
        }
        #Login h1 {
            margin-bottom: 10px;
            font-weight: bold;
            color: #049ada;
        }
        #Login .input {
            width: 100%;
            height: 50px;
            margin-top: 30px;

        }
        .input input {
            width: 100%;
            height: 100%;
            border: 1px solid rgb(179, 162, 162);
            background: rgb(255, 255, 255);
            border-radius: 40px;
            font-size: 16px;
            padding: 20px 45px 20px 20px;
        }
        .input input::placeholder {
            color: rgb(196, 196, 196);
        }
        .input input:focus {
            outline: 1px solid #049ada;
            box-shadow: 0px 0px 20px 0px #8aabe6;
        }
        #login-link {
            height:30px;
        }
        button {
            width: 40%;
            height: 45px;
            background: white;
            border: 2px solid #049ada;
            border-radius: 40px;
            box-shadow: 0 0 10px gray;
            cursor: pointer;
            font-size: 16pt;
            margin-top: 20px;
            appearance: none;
            background-color: #FFFFFF;
            border-radius: 40em;
            box-shadow: #ADCFFF 0 -12px 6px inset;
            box-sizing: border-box;
            color: #000000;
            cursor: pointer;
            display: inline-block;
            letter-spacing: -.24px;
            outline: none;
            padding: 10px;
            quotes: auto;
            text-align: center;
            text-decoration: none;
            transition: all .15s;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        button:hover {
        background-color: #FFC229;
        box-shadow: #FF6314 0 -6px 8px inset;
        transform: scale(1.125);
        }

        button:active {
        transform: scale(1.025);
        }
        #word {
            margin-top: 20px;
            display: flex;
            width: 100%;
            font-size: 15pt;
            color: #049ada;
        }


        @media (max-width: 768px) {
    * {
        flex-direction: column;
        align-items: center;
    }
    #box, #Login {
        width: 100%;
    }
    button {
        margin-left: 100px;
    }
}  
              
    </style>
</head>
<body>
<div id="container">
        <div id="box" >
            <img id="picture" src="../images/JustAQuiz.png" alt="JustAQuiz">
        </div>
        <div id="Login" >
            <h1>Create your Account</h1>  
                <div class="container2">
            <form method="post">  
                <div class="toggle">  
                    <input type="radio" id="choice1" name="choice" value="3">
                        <label for="choice1">Student</label>
               
                        <input type="radio" id="choice2" name="choice" value="2">
                        <label for="choice2">Instructor</label>
               
                        <div id="flap"><span class="content">Student</span></div>
                </div>            
            </div>  
                             
                <div class="input"><input type="text" name="Name" placeholder="Name" required></div>
                <div class="input"><input type="email" name="Email" placeholder="Email" required></div>
                <div class="input"><input type="password" name="password" placeholder="Password" required></div>
                <div class="input"><input type="password" name="confirm-password" placeholder="confirm-password" required></div>
                <p id="word">Already have an account?&nbsp<a href="Login.php" id="login-link">Login</a></p>
                <button name='signupBtn' type="submit">Sign Up</button>
            </form>
        </div>
    </div>
   
    <div class="wrapper"></div> 
    <script src="signup.js"></script>
    <?php
        if (isset($_POST['signupBtn'])) {
            include("../../main.php");
            $status=sign_up($_POST['Name'], $_POST['password'], $_POST['confirm-password'], $_POST['Email'], $conn, $_POST['choice']);
            if ($status){
                $user_id=$_SESSION['user_id'];
                $user_role=$_SESSION['role_id'];
                echo '<script>alert("Successful");
                        window.location.href = "Login.php";
                        </script>';
            }else{
                echo '<script>alert("Failed");
                    window.location.href = "Sign Up.php";
                    </script>';
            }
        }
    ?>
</body>
</html>