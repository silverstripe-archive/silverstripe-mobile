<div data-role="navbar">
	<ul class="menu1">
		<% loop Menu(1) %>
			<% if URLSegment == home %><% else %>
			<li class="$LinkingMode">
				<a href="$AbsoluteLink" class="<% if isSection %>ui-btn-active<% end_if %>">
					$MenuTitle
				</a>
			</li>
			<% end_if %>
		<% end_loop %>
	</ul>
</div>