<% if Level(2) %>
	<div class="breadcrumbs">$Breadcrumbs</div>
<% end_if %>

<div class="content typography">
	$Content
</div>

<% if Menu(2) %>
	<ul class="menu2" data-role="listview" data-inset="true">
	<% control Menu(2) %>
		<li class="$LinkingMode">
			<a href="$Link" class="<% if isSection %>ui-btn-active<% end_if %>">
				$MenuTitle
			</a>
		</li>
	<% end_control %>
	</ul>
<% end_if %>