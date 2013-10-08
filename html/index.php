<!DOCTYPE html>

<html>
<?php
	include_once('lib/db.inc.php');
	$db = ierg4210_DB();
	$q = $db->prepare("SELECT * FROM categories LIMIT 100;");
	$q->execute();
	$cat = $q->fetchAll();
?>
<head>
	<title>Calvin Chan Store</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
			<div id="header">
				<h1>Calvin Store  <div class="fb-like" data-href="http://www.shop105.ierg4210.org" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true"></div></h1>
			</div>
			<dl id="category">
				<dt>Categories</dt>
				<?php for ($i = 0;$i<sizeof($cat);$i++) {?>
				<dd>
				<a href="index.php?catid=<?php echo $cat[$i]['catid'];?>"><?php echo $cat[$i]['name'];?></a></dd>
				<?php } ?>
			</dl>
			<div id="shoppingCart">
				Shopping cart HK $<span id="calculatTotal">0.0</span>
				<form method="POST" action="https://www.sandbox.paypal.com/cgi-bin/webscr" onsubmit="return ui.cart.submit(this)">
					<ul id="shoppingCartList"></ul>
					<input type="hidden" name="cmd" value="_cart" />
					<input type="hidden" name="upload" value="1" />
					<input type="hidden" name="business" value="s09634_1353836825_biz@mailserv.cuhk.edu.hk" />
					<input type="hidden" name="currency_code" value="HKD" />
					<input type="hidden" name="charset" value="utf-8" />
					<input type="hidden" name="custom" value="0" />
					<input type="hidden" name="invoice" value="0" />
					<input type="submit" value="Checkout" />
				</form>
			</div>
			<ul class="navigationMenu">
				<li><a href="index.php">Home</a></li>
				<li>></li>
				<li><?php if ($_GET['catid'])
								{
								echo $cat[(int)$_GET['catid']-1]['name'];
								}
							else {
								echo 'Please select a category';
								}
					?></li>
			</ul>
			<ul class="productTable">
			<?php 
				if (!preg_match('/^\d*$/', $_GET['catid']))
					{header('Location: index.php'); exit();}
				if ($_GET['catid'])
				{$q2 = $db->prepare("SELECT * FROM products WHERE catid = ?;");
				$q2->execute(array((int)$_GET['catid']));
				$prod = $q2->fetchAll();
				if (!$prod) {header('Location: index.php'); exit();}
				for ($i=0;$i<sizeof($prod);$i++){
			?>
				<li>
				<a href="productPage.php?pid=<?php echo $prod[$i]['pid']?>"><img src="incl/img/<?php echo $prod[$i]['pid']?>.jpg" width="100" height="99"/></a>
				<dt>
					<br/>
					<a href="productPage.php?pid=<?php echo $prod[$i]['pid']?>"><?php echo $prod[$i]['name']?></a>
				</dt>
				<dt>
					<br/>$
					<?php echo $prod[$i]['price']?>
				</dt>
				<br/>
					<input type="button" value="Add To Cart" onclick="ui.cart.add(<?php echo $prod[$i]['pid']?>)">
			  </li>
			<?php
				}}
			?>
			</ul>
			<div class="clear"></div>
			<div id="footer">
              Web design by Calvin Chan
			</div>
		<!--</div>
	</div>-->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=463355560368281";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript" src="incl/myLib.js"></script>
<script type="text/javascript" src="incl/ui.js"></script>
</body>
</html>