<html>

    <head>
        <title>Hashtaglexikon</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h1>Das Das-Podcast-UFO-Telegram-Gruppe-Hashtag-Lexikon</h1>
        <div class="text">
            Dies ist eine (unvollständige) Sammlung von Hashtags, die in der "DAS PODCAST UFO"-Telegramgruppe Verwendung finden. Definitionen sind für alle Hashtags angegeben, die
            häufiger als 50 mal verwendet wurden, sowie auch für einige ausgewählte seltenere. Solltest du eine Definition für einen weiteren Hashtag haben, wende dich bitte an <a href="https://t.me/Imbecillus">@Imbecillus</a>.
        </div>

        <table>
        <?php
            function startsWith($haystack, $needle) {
                return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
            }

            // Open data csv
            $csvfile = fopen("data.csv", 'r');
            $line = fgetcsv($csvfile, 0, ";");

            // Set head row
            echo("<tr class=\"head\"> <td>");
            echo($line[0]);
            echo("</td> <td>");
            echo($line[2]);
            echo("</td> </tr>");
            $line = fgetcsv($csvfile, 0, ";");

            // Create the rest of the table
            $rowstyle = "one";
            while ($line != null) {
                if (strcmp("", $line[2]) != 0) {
                    // Find hashtag mentions in the description and convert them into links
                    $words = explode(" ", $line[2]);
                    for ($i = 0; $i < count($words); $i++) {
                        if (startsWith($words[$i], '#')) {
                            $hashtag = rtrim($words[$i], ",;.");
                            if (strcmp($hashtag, "#werbung") == 0) {
                                $hashtag = "hashtag_werbung";
                            }
                            $hashtaglink = "<a href=\"$hashtag\">$words[$i]</a>";
                            $line[2] = str_replace($words[$i], $hashtaglink, $line[2]);
                        }
                    }

                    // Generate table row
                    $hashtagname = ltrim($line[0], '#');
                    if (strcmp($hashtagname, "werbung") == 0) {
                        $hashtagname = "hashtag_werbung";
                    }
                    echo("<tr id=\"$hashtagname\"> <td>");
                    echo($line[0]);
                    echo("</td> <td>");
                    echo($line[2]);
                    echo("</td> </tr>\n");
                }
                $line = fgetcsv($csvfile, 0, ";");
            }
            fclose($csvfile);
        ?>
        </table>

        <div class = "footer">
            © 2020 Tim K / <a href="https://t.me/Imbecillus">@Imbecillus</a><br />

        </div>

    </body>

</html>