<?php
session_start()
?>
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
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;

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
        #container {
            width: 70%;
            display: flex;
            justify-content: center;
            border: 3px solid #049ada;
            border-radius: 20px;
            box-shadow: 0px 40px 0px 0px rgb(84, 91, 98);
            z-index: 1;
            background: white;
            }
        #box{ 
            width: 50%;
            height: 450px;
            border-radius: 20px;
            justify-content: center;
            
        }
        #picture {
            width: 100%;
            height: 100%;
            object-fit: cover; 
            display: block;
            border-radius: 20px;
        }
        #Login {
            width: 50%;
            background: radial-gradient( #e6e3e3,white);
            height: 450px;
            text-align: center;
            border-radius: 20px;
            padding: 50px 60px;
            font-family:'Times New Roman', Times, serif;
        }
        #Login h1 {
            margin-bottom: 10px;
            color: #049ada;
            
        }
        #Login .input {
            width: 100%;
            height: 50px;
            margin-top: 45px;

        }
        .input input {
            width: 100%;
            height: 100%;
            border: 1px solid rgb(179, 162, 162);
            background: rgb(255, 255, 255);
            border-radius: 40px;
            font-size: 16pt;
            padding: 30px 45px 30px 20px;
        }
        .input input::placeholder {
            color: rgb(196, 196, 196);
            font-size: 15pt;
        }
        .input input:focus {
            outline: 1px solid #049ada;
            box-shadow: 0px 0px 20px 0px #8aabe6;
        }
        button {
            width: 40%;
            height: 45px;
            background: white;
            border: 2px solid grey;
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
        p {
            margin-top: 40px;
            display: flex;
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

}
    
              
    </style>
</head>
<body>
    <div id="container" > 
        <div id="box">
            <img id="picture" src="../images/JustAQuiz.png" alt="JustAQuiz">
        </div>
        <div id="Login">
            <form method="post">        
                <h1>Login to your Account</h1>                
                <div class="input"><input type="email" name="loginEmail" placeholder="Email" required></div>
                <div class="input"><input type="password" name="loginPassword" placeholder="Password" required></div>
                <p>Don't have an account?&nbsp  <a  id="login-link" href="Sign Up.php">Sign Up</a></p>
                <button type="submit" name="loginBtn">Login</button>
            </form>
        </div>
    </div>  
    <div class="wrapper"></div> 

    <?php
        if (isset($_POST['loginBtn'])) {
            include("../../main.php");
            $status=login($_POST['loginEmail'],$_POST['loginPassword'],$conn);
            if ($status){
                $user_id=$_SESSION['user_id'];
                $user_role=$_SESSION['role_id'];
                echo $user_id;
                if ($user_role==1){
                    echo '<script>alert("Login successful")
                        window.location.href = "../Admin/AdminHome.php";
                        </script>';
                }else if ($user_role==3){
                    echo '<script>alert("Login successful")
                        window.location.href = "Home.php";
                        </script>';
                }else if ($user_role==2){
                    echo '<script>alert("Login successful")
                        window.location.href = "../Instructor/InstructorHome.php";
                        </script>';
                }
            }else{
                echo '<script>alert("Login failed");
                    window.location.href = "Login.php";
                    </script>';
            }
        }
    ?>
</body>
</html>