<!DOCTYPE html>
<html>
    <head>
        <title><?= $recipient->pseudo ?>'s Messages</title>
        <base href="<?= $web_root ?>"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script>

            const messages = <?= $messages_json ?>;
            let tblMessages;
            let sortColumn = 'datetime';
            let sortAscending = false;


            document.onreadystatechange=function(){
                if (document.readyState==='complete'){
                    tblMessages=document.getElementById('message_list');
                    displayTable();
                }

            }
            function displayTable(){
                let html = "<tr><th id='col_datetime' onclick='sort(\"datetime\");'>Date/Time</th>" +
                        "<th id='col_author' onclick='sort(\"author\");'>Author</th>" + 
                        "<th id='col_body' onclick='sort(\"body\");'>Message</th>" + 
                        "<th>Private?</th>" + 
                        "<th>Action</th></tr>";
                for (let m of messages) {
                    html += "<tr>";
                    html += "<td>" + m.datetime + "</td>";
                    html += "<td><a href='member/profile/"+m.author+"'>"+m.author+"</a></td>";
                    html += "<td>" + m.body + "</td>";
                    html += "<td><input type='checkbox' disabled" + (m.private === '1' ? ' checked' : '') + "></td>";
                    html += "<td>" + (m.erasable ? "<form class='link' action='message/delete' method='post'><input type='text' name='id_message' value='"+m.id+"' hidden><input type='submit' value='erase'></form>" : "") + "</td>";
                    html += "</tr>";
                }
                tblMessages.innerHTML = html;
                document.getElementById('col_'+ sortColumn).innerHTML += sortAscending ? '&#9650;': '&#9660;';
            }
            // un tableau d'objets
            const objs = [{"f1": "abc", "f2": "def"}, {"f1": "zzz", "f2": "aaa"}];

            // contient le nom de la propriété sur base de laquelle on veut trier
            const field = "f2";    

            // utilisation de la méthode sort() de la classe Array  
            function sortMessages(){
                messages.sort(function (a,b) {
                                if (a[sortColumn] < b[sortColumn])
                                    return sortAscending ? -1 : 1;
                                if (a[sortColumn] > b[sortColumn])
                                    return sortAscending ? 1:-1;
                                return 0;
                            });
                        
            }
            function sort(field){
                if (field === sortColumn)
                    sortAscending=!sortAscending;
                else{
                    sortColumn=field;
                    sorAscending=true;

                }
                sortMessages();
                displayTable();
            }
            

            // affichage du résultat dans la console
            console.log(objs);
        </script>
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
                <!-- we could delet because we generate  it with js but if js don't work we have that -->
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