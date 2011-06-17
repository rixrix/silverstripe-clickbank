<div class="typography">
	<% if Menu(2) %>
		<% include SideBar %>
		<div id="Content">
	<% end_if %>

	<% if Level(2) %>
	  	<% include BreadCrumbs %>
	<% end_if %>
		<p class="memberProfileSelf right">
			This is your profile! <a href="$Link">Edit Profile</a> | <a href="{$Link}clickbankProfile">Edit ClickBank Profile</a>
		</p>	
		<h2>$Title</h2>
	
		$Content
		$ClickBankProfileForm
	<% if Menu(2) %>
		</div>
	<% end_if %>
</div>

