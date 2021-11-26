<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?=$web_root?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <title>Sign Up</title>
    <script>
        let pseudo,password,password_confirm,pattern;
        document.onreadystatechange = function(){
            if(document.readyState='complete'){
                pseudo =document.getElementById("pseudo");
                password =document.getElementById("password");
                password_confirm =document.getElementById("password_confirm");

            }
        };
        function checkPseudo(){
             errPseudo.innerHTML="";
            pattern=/^.{3,16}$/;
            let state=false;
            
            if(pattern.test(pseudo.value)==false){
                errPseudo.innerHTML="<span style='color:red'>doit contenir entre 8 et 16 caract&egrave;res</span>";
               
            }
            else if(!/^[a-zA-Z][a-zA-Z0-9]*$/.test(pseudo.value)){
                errPseudo.innerHTML+="<span style='color:red'>doit contenir que des lettres et des chiffres</span>";
               
            }else{
                state=true;
            }
            return state;
            

        };
        function checkPassword(){
            errPassword.innerHTML="";
            pattern=/^.{8,16}$/;
            let state=false;

            if(pattern.test(password.value)==false){
                errPassword.innerHTML+="<span style='color:red'>-doit contenir entre 8 et 16 caract&egrave;res</span>";
                
            }
            if(!/[A-Z]/.test(password.value)){
                errPassword.innerHTML+="<span style='color:red'>-doit au moin un caract&egrave;res majuscule</span>";
              
            }
            if(!/\d/.test(password.value)){
                errPassword.innerHTML+="<span style='color:red'>-doit au moins un chiffre-</span>";
            }
            if(!/['";:,.\/?\\-]/.test(password.value)){
                errPassword.innerHTML+="<span style='color:red'>-doit au moins une ponctuation</span>";
            }else{
                state=true;
            }
            return state;
        };
        function checkPasswords(){
            errPassword_confirm.innerHTML="";
            let state=false;
            if(checkPassword()){
                if(password.value!=password_confirm.value){
                errPassword_confirm.innerHTML="<span style='color:red'>-doit être identiques</span>";
                }
            }else{
                state = true;
            }
            return state;
            
        };
        function checkAll(){

            if(checkPseudo()&&checkPassword()&&checkPasswords()){
                
                return true;
            }else{
                console.log("erreur");
                return false;
            }
        }
        
           

    </script>
</head>
<body>
    <div class="title">Sign Up</div>
    <div class="menu">
        <a href="index.php">Home</a>
    </div>
    <div class="main">
        Please enter your details to sign up :
        <br><br>
        <form id="signupForm" action="main/signup" method="POST" onsubmit="checkAll()">
            <table>
                <tr>
                    <td>Pseudo:</td>
                    <td><input type="text" id="pseudo" name="pseudo" size="16" value="<?= $pseudo ?>" onchange="checkPseudo()" ></td>
                    <td id="errPseudo"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" id="password" name="password" size="16" value="<?=$password?>" onchange="checkPassword()"></td>
                    <td id="errPassword"></td>
                </tr>
                <tr>
                    <td>Confirm your Password:</td>
                    <td><input type="password" id="password_confirm" name ="password_confirm"  size="16" value="<?=$password_confirm ?>" onchange="checkPasswords()" > </td>
                    <td id="errPassword_confirm"></td>
                </tr>
            </table>
            <input id="submit" type="submit" value="Sign Up" >
        </form>
        <?php if(count($errors)!=0):?>
            <div class="errors">
                <br><br>
                <p>Please correct the following errors : </p>
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?=$error?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>