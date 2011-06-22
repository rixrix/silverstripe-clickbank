<% require css(clickbank/css/clickbank.css) %>

<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>

	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
		<% if Title %>
			<h2>$Title</h2>
		<% end_if %>
		$Content
		
		<% if ClickBankProducts %>
			<ul id="product-list">
				<% control ClickBankProducts %>
					<li>
						<div class="clickbank-product-item">
							<% if ProductImage %>
								<div class="clickbank-product-item-image">
									$ProductImage.SetWidth(50)
								</div>
							<% end_if %>
							<div class="clickbank-product-item-info">
								<% if MenuTitle %>
									<h3><a href="$Link">$MenuTitle</a></h3>
								<% else %>
									<h3><a href="$Link">$Title</a></h3>
								<% end_if %>
								
								<% if Content %>
				          			$Content.LimitCharacters(200)
						  		<% end_if %>
					  		
					  			<a class="readMoreLink" href="$Link" title="Read more" >Read more</a>
					  		</div> <!-- /.clickbank-product-item-info -->
					  		<div class="clear"></div>
				  		</div> <!-- /.clickbank-product-item -->
					</li>
				<% end_control %>
			</ul>
		<% else %>
			<p>
				You don't have any products.
			</p>
		<% end_if %>
		<div class="clear"></div>
		<br />
		<% if ClickBankProducts.MoreThanOnePage %>
			<div id="PageNumbers">
				<% if ClickBankProducts.NotFirstPage %>
					<a class="prev" href="$ClickBankProducts.PrevLink" title="View the previous page">Prev</a>
				<% end_if %>
				
				<% if ClickBankProducts.NotLastPage %>
					<a class="next" href="$ClickBankProducts.NextLink" title="View the next page">Next</a>
				<% end_if %>
				
				<span>
					<% control ClickBankProducts.Pages %>
						<% if CurrentBool %>
							$PageNum
						<% else %>
							<a href="$Link" title="View page number $PageNum">$PageNum</a>
						<% end_if %>
					<% end_control %>
				</span>
			</div>
		<% end_if %>
		
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>