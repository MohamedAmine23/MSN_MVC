<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?=$web_root?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <title>Sign Up</title>
</head>
<body>
    <div class="title">Sign Up</div>
    <div class="menu">
        <a href="index.php">Home</a>
    </div>
    <div class="main">
        Please enter your details to sign up :
        <br><br>
        <form id="signupForm" action="main/signup" method="POST">
            <table>
                <tr>
                    <td>Pseudo:</td>
                    <td><input type="text" id="pseudo" name="pseudo" size="16" value="<?= $pseudo ?>"</td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input type="password" id="password" name="password" size="16" value="<?=$password?>"></td>
                </tr>
                <tr>
                    <td>Confirm your Password:</td>
                    <td><input type="password" id="password_confirm" name ="password_confirm"  size="16" value="<?=$password_confirm ?>"> </td>
                </tr>
            </table>
            <input type="submit" value="Sign Up">
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