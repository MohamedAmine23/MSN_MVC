<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?= $web_root ?>">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    <title>Members</title>
</head>
<body>
    <div class="title">Members</div>
    <?php include("view/menu.html"); ?>
    <div class="main">
        <ul>
            <?php foreach($members as $member): ?>
                <li><a href="member/profile/<?=$member->pseudo?>"><?=$member->pseudo?></a></li>
            <?php endforeach;  ?>

        </ul>
    </div>
</body>
</html>