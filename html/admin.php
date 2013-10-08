<?php
include_once('lib/csrf.php');
include_once('lib/auth.php');
//session_start();
if (!auth()){
	//header('Refresh:3; login.php');
	//echo 'You are not logined <br>Redirecting you to login page in 3 second...';
	header('Location: login.php');
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>IERG4210 Calvin Shop - Admin Panel</title>
	<link href="incl/admin.css" rel="stylesheet" type="text/css"/>
</head>

<body>
<h1>IERG4210 Calvin Shop - Admin Panel
<form id="logout" method="POST" action="auth-process.php?action=logout">
     <input type="submit" value="Logout" />
</form>
<form id="chgPwd" method="POST" action="chgPwd.php">
     <input type="submit" value="Change Password" />
</form>
</h1>
<!--<a href="chgPwd.php">Change Password</a>-->
<article id="main">

<section id="categoryPanel">
	<fieldset>
		<legend>New Category</legend>
		<!--<form id="cat_insert" method="POST" action="admin-process.php?action=cat_insert" onsubmit="return false;">-->
			<form id="cat_insert" method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_insert'); ?>">
			<label for="cat_insert_name">Name <?php echo $action; ?></label>
			<div><input id="cat_insert_name" type="text" name="name" required="true" pattern="^[\w\- ]+$" /></div>
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
			<input type="submit" value="Submit" />
		</form>
	</fieldset>
	
	<!-- Generate the existing categories here -->
	<ul id="categoryList"></ul>
</section>

<section id="categoryEditPanel" class="hide">
	<fieldset>
		<legend>Editing Category</legend>
		<!--<form id="cat_edit" method="POST" action="admin-process.php?action=cat_edit" onsubmit="return false;">-->
			<form id="cat_edit" method="POST" action="admin-process.php?action=<?php echo ($action = 'cat_edit'); ?>" onsubmit="return false;">
			<label for="cat_edit_name">Name</label>
			<div><input id="cat_edit_name" type="text" name="name" required="true" pattern="^[\w\- ]+$" /></div>
			
			<input type="hidden" id="cat_edit_catid" name="catid" />
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
			<input type="submit" value="Submit" /> <input type="button" id="cat_edit_cancel" value="Cancel" />
		</form>
	</fieldset>
</section>

<section id="productPanel">
	<fieldset>
		<legend>New Product</legend>
		<!--<form id="prod_insert" method="POST" action="admin-process.php?action=prod_insert" enctype="multipart/form-data">-->
			<form id="prod_insert" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_insert'); ?>" enctype="multipart/form-data">
			<label for="prod_insert_catid">Category *</label>
			<div><select id="prod_insert_catid" name="catid" required="true"></select></div>

			<label for="prod_insert_name">Name *</label>
			<div><input id="prod_insert_name" type="text" name="name" required="true" pattern="^[\w\- ]+$" /></div>

			<label for="prod_insert_price">Price *</label>
			<!--<div><input id="prod_insert_price" type="number" name="price" required="true" pattern="^[\d\.]+$" /></div>-->
			<div><input id="prod_insert_price" type="number" name="price" required="true" pattern="^[\d\.]+$" step="0.1"/></div>
			
			<label for="prod_insert_description">Description</label>
			<div><textarea id="prod_insert_description" name="description" pattern="^[\w\- ]+$" /></textarea></div>

			<label for="prod_insert_name">Image *</label>
			<div><input type="file" name="file" required="true" accept="image/jpeg" /></div>
			<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
			<input type="submit" value="Submit" />
		</form>
	</fieldset>
	<!-- Generate the corresponding products here -->
	<ul id="productList"></ul>
</section>

<section id="productEditPanel" class="hide">
		<fieldset>
			<legend>Editing Product</legend>
			<!--<form id="prod_edit" method="POST" action="admin-process.php?action=prod_edit" enctype="multipart/form-data">-->
				<form id="prod_edit" method="POST" action="admin-process.php?action=<?php echo ($action = 'prod_edit'); ?>" enctype="multipart/form-data">
				<label for="prod_edit_name">Name</label>
				<div><input id="prod_edit_name" type="text" name="name" required="true" pattern="^[\w\- ]+$" /></div>
				
				<label for="prod_edit_catid">Category</label>
				<div><select id="prod_edit_catid" name="catid" required="true"></select></div>
				
				<label for="prod_edit_price">Price</label>
				<!--<div><input id="prod_edit_price" type="number" name="price" required="true"  pattern="^[\d\.]+$"/></div>-->
				<div><input id="prod_edit_price" type="number" name="price" required="true"  pattern="^[\d\.]+$" step="0.1"/></div>
				
				<label for="prod_edit_description">Description</label>
				<div><textarea id="prod_edit_description" name="description" pattern="^[\w\- ]+$" /></textarea></div>
				
				<label for="prod_edit_image">Change Image ?</label>
				<div><input type="file" name="file" accept="image/jpeg" /></div>
				
				<input type="hidden" id="prod_edit_pid" name="pid" />
				<input type="hidden" name="nonce" value="<?php echo csrf_getNonce($action); ?>"/>
				<input type="submit" value="Submit" /> <input type="button" id="prod_edit_cancel" value="Cancel" />
			</form>
		</fieldset>
</section>
<section id="txnTable">
<fieldset style="width:900px">
<legend>Lastest 50 Transaction Records</legend>
<table>
<tr><th width="70">Order ID</th><th width="400">Digest</th><th width="200">Salt</th><th width="200">Transaction ID</th></tr>
<?php
$db = new PDO('sqlite:/var/www/orders.db');
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$q=$db->prepare('SELECT * FROM orderlist ORDER BY oid DESC LIMIT 50');
	if ($q->execute()){
		$OrderRecord=$q->fetchAll();}
	foreach($OrderRecord as $Rcd){
		?><tr><th width="70"><?php echo $Rcd['oid'];?></th><th width="400"><?php echo $Rcd['digest'];?></th><th width="200"><?php echo $Rcd['salt'];?></th><th width="200"><?php echo $Rcd['pay'];?></th></tr><?php
	}
?>
</table>
</fieldset>
</section>
<div class="clear"></div>
</article>
<script type="text/javascript" src="incl/myLib.js"></script>
<script type="text/javascript">
(function(){

	function updateUI() {
		myLib.get({action:'cat_fetchall'}, function(json){
			// loop over the server response json
			//   the expected format (as shown in Firebug): 
			for (var options = [], listItems = [],
					i = 0, cat; cat = json[i]; i++) {
				options.push('<option value="' , parseInt(cat.catid) , '">' , cat.name.escapeHTML() , '</option>');
				listItems.push('<li id="cat' , parseInt(cat.catid) , '"><span class="name">' , cat.name.escapeHTML() , '</span> <span class="delete">[Delete]</span> <span class="edit">[Edit]</span></li>');
			}
			el('prod_insert_catid').innerHTML = '<option></option>' + options.join('');
			el('categoryList').innerHTML = listItems.join('');
		});
		//el('productList').innerHTML = '';
	}
	updateUI();
	
	function updateUI_prod(id) {
				myLib.get({action:'prod_fetchall',catid:id}, function(json){
					// loop over the server response json
					//   the expected format (as shown in Firebug): 
					for (var options = [], listItems = [],
							i = 0, prod; prod = json[i]; i++) {
							listItems.push('<li id="',id,'prod' , parseInt(prod.pid) , '"><span class="name">' , prod.name.escapeHTML() , '</span> <span class="delete">[Delete]</span> <span class="edit">[Edit]</span></li>');
					}
					el('productList').innerHTML = listItems.join('');
				});
			}
	
	el('categoryList').onclick = function(e) {
		if (e.target.tagName != 'SPAN')
			return false;
		
		var target = e.target,
			parent = target.parentNode,
			id = target.parentNode.id.replace(/^cat/, ''),
			name = target.parentNode.querySelector('.name').innerHTML;
		
		// handle the delete click
		if ('delete' === target.className) {
			/*confirm('You have to delete all those products first, Sure?') && myLib.post({action: 'prod_delete_by_catid', catid: id}, function(json){
				alert('They are deleted successfully!');
				updateUI_prod(id);
			});
			confirm('Sure to delete this category?')
				&& myLib.post({action: 'cat_delete', catid: id}, function(json){
					alert('"' + name + '" is deleted successfully!');
					updateUI();
			});*/
			if (confirm('Sure to delete this category?')){
				myLib.post({action: 'prod_delete_by_catid', catid: id}, function(json){
					updateUI_prod(id);
				});
				myLib.post({action: 'cat_delete', catid: id}, function(json){
					alert('"' + name + '" is deleted successfully!');
					updateUI();
				});
			}
		
		// handle the edit click
		} else if ('edit' === target.className) {
			// toggle the edit/view display
			el('categoryEditPanel').show();
			el('categoryPanel').hide();
			
			// fill in the editing form with existing values
			el('cat_edit_name').value = name;
			el('cat_edit_catid').value = id;
		
		//handle the click on the category name
		} else {
			el('prod_insert_catid').value = id;
			// populate the product list or navigate to admin.php?catid=<id>			
			updateUI_prod(id);
		
		}
	}
	
	el('cat_insert').onsubmit = function() {
		return myLib.submit(this, updateUI);
	}
	el('cat_edit').onsubmit = function() {
		return myLib.submit(this, function() {
			// toggle the edit/view display
			el('categoryEditPanel').hide();
			el('categoryPanel').show();
			updateUI();
		});
	}
	el('cat_edit_cancel').onclick = function() {
		// toggle the edit/view display
		el('categoryEditPanel').hide();
		el('categoryPanel').show();
	}
	
	el('productList').onclick = function(e) {
		if (e.target.tagName != 'SPAN')
			return false;
		
		var target = e.target,
			parent = target.parentNode,
			ccid = target.parentNode.id.replace(/prod\d*/, ''),
			ppid = target.parentNode.id.replace(/\d*prod/, ''),
			name = target.parentNode.querySelector('.name').innerHTML;
		
		// handle the delete click
		if ('delete' === target.className) {
			confirm('Sure?') && myLib.post({action: 'prod_delete', pid: ppid}, function(json){
				alert('"' + name + '" is deleted successfully!');
				updateUI_prod(ccid);
			});
		// handle the edit click
		}else if (('edit' === target.className)||('name' === target.className)) {
			// toggle the edit/view display
			el('productEditPanel').show();
			el('productPanel').hide();
			
			myLib.get({action:'cat_fetchall'}, function(json){
				for (var options = [], listItems = [],
						i = 0, cat; cat = json[i]; i++) {
							options.push('<option value="' , parseInt(cat.catid) , '">' , cat.name.escapeHTML() , '</option>');
						}
				el('prod_edit_catid').innerHTML = '<option></option>' + options.join('');
			});
			
			// fill in the editing form with existing values
			el('prod_edit_name').value = name;
			myLib.get({action:'prod_fetchOne', pid:ppid}, function(json){
				el('prod_edit_price').value = json[0].price;
				el('prod_edit_description').value = json[0].description;
				el('prod_edit_catid').value = json[0].catid;
			});
			el('prod_edit_pid').value = ppid;
		}
	}
	
	el('prod_edit_cancel').onclick = function() {
		// toggle the edit/view display
		el('productEditPanel').hide();
		el('productPanel').show();
	}
	
})();
</script>
</body>
</html>
