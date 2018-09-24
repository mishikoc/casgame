<!DOCTYPE html>
<html>
<head>
	<title>Best Game</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link rel="icon" href="/www/cas/img/favicon.ico"/>
    <?php echo Asset::css('style.css'); ?>
    <?php echo Asset::css('bootstrap.css'); ?>
    <?php echo Asset::js('jquery-1.7.2.js'); ?>
    <?php echo Asset::js('script.js'); ?>
</head>
<body>
	<header>
		<div class="container">
        	<div class="row">
                <div class="col-md-3">
                <div id="logo" class="logo"></div>
                </div>
                <div class="col-md-3">
                <div class="title"><h1>Best game</h1></div>
                </div>
            </div>
        </div>
	</header>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1><?php echo $title; ?> <small>We can't find that!</small></h1>
				<hr>
				<p>The controller generating this page is found at <code>APPPATH/classes/controller/welcome.php</code>.</p>
				<p>This view is located at <code>APPPATH/views/welcome/404.php</code>.</p>
			</div>
		</div>
		<footer>
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
			<p>
				<a href="http://fuelphp.com">FuelPHP</a> is released under the MIT license.<br>
				<small>Version: <?php echo Fuel::VERSION; ?></small>
			</p>
		</footer>
	</div>
</body>
</html>
