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
			<span></span>
		</div>
		<div class="row">
			<div class="form-group col-md-6" >
				<label class="control-label" for="product">Product</label>
					<select id="product" name="product" class="form-control"/>
						<option value="Fresh mushroom">Fresh mushroom</option>
						<option value="Mushroom chips">Mushroom chips</option>
					</select>
			</div>

			<!-- Button Drop Down -->
			<div class="form-group col-md-6">
				<label class="control-label" for="quantity">Quantity</label>
				<div class="input-group">
					<input id="quantity" name="quantity" class="form-control" placeholder="Quantity" type="text"/>
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
				<input id="name" name="name" type="text" placeholder="Full name" class="form-control input-md" required=""/>
				<span class="help-block">Please enter your full name here</span>
			</div>

			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="email">Email</label>
				<input id="email" name="email" type="text" placeholder="Email" class="form-control input-md" required=""/>
				<span class="help-block">Please enter your email address so we can contact you</span>
				<div class="error"></div>
			</div>
		</div>

		<div class="row">
			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="phone">Phone</label>
				<input id="phone" name="phone" type="tel" placeholder="Phone number" class="form-control input-md" required=""/>
				<span class="help-block">Please enter your phone number so we can call you about your order</span>
			</div>

			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="address">Address</label>
				<textarea id="address" name="address" type="text" placeholder="Street address" class="form-control input-md" required="">
				</textarea>
				<span class="help-block">Please enter mailing/shipping address</span>
			</div>
		</div>

		<div class="row">
			<!-- Text input-->
			<div class="form-group col-md-6">
				<label class="control-label" for="city">City</label>
				<input id="city" name="city" type="text" placeholder="City" class="form-control input-md" required=""/>
				<span class="help-block">City </span>
			</div>

			<!-- Select Basic -->
			<div class="form-group col-md-6">
				<label class="control-label" for="country">Country</label>
				<select id="country" name="country" class="form-control">
					<option value="1">Ghana</option>
					<option value="2">Japan</option>
					</select>
			</div>
		</div>

		<div class="row">
			<!-- Button -->
			<div class="form-group col-md-6">
				<input class="form-control" type="hidden" name="task" value="order">
				<label class="control-label sr-only" for="send">Submit</label>
				<button id="send" name="send" class="btn btn-primary">Send</button>
			</div>
		</div>
	</form>
</fieldset>
