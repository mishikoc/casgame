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
    <?php echo Asset::js('carousel.js'); ?>
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
        <?= View::forge('cas/login'); ?>
	<div class="container">
		<div class="row">
            <div class="col-md-12">
            <div class="work">
            <ul id="carusel">
            </ul>
            </div>

            </div>
			<div class="col-md-4">

			</div>
			<div class="col-md-4">
            <input name="" type="button" class="btn btn-success start" value="START">
			</div>
			<div class="col-md-4">
<p><tt id="results"></tt></p>
			</div>
		</div>
		<hr/>
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
