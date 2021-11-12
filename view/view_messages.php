<!DOCTYPE html>
<html>
    <head>
        <title><?= $recipient->pseudo ?>'s Messages</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"><?= $recipient->pseudo ?>'s Messages</div>
        <?php include('view/menu.html'); ?>
        <div class="main">
            <form id="message_form" action="message/index/<?= $recipient->pseudo ?>" method="post">
                Type here to leave a message:<br>
                <textarea id="body" name="body" rows='3'></textarea><br>
                <input id="private" name="private" type="checkbox">Private message<br>
                <input id="post" type="submit" value="Post">
            </form>
            
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <p>These are <?= $recipient->pseudo ?>'s messages:</p>
            <table id="message_list" class="message_list">
                <tr>
                    <th>Date/Time</th>
                    <th>Author</th>
                    <th>Message</th>
                    <th>Private?</th>
                    <th>Action</th>
                </tr>
                <?php foreach($messages as $message): ?>
                    <?php if(($message->private && ($message->author == $user || $message->recipient == $user)) || !$message->private): ?>
                        <tr>
                            <td><?= $message->date_time ?></td>
                            <td><a href='member/profile/<?= $message->author->pseudo?>'><?= $message->author->pseudo ?></a></td>
                            <td><?= $message->body ?></td>
                            <td><input type='checkbox' disabled <?= ($message->private ? ' checked' : '') ?>></td>
                            <td>
                                <?php if($user == $message->author || $user == $message->recipient): ?>
                                    <form class='link' action='message/delete' method='post' >
                                    	<input type='text' name='param' value='<?= $message->post_id ?>' hidden>
                                    	<input type='submit' value='erase'>
                                    </form>   
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif;?>
                <?php endforeach; ?>               
            </table>
        </div>
    </body>
</html>