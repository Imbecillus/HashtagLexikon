<html>

    <head>
        <title>Hashtaglexikon</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <script type="text/javascript">
        function highlightRowById(id) {
            // De-highlight everything
            var active_rows = document.getElementsByClassName("active");
            if (active_rows.length > 0) {
                for (i = 0; i < active_rows.length; i++) {
                    active_rows[i].classList.remove('active');
                }
            }

            // Highlight new row
            var row = document.getElementById(id);
            row.classList.add('active');
        }

        function filterByCategory(category) {
            // Get all rows
            var all_rows = document.getElementsByTagName("table")[0].rows;

            // Hide all rows that do not have the category class
            // Starting i at one, so that the head row gets skipped
            for (i = 1; i < all_rows.length; i++) {
                var row = all_rows[i];
                if (!row.classList.contains(category)) {
                    row.classList.add("hidden");
                }
            }
        }

        function restoreAll() {
            // Get all rows
            var all_rows = document.getElementsByTagName("table")[0].rows;

            // Remove class "hidden" from all rows
            for (i = 0; i < all_rows.length; i++) {
                var row = all_rows[i];
                row.classList.remove("hidden");
            }

            // Get all links
            var all_links = document.getElementsByClassName("category");

            // Remove class "filtered" from all links
            for (i = 0; i < all_links.length; i++) {
                var link = all_links[i];
                link.classList.remove("filtered");
            }
        }

        function setFiltered(e) {
            e.classList.add("filtered")
        }
    </script>

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

            // Create array for hashtag categories
            $categories = [];

            // Set head row
            echo("<tr class=\"head\"> <td>");
            echo($line[0]);
            echo("</td> <td>");
            echo($line[2]);
            echo("</td> </tr>");
            $line = fgetcsv($csvfile, 0, ";");

            // Create the rest of the table
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
                            $hashtagname = ltrim($hashtag, '#');
                            if (strcmp($hashtagname, "werbung") == 0) {
                                $hashtagname = "hashtag_werbung";
                            }
                            $hashtaglink = "<a onClick='highlightRowById(\"$hashtagname\")' href=\"$hashtag\">$words[$i]</a>";
                            $line[2] = str_replace($words[$i], $hashtaglink, $line[2]);
                        }
                    }

                    // Generate table row
                    $hashtagname = ltrim($line[0], '#');
                    if (strcmp($hashtagname, "werbung") == 0) {
                        $hashtagname = "hashtag_werbung";
                    }
                    echo("<tr id=\"$hashtagname\" class=\"$line[3]\"> <td>");
                    echo($line[0]);
                    echo("</td> <td>");
                    echo($line[2]);
                    echo("</td> </tr>\n");

                    // Look for categories that are not yet included in $categories
                    $line_categories = explode(' ', $line[3]);
                    for ($i = 0; $i < count($line_categories); $i++) {
                        if (!in_array($line_categories[$i], $categories, false) & strcmp($line_categories[$i], "") != 0) {
                            array_push($categories, $line_categories[$i]);
                        }
                    }
                }
                $line = fgetcsv($csvfile, 0, ";");
            }

            // Create table of categories
        ?>
            <div class="toc">
                <h3>Filters</h3>
                <?php
                for ($i = 0; $i < count($categories); $i++) {
                    $category = $categories[$i];
                    ?> <a href="#" onClick="filterByCategory('<?php echo($category); ?>'); setFiltered(this);" class="category">#<?php echo($category); ?></a> <?php
                }
                echo("<a class=\"category\" id=\"reset\" href=\"#\" onClick=\"restoreAll()\">Filter entfernen</a>");
                ?>
            </div>
        <?php

            fclose($csvfile);
        ?>
        </table>

        <div class = "footer">
            © 2020 Tim K / <a href="https://t.me/Imbecillus">@Imbecillus</a><br />
        </div>

    </body>

</html>