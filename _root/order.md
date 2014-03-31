---
layout : default
title : Order
---

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Place an order</h3>
		<div class="load"><i class="fa fa-spinner fa-spin"></i></div>
	</div>
	<div class="panel-body">
		<form class="form-horizontal" action="#" method="post" id="order-form" novalidate="novalidate">
		<div class="form-group">
			<label class="col-md-4 control-label" for="product">Product</label>
			<div class="col-md-4">
				<select id="product" name="entry.1877278473" class="form-control"/>
					<option value="Fresh mushroom">Fresh mushroom</option>
					<option value="Mushroom chips">Mushroom chips</option>
				</select>
			</div>
			</div>
		</form>
	</div>
	<div class="panel-footer">
		<div class="alert alert-danger error" style="display:none;"><span></span></div>
	</div>
</div>

<div class="row">

<fieldset>
<!-- Form Name -->
<legend>Form Name</legend>

<!-- Select Basic -->
<div class="form-group">

</div>

<!-- Button Drop Down -->
<div class="form-group">
  <label class="col-md-4 control-label" for="quantity">Quantity</label>
  <div class="col-md-4">
	<div class="input-group">
	  <input id="quantity" name="entry.1204439759" class="form-control" placeholder="Quantity" type="text"/>
	  <div class="input-group-btn">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
		  1
		  <span class="caret"></span>
		</button>
		<ul class="dropdown-menu pull-right">
		  <li><a href="#">2</a></li>
		  <li><a href="#">3</a></li>
		  <li><a href="#">4</a></li>
		</ul>
	  </div>
	</div>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="name">Full name</label>
  <div class="col-md-4">
  <input id="name" name="entry.1611368027" type="text" placeholder="Full name" class="form-control input-md" required=""/>
  <span class="help-block">Please enter your full name here</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">Email</label>
  <div class="col-md-4">
  <input id="email" name="entry.1332200273" type="text" placeholder="Email" class="form-control input-md" required=""/>
  <span class="help-block">Please enter your email address so we can contact you</span>
  <div class="error"></div>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="phone">Phone</label>
  <div class="col-md-4">
  <input id="phone" name="entry.1125117768" type="text" placeholder="Phone number" class="form-control input-md" required=""/>
  <span class="help-block">Please enter your phone number so we can call you about your order</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="address">Address</label>
  <div class="col-md-4">
  <input id="address" name="entry.1132168120" type="text" placeholder="Street address" class="form-control input-md" required=""/>
  <span class="help-block">Please enter mailing/shipping address</span>
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="city">City</label>
  <div class="col-md-4">
  <input id="city" name="entry.879379036" type="text" placeholder="City" class="form-control input-md" required=""/>
  <span class="help-block">City </span>
  </div>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="country">Country</label>
  <div class="col-md-4">
	<select id="country" name="entry.324110172" class="form-control">
	  <option value="1">Ghana</option>
	  <option value="2">Japan</option>
	</select>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="send">Submit</label>

  <div class="col-md-4">
	<button id="send" name="send" class="btn btn-primary">Send</button>
  </div>
</div>
</fieldset>
</form>
</div>