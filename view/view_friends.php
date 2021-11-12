<!DOCTYPE html>
<html>
    <head>
        <title>Your Friends</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Your Friends</div>
        <?php include('view/menu.html'); ?>
        <div class="main">

            <h2>Your mutual friends</h2>
            <ul>  
                <?php foreach($relations as $relation): ?>
                    <?php if($relation['follower']=="1" && $relation['followee']=="1"):?>
                       <li><a href="member/profile/<?=$relation['pseudo'] ?>"> <?=$relation['pseudo'] ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>    
            </ul>

            <h2>Your followers</h2>
            <ul>
                <?php foreach($relations as $relation): ?>
                    <?php if($relation['follower']=="0" && $relation['followee']=="1"):?>
                       <li><a href="member/profile/<?=$relation['pseudo'] ?>"> <?=$relation['pseudo'] ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>                
            </ul>

            <h2>Member you are following </h2>
            <ul>
                <?php foreach($relations as $relation): ?>
                    <?php if($relation['follower']=="1" && $relation['followee']=="0"):?>
                       <li><a href="member/profile/<?=$relation['pseudo'] ?>"> <?=$relation['pseudo'] ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>                
            </ul>

        </div>
    </body>
</html>