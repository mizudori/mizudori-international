---
layout : default
title : Order
---


<fieldset>
	<form  action="#" method="post" id="order-form" novalidate="novalidate" role="form">
		<!-- Form Name -->
		<legend> Place an order <span class="load"><i class="fa fa-spinner fa-spin"></i></span></legend>

		<div class="error alert alert-danger alert-dismissable" style="display: none;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Form error</strong> <span>die already</span>
		</div>
		<div class="row">
			<div class="form-group col-md-6" >
				<label class="control-label" for="product">Product</label>
					<select id="product" name="entry.1877278473" class="form-control"/>
						<option value="Fresh mushroom">Fresh mushroom</option>
						<option value="Mushroom chips">Mushroom chips</option>
					</select>
			</div>

			<!-- Button Drop Down -->
			<div class="form-group col-md-6">
				<label class="control-label" for="quantity">Quantity</label>
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

		<div class="row">
			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="name">Full name</label>
				<input id="name" name="entry.1611368027" type="text" placeholder="Full name" class="form-control input-md" required=""/>
				<span class="help-block">Please enter your full name here</span>
			</div>

			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="email">Email</label>
				<input id="email" name="entry.1332200273" type="text" placeholder="Email" class="form-control input-md" required=""/>
				<span class="help-block">Please enter your email address so we can contact you</span>
				<div class="error"></div>
			</div>
		</div>

		<div class="row">
			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="phone">Phone</label>
				<input id="phone" name="entry.1125117768" type="tel" placeholder="Phone number" class="form-control input-md" required=""/>
				<span class="help-block">Please enter your phone number so we can call you about your order</span>
			</div>

			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="address">Address</label>
				<input id="address" name="entry.1132168120" type="text" placeholder="Street address" class="form-control input-md" required=""/>
				<span class="help-block">Please enter mailing/shipping address</span>
			</div>
		</div>

		<div class="row">
			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="city">City</label>
				<input id="city" name="entry.879379036" type="text" placeholder="City" class="form-control input-md" required=""/>
				<span class="help-block">City </span>
			</div>

			<!-- Select Basic -->
			<div class="form-group col-md-6">
				<label class="control-label" for="country">Country</label>
				<select id="country" name="entry.324110172" class="form-control">
					<option value="1">Ghana</option>
					<option value="2">Japan</option>
					</select>
			</div>
		</div>

		<div class="row">
			<!-- Button -->
			<div class="form-group col-md-6">
				<label class="control-label sr-only" for="send">Submit</label>
				<button id="send" name="send" class="btn btn-primary">Send</button>
			</div>
		</div>
	</form>
</fieldset>