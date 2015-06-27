<?php
    if (!empty($_POST['md'])) {
        header('Content-Type: text/plain');

        $input = $_POST['md'];
        if (filter_var($input, FILTER_VALIDATE_URL))
            $input = @file_get_contents($input);

        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w")
        );

        $cwd = __DIR__;
        $bbcode_writer = 'pandoc_spigotmc.lua';

        $process = proc_open("pandoc -t $bbcode_writer", $descriptorspec, $pipes, $cwd);
        if (is_resource($process)) {
            fwrite($pipes[0], $input);
            fclose($pipes[0]);

            echo stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $return_value = proc_close($process);
        }

        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SpigotMC Markdown to BBCode Converter</title>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap/3.3.5/css/bootstrap.min.css">

    <!--[if lt IE 9]>
      <script src="//cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
        <div class="page-header">
            <h1>Markdown to BBCode Converter</h1>
        </div>
        <form role="form" method="post">
            <div class="form-group">
                <label for="md">Markdown</label>
                <textarea class="form-control" id="md" name="md" placeholder="Enter Markdown here" rows="25" style="font-family: monospace;"></textarea>
            </div>
            <button id="submit" type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!--[if lt IE 9]>
    <script src="//cdn.jsdelivr.net/jquery/1.11.3/jquery.min.js"></script>
    <![endif]-->
    <!--[if gte IE 9]><!-->
    <script src="//cdn.jsdelivr.net/jquery/2.1.4/jquery.min.js"></script>
    <!--<![endif]-->

    <script>
        $(function() {
            $("#submit").click(function(event) {
                event.preventDefault();
                $.post("", { "md": $("#md").val() }, function(data) {
                    $("#md").val(data);
                }, "text");
            });
        });
    </script>
  </body>
</html>
