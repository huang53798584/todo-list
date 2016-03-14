<!DOCTYPE html>
<?php

require_once(__DIR__ . "/../config/constants.php");
require_once(__DIR__ . "/../controller/ensure_session.php");

require_once(__DIR__ . "/../config/config.php");
require_once(__DIR__ . "/../util/web.php");
require_once(__DIR__ . "/../util/security.php");
require_once(__DIR__ . "/../service/todo_service.php");
require_once(__DIR__ . "/../service/data_service.php");

//session_start();
$userId = $_SESSION[CURRENT_USER];
if (!isset($_SESSION["taskId"])) {
    redirect(VIEWS . "/home.php");
}

$taskId = $_SESSION["taskId"];
$task = getTask($taskId);
unset($_SESSION["taskId"]);
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="images/favicon.ico">

        <title>Never forget another Todo</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo CSS ?>/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo CSS ?>/todo.css" rel="stylesheet">

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="<?php echo JS ?>/ie-emulation-modes-warning.js"></script>

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo JS ?>/ie10-viewport-bug-workaround.js"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo APPLICATION_ROOT ?>">ToDo</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav pull-right">
                        <li><a href="<?php echo APPLICATION_ROOT . '/app/views/home.php'; ?>">Tasks</a></li>
                        <li><a href="<?php echo APPLICATION_ROOT . '/app/controller/auth.php'; ?>">Logout</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>

        <div class="container">
            <form action="<?php echo CONTROLLER . "/todo.php" ?>" method="POST">
                <div class="row" style="margin-top:20px">
                    <div class="col-xs-12">
                        <h4>Update Task</h4>
                    </div>                
                </div>
                <div class="clearfix" />
                <div class="row">
                    <div class="col-xs-2">
                        <label>Description</label>
                    </div>
                    <div class="col-xs-10">
                        <input type="hidden" name="taskId" value="<?php echo $taskId; ?>" />
                        <input type="text" name="description" size="100" value="<?php echo $task[todo_DESCRIPTION] ?>" />
                    </div>
                </div>
                <div class="clearfix" />
                <div class="row" style="margin-top: 5px">
                    <div class="col-xs-2">
                        <label>Status</label>
                    </div>
                    <div class="col-xs-10">
                        <select name="status">
                            <option value="Not Started" <?php if ($task[todo_STATUS] === "Not Started") echo "selected"; ?>>Not Started</option>
                            <option value="Started" <?php if ($task[todo_STATUS] === "Started") echo "selected"; ?>>Started</option>
                            <option value="Mid-way" <?php if ($task[todo_STATUS] === "Mid-way") echo "selected"; ?>>Mid-way</option>
                            <option value="Completed" <?php if ($task[todo_STATUS] === "Completed") echo "selected"; ?>>Completed</option>
                        </select>
                        <input type="submit" name="action" value="Update" />
                    </div>
                </div>
                <div class="clearfix" />
            </form>
        </div><!-- /.container -->


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="<?php echo JS ?>/bootstrap.min.js"></script>        
    </body>
</html>
