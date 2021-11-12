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
                <?php foreach($relations as $relation): ?>
                    <?php if($member->pseudo===$relation['pseudo']): ?>
                        <li>
                            <a href="member/profile/<?=$member->pseudo?>"><?=$member->pseudo?></a>
                            <form class="link" action="member/follow" method="Post">
                            <input type="text" name="param" value="<?=$member->pseudo?>" hidden>
                            
                            <?php if($relation['follower']==="0"):?>

                                <?php if($relation['followee']==="0"): ?>
                                    <input type="submit" name="action" value="[follow]">

                                <?php elseif($relation['followee']==="0"): ?>
                                    → is following you<input type="submit" name="action" value="[recip]">

                                <?php endif; ?>

                            <?php elseif($relation['follower']==="1"  ):?>

                                <?php if($relation['followee']==="0"): ?>
                                    ← you are following

                                <?php elseif($relation['followee']==="1"): ?>
                                    ↔ is a mutual friend

                                <?php endif; ?>

                                <input type="submit" name="action" value="[drop]">

                            <?php endif; ?> 

                            </form>   
                        </li>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach;  ?>

        </ul>
    </div>
</body>
</html>