<% require css(clickbank/css/clickbank.css) %>

<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>

	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
		<div class="clickbank-product">
			<h2>$Title</h2>
			<% if ProductImage %>
				<div class="clickbank-product-image">
					$ProductImage.SetWidth(150)
				</div>
			<% end_if %>
			<div class="clickbank-product-details">
				$Content
			</div>
			<div class="clear"></div>
		</div>
		$Form
		$PageComments
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>