<%-- Alternate template rendering nav with more pages as a list rather than a navigation bar --%>
<div>
	<ul class="menu1" data-role="listview" data-inset="true">
		<% control Menu(1) %>
			<% if URLSegment == home %><% else %>
			<li class="$LinkingMode">
				<a href="$AbsoluteLink" class="<% if isSection %>ui-btn-active<% end_if %>">
					$MenuTitle
				</a>
			</li>
			<% end_if %>
		<% end_control %>
	</ul>
</div>